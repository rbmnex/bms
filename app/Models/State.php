<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use App\Facades\Transaction;
use Exception;

class State extends Model
{
    //
    protected $table = 'public.state';

    public static function searchIdByWarrant(string $warrant) {
        $stateCode = substr($warrant,0,2);
        try {
            $myState = DB::connection('pgsql2')->table('l_negeri as negeri')
            ->select('negeri.negeri as nama')
            ->where('kod_negeri', $stateCode)->first();

            
        } catch (Exception $e) { 
            report($e);
            return NULL;
        }

        if($myState) {
            $state = DB::table('public.state')->where('name',$myState->nama)->first();

            if($state) {
                return $state->id;
            } else {
                $input = [
                    'name' => $myState->nama,
                    'enabled' => '1',
                    'created_by' => 'Mykj',
                    'created_at' => Date::now()
                ];

                $id = Transaction::saveToTable("public.district", $input);
                return $id;
            }
        } else {
            return NULL;
        }
    }
}
