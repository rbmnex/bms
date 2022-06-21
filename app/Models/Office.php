<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use App\Facades\Transaction;

class Office extends Model
{
    //
    protected $table = 'office';

    
    public function district()
    {
        return $this->belongsTo('App\Models\District','district_id');
    }
    
    public function state()
    {
      return $this->belongsTo('App\Models\State','state_id');
    }

    public static function lookupByState($stateId)
    {
        $results = DB::table("public.office as office")
                    ->select('office.id as id','office.name as name','office.hq as hq')
                    ->join('public.state as state','office.state_id','state.id')
                    ->where([['state.id','=',$stateId],['office.enabled','=','1']])
                    ->get();

        return $results;
    }

    public static function searchSettlement(string $code) {
      $lWaranPej = DB::connection('pgsql2')->table('l_waran_pej')->where('kod_waran_pej', 'like', $code.'%')->first();
      if($lWaranPej) {
        $namaPej = trim($lWaranPej->waran_pej);
        $office =  DB::table('public.office')->whereRaw("UPPER(name) = '".strtoupper($namaPej)."'")->first();
        if($office) {
          return $office->id;
        } else {
          $id = DB::table('public.office')->insertGetId(
              ['name' => $namaPej,
            'created_by' => 'Mykj', 'created_at' => Date::now()]
          );

          return $id;
        }
      } else {
        return NULL;
      }
    }

    public static function searchId(string $name)
    {
      $office = DB::table('public.office')->where('name',$name)->first();
      if($office) {
        return $office->id;
      } else {
        $id = DB::table('public.office')->insertGetId(
            ['name' => $name,
          'created_by' => 'Mykj', 'created_at' => Date::now()]
        );

        return $id;
      }
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("public.office", $input);

        return $id;
    }
}
