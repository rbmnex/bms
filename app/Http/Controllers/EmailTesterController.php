<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Position;
use App\Models\Office;
use App\Models\District;
use App\Models\State;
use App\User;

class EmailTesterController extends Controller
{
    //
    public function sendMail() {
        
        Mail::raw('Success', function($message) {
            $message->from('bms.test@gov.my', 'BMS');
            $message->to('rubmin@vn.net.my');
            $message->subject('Test Mail Sent');
            $message->setBody( '<html><h1>Success!</h1><p>Mail successfully sent to this email!</p></html>', 'text/html' );
            $message->addPart("Success!\n\nMail successfully sent to this email!", 'text/plain');
        });

        return 'success';
    }

    public function createUser($ic) {
        try {
                    // check on mykj
                    $myUser = DB::connection('pgsql2')->table('list_pegawai2')->where('nokp', $ic)->first();
    
                    if ($myUser) {
                        $user = User::where('ic_no', $ic)->first();
                        if ($user) {
                            
                                // update user here
                                $userData = [
                                    'name' => strtoupper($myUser->nama),
                                    'email' => $myUser->email,
                                    'password' => Hash::make('password'),
                                    'telephone_no' => $myUser->tel_bimbit,
                                    'updated_by' => 'MyKj',
                                    'updated_at' => Date::now(),
                                    'position_id' => Position::searchId($myUser->jawatan),
                                    'office_id' => Office::searchId($myUser->caw),
                                    'district_id' => District::searchIdByWarrant($myUser->kod_waran),
                                    'state_id' => State::searchIdByWarrant($myUser->kod_waran),
                                    'warrant_code' => $myUser->kod_waran
                                ];

                                User::where('id', $user->id)->update($userData);
                            

                            return 'user been inserted';

                        } else {
                            // insert new user here
                            $userData = [
                                'name' => strtoupper($myUser->nama),
                                'ic_no' => $myUser->nokp,
                                'email' => $myUser->email,
                                'password' => Hash::make('password'),
                                'telephone_no' => $myUser->tel_bimbit,
                                'type' => 1,
                                'enabled' => '1',
                                'created_by' => 'MyKj',
                                'created_at' => Date::now(),
                                'position_id' => Position::searchId($myUser->jawatan),
                                'office_id' => Office::searchId($myUser->caw),
                                'district_id' => District::searchIdByWarrant($myUser->kod_waran),
                                'state_id' => State::searchIdByWarrant($myUser->kod_waran),
                                'warrant_code' => $myUser->kod_waran
                            ];

                            User::insert($userData);

                            return 'user been updated';
                        }

                    }
                } catch (Exception $e) {
                    report($e);
                    return 'failed to find user';
                }
    }

    public function deleteUser($ic) {
        try { 
            $user = User::where('ic_no', $ic)->firstOrFail();
            User::destroy($user->id);
            return 'user been deleted';
        } catch (ModelNotFoundException $e) {
                    return 'user not exist';
                }
        
    }
}
