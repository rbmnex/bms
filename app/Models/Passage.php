<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Facades\Transaction;

class Passage extends Model
{
    //
    protected $table = 'passage';

    public function route() 
    {
        return $this->belongsTo('App\Models\Route','route_id');
    } 

    public function type()
    {
        return $this->belongsTo('App\Models\MasterLookup','type_id');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District','district_id');
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.passage", $input);

        return $id;
    }

    public static function search(array $input = array())
    {
        $query = DB::table("public.passage")
        ->select(
            "public.passage.id as id",
            "public.master_lookup.name as type",
            "public.passage.primary as primary",
            "public.passage.ou as ou",
            "public.passage.kilometer as km",
            "public.passage.meter as meter",
            "public.passage.number as number",
            "public.route.code as code",
            "public.route.name as name",
            "public.state.id as state_id",
            "public.district.id as district_id"
        )
        ->join("public.route", "public.passage.route_id", "public.route.id")
        ->join("public.master_lookup", "public.passage.type_id", "public.master_lookup.id");
        
        
        if(empty($input)) {
            $results = $query->leftJoin("public.district","public.passage.district_id","public.district.id")
            ->leftJoin("public.state","public.district.state_id","public.state.id")->get();
        } else {
                $results = $query->leftJoin("public.district","public.passage.district_id","public.district.id")
                ->leftJoin("public.state","public.district.state_id","public.state.id")
                ->where($input)
                ->get();
        }

        return $results;
    }
}
