<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\Transaction;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class ConstructionYear extends Model
{
    //
    protected $table = 'construction_year';

    public function bridge()
    {
        return $this->belongsTo('App\Models\Bridge', 'bridge_id');
    }

    public function geometry() {
        return $this->hasOne('App\Models\Geometry', 'year_id');
    }

    public function superstructures() {
        return $this->hasMany('App\Models\Superstructure', 'year_id')->orderBy('type','asc');
    }

    public static function insert(int $bridgeId, $year, $status =  Task::NEW_STATUS)
    {
        $id = Transaction::save(
            "bms.public.construction_year",
            array("bridge_id", "year", "status", "updated_at", "updated_by"),
            array($bridgeId, $year, $status, Date::now(), Auth::user()->ic_no)
        );

        return $id; 
    }
}
