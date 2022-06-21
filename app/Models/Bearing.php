<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\Transaction;

class Bearing extends Model
{
    //
    protected $table = "bearings";

    public function fixed()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'fixed_id');
    }

    public function free()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'free_id');
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.bearings", $input);

        return $id;
    }
}
