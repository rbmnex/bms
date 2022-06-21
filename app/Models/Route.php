<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Facades\Transaction;

class Route extends Model
{
    //
    protected $table = 'route';
    protected $fillable = ['code','name','stretches_id','created_by','updated_by'];

    public function type()
    {
        return $this->belongsTo('App\Models\MasterLookup','stretches_id');
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.route", $input);

        return $id;
    }

    public static function search(array $input) 
    {
        $result =  DB::table('bms.public.route')->select(
            'bms.public.route.id as id',
            'bms.public.route.code as code',
            'bms.public.route.name as name',
            'bms.public.master_lookup.name as type'
        )
            ->join('bms.public.master_lookup', 'bms.public.route.stretches_id', '=', 'bms.public.master_lookup.id')
            ->where($input)->get();

        return $result;
    }
}
