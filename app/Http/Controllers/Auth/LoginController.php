<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Log;
use App\Models\Position;
use App\Models\Office;
use App\Models\District;
use App\Models\State;
use App\User;
use Exception;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/bridge/view?action=list';
    protected $password = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'ic_no';
    }

    public function login(Request $request) {
    //    Log::debug('Request received:');
    //    Log::debug(print_r($request->all()));

		$this->validateLogin($request);

		// If the class is using the ThrottlesLogins trait, we can automatically throttle
		// the login attempts for this application. We'll key this by the username and
		// the IP address of the client making these requests into this application.
		// if ($this->hasTooManyLoginAttempts($request)) {
		// 	$this->fireLockoutEvent($request);
		// 	return $this->sendLockoutResponse($request);
		// }

		// This section is the only change
		if($request->sso == 'NO'){
			if ($this->guard()->validate($this->credentials($request))) {
				$user = User::where('ic_no', $request->ic_no)->first();

				if (($user->type == '2') && $this->attemptLogin($request)) {
					return $this->sendLoginResponse($request);
				} else {
					return redirect()
						->back()
						->withInput($request->only($this->username(), 'remember'))
						->withErrors(['disable' => 'Log Masuk ini hanyalah untuk Pengawal Keselamatan.']);
				}
			}
		}else{
			// if ($this->guard()->validate($this->credentials($request))) {
            /*
			if ($this->attemptLogin($request)) {
				return $this->sendLoginResponse($request);
			} else {
			//	$user = $this->guard()->getLastAttempted();

			}
            */
            try {
                    // check on mykj
                    $myUser = DB::connection('pgsql2')->table('list_pegawai2')->where('nokp', $request->ic_no)->first();

                    if ($myUser) {
                        $user = User::where('ic_no', $request->ic_no)->first();
                        if ($user) {

                                // update user here
                                $userData = [
                                    'name' => strtoupper($myUser->nama),
                                    'email' => $myUser->email,
                                    'password' => Hash::make($request->password),
                                    'telephone_no' => $myUser->tel_bimbit,
                                    'updated_by' => 'MyKj',
                                    'updated_at' => Date::now(),
                                    'position_id' => Position::searchId($myUser->jawatan),
                                    'office_id' => Office::searchSettlement($myUser->bah),
                                    'district_id' => District::searchIdByWarrant($myUser->kod_waran),
                                    'state_id' => State::searchIdByWarrant($myUser->kod_waran),
                                    'warrant_code' => $myUser->kod_waran
                                ];

                                User::where('id', $user->id)->update($userData);


                            if ($this->attemptLogin($request)) {
                                return $this->sendLoginResponse($request);
                            }

                        } else {
                            // insert new user here
                            $userData = [
                                'name' => strtoupper($myUser->nama),
                                'ic_no' => $myUser->nokp,
                                'email' => $myUser->email,
                                'password' => Hash::make($request->password),
                                'telephone_no' => $myUser->tel_bimbit,
                                'type' => 1,
                                'enabled' => '1',
                                'created_by' => 'MyKj',
                                'created_at' => Date::now(),
                                'position_id' => Position::searchId($myUser->jawatan),
                                'office_id' => Office::searchSettlement($myUser->caw),
                                'district_id' => District::searchIdByWarrant($myUser->kod_waran),
                                'state_id' => State::searchIdByWarrant($myUser->kod_waran),
                                'warrant_code' => $myUser->kod_waran
                            ];

                            User::insert($userData);

                            if ($this->attemptLogin($request)) {
                                return $this->sendLoginResponse($request);
                            }
                        }

                    }
                } catch (Exception $e) {
                    report($e);
                }
		}

		return $this->sendFailedLoginResponse($request);
	}

    public function authenticated(Request $request, $user){
        if (Auth::attempt(['ic_no' => $request->ic_no, 'password' => $request->password])) {
        	//dd('gg');
            // Authentication passed...
            return redirect()->intended('bridge/view?action=list');
        }
    }

    protected function credentials(Request $request)
    {
        return array_merge(
            $request->only($this->username(), 'password'),
            ['enabled' => '1']
        );
    }

    private static function split_kod_waran($kod_waran){
        $data = [];
        $data['sektor'] = substr($kod_waran, 0, 2).'0000000000';
        $data['cawangan'] = substr($kod_waran, 0, 4).'00000000';
        $data['bahagian'] = substr($kod_waran, 0, 6).'000000';
        $data['unit'] = substr($kod_waran, 0, 8).'0000';
        $data['waran_penuh'] = $kod_waran;

        return $data;
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/AGLogout');
    }
}
