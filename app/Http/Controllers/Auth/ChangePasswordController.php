<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\OldPassword;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function change(Request $request) 
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'oldpassword' => ['required', 'string', 'min:8', new OldPassword],
            ]);
            
            /*
            
            $validator = Validator::make($request->all(),[
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'oldpassword' => ['required', 'string', 'min:8', new OldPassword],
                ]);
                
                if ($validator->fails()) {
                    return redirect(route('password.change'))
                    ->withErrors($validator)
                    ->withInput();
                }
                
                */

        User::where('id','=',Auth::user()->id)->update(['password' => Hash::make($request->password)]);

        return redirect(route('password.change'))->with('message','Your password successful been changed');
    }
}
