<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use App\Facades\Transaction;
use Exception;

class District extends Model
{
    //
    protected $table = 'district';

    public function state()
    {
        return $this->belongsTo('App\Models\State','state_id');
    }

    public static function searchIdByWarrant(string $warrant) {
        $districtCode = substr($warrant,0,4);

        try {
            $myDaerah = DB::connection('pgsql2')->table('l_daerah as daerah')
            ->join('l_negeri as negeri','negeri.kod_negeri','daerah.kod_negeri')
            ->select('daerah.nama_daerah as nama_daerah','negeri.negeri as negeri')
            ->where('kod_daerah', $districtCode)->first();

        } catch (Exception $e) {
            report($e);
            return NULL;
        }

        if($myDaerah) {
            $district = DB::table('public.district')->where('name',$myDaerah->nama_daerah)->first();
            if($district){
                return $district->id;
            } else {
                
                $state = DB::table('public.state')->where('name',strtoupper($myDaerah->negeri))->first();

                $input = [
                    'name' => $myDaerah->nama_daerah,
                    'state_id' => $state->id,
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
