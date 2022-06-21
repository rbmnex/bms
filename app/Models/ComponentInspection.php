<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\Transaction;

class ComponentInspection extends Model
{
    //
    protected $table = "inspection_component";

    public function component()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'component_id');
    }

    public function damage()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'damage_id');
    }

    public function photos()
    {
        return $this->hasMany('App\Models\InspectionPhoto', 'inspection_component_id');
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("public.inspection_component", $input);

        return $id;
    }
}
