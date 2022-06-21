<?php

namespace Illuminate\Foundation\Auth;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\ValidationException;
use App\Models\Position;
use App\Models\Office;
use App\User;
use Exception;

trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if($request->sso == 'NO'){
			if ($this->guard()->validate($this->credentials($request))) {
				$user = User::where('ic_no', $request->ic_no)->first();

				if (($user->type == '2') && $this->attemptLogin($request)) {
					return $this->sendLoginResponse($request);
				}else{
					return redirect()
						->back()
						->withInput($request->only($this->username(), 'remember'))
						->withErrors(['disable' => 'Log Masuk ini hanyalah untuk Pengawal Keselamatan.']);
				}
			}
		}else{
			// if ($this->guard()->validate($this->credentials($request))) {
			if ($this->attemptLogin($request)) {
				return $this->sendLoginResponse($request);
			}else{
			//	$user = $this->guard()->getLastAttempted();
                try {
                    // check on mykj
                    $myUser = DB::connection('pgsql2')->table('list_pegawai2')->where('nokp', $request->ic_no)->first();
    
                    if ($myUser) {
                        $user = User::where('ic_no', $request->ic_no)->first();
                        if ($user) {
                            if (empty($user->warrant_code) || ($user->warrant_code != $myUser->kod_waran)) {
                                // update user here
                                $userData = [
                                    'name' => strtoupper($myUser->nama),
                                    'email' => $myUser->email,
                                    'password' => Hash::make($request->password),
                                    'telephone_no' => $myUser->tel_bimbit,
                                    'updated_by' => 'MyKj',
                                    'updated_at' => Date::now(),
                                    'position_id' => Position::searchId($myUser->jawatan),
                                    'office_id' => Office::searchId($myUser->caw),
                                    'district_id' => District::searchIdByWarrant($myUser->kod_waran),
                                    'warrant_code' => $myUser->kod_waran
                                ];
                                User::where('id', $user->id)->update($userData);
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
                                'office_id' => Office::searchId($myUser->caw),
                                'district_id' => District::searchIdByWarrant($myUser->kod_waran),
                                'warrant_code' => $myUser->kod_waran
                            ];
                            User::insert($userData);
                            return $this->sendLoginResponse($request);
                        }
                    }
                } catch (Exception $e) {
                    report($e);
                }
			}
		}

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
