<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Facades\Lookup;
use App\User;
use App\Models\Role;
use App\Custom\CommonHelper;
use App\Mail\UserCreate;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $states = Lookup::loadLookup('bms.public.state');
        $positions = Lookup::loadLookup('bms.public.position');
        $departments = Lookup::loadLookup('bms.public.department');
        $roles = Lookup::loadLookup('bms.public.roles', 'display_name');
        return view('setting.user-form', compact(
            'states',
            'positions',
            'departments',
            'roles'
        ));
    }

    public function loadUsers(Request $request)
    {
        if ($request->ajax()) {
            $users = User::fetch();

            return Datatables::of($users)->addColumn('action', function ($user) {

                $btn = '<a href="'.route('user.edit').'?id=' . $user->id . '" class="btn btn-sm btn-info"><span class="ml-1 fa fa-edit"></span>Edit</a>';

                return $btn;
            })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function saveUser(Request $request)
    {
        $data = $request->all();

        $pasword = CommonHelper::randomPassword();

        $user = User::where('ic_no',$data['ic_no'])->first();

        if($user) {
            return redirect()->back()->with('message','This user already existed in this system')->withInput();
        }

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'ic_no' => $data['ic_no'],
            'gender' => $data['gender'],
            'telephone_no' => $data['phone'],
            'position_id' => $data['position'],
            'department_id' => $data['department'],
            'office_id' => $data['office'],
            'state_id' => $data['state'],
            'enabled' => isset($data['enabled']) ? $data['enabled'] : '0',
            'created_at' => Date::now(),
            'created_by' => Auth::user()->ic_no,
            'password' => Hash::make($pasword),
            'type' => 2
        //  'district_id' => $data['district'],  
        ];

        $id = User::insert($userData);

        $roles = json_decode($data['rolelist'],true);

        foreach ($roles as $role) {
            Role::assignUserRole($id, $role);
        }
        /*
        if (strpos($data['rolelist'], ',') === true) {
            $roles = explode(',', $data['rolelist']);
        } else {
            Role::assignUserRole($data['user_id'], $data['rolelist']);
        }
        */

        // send to email to user notify account
        if($this->email) {
            try {
                Mail::to($data['email'])->send(new UserCreate($data['name'], $pasword));
            } catch (Exception $e) {
                report($e);
            }
        }

        return redirect(route('user.list'))->with('message','The user successful been saved');
    }

    public function profile(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $states = Lookup::loadLookup('bms.public.state');
        $positions = Lookup::loadLookup('bms.public.position');
        $departments = Lookup::loadLookup('bms.public.department');
        $roles = Lookup::loadLookup('bms.public.roles', 'display_name');
        $show = false;

        return view('setting.user-form', compact('user', 'states', 'positions', 'departments', 'roles', 'show'));
    }

    public function show(Request $request)
    {
        $user = User::find($request->id);
        $states = Lookup::loadLookup('bms.public.state');
        $positions = Lookup::loadLookup('bms.public.position');
        $departments = Lookup::loadLookup('bms.public.department');
        $roles = Lookup::loadLookup('bms.public.roles', 'display_name');
        $show = true;

        return view('setting.user-form', compact('user', 'states', 'positions', 'departments', 'roles', 'show'));
    }

    public function modify(Request $request) 
    {
        $data = $request->all();

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'ic_no' => $data['ic_no'],
            'gender' => $data['gender'],
            'telephone_no' => $data['phone'],
            'position_id' => $data['position'],
            'department_id' => $data['department'],
            'office_id' => $data['office'],
            'state_id' => $data['state'],
            'enabled' => isset($data['enabled']) ? $data['enabled'] : '0',
            'updated_at' => Date::now(),
            'updated_by' => Auth::user()->ic_no
//          'district_id' => $data['district'],            
        ];

        User::where('id', '=', $data['user_id'])->update($userData);

        return redirect(route('user.profile'))->with('message','Your profile successful been saved');
    }

    public function update(Request $request)
    {
        $data = $request->all();

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'ic_no' => $data['ic_no'],
            'gender' => $data['gender'],
            'telephone_no' => $data['phone'],
            'position_id' => $data['position'],
            'department_id' => $data['department'],
            'state_id' => $data['state'],
            'office_id' => $data['office'], 
            'enabled' => isset($data['enabled']) ? $data['enabled'] : '0',
            'updated_at' => Date::now(),
            'updated_by' => Auth::user()->ic_no
//          'district_id' => $data['district'],
        ];

        User::where('id', '=', $data['user_id'])->update($userData);

        $roles = json_decode($data['rolelist'],true);

        /*
        echo "<pre>";
        print_r($roles);
        echo "</pre>";
        */
        
        Role::clearRoles($data['user_id']);
        foreach ($roles as $role) {
            Role::assignUserRole($data['user_id'], $role);
        }

        /*
        if (strpos($data['rolelist'], ',') === true) {
            $roles = explode(',', $data['rolelist']);
        } else {
            Role::clearRoles($data['user_id']);
            Role::assignUserRole($data['user_id'], $data['rolelist']);
        }
        */

        return redirect(route('user.list'))->with('message','The user successful been updated');
    }

    public function deleteUser(Request $request) {
        $data = $request->all();
        $id = $data['user_id'];
        Role::clearRoles($id);
        User::destroy($id);

        return redirect(route('user.list'))->with('message','The user successful been deleted');
    }

    public function resetPasword(Request $request) {
        
    }
}
