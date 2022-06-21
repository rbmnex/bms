<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\Transaction;

class Administrative extends Model
{
    //
    protected $table = 'administrative';

    public function equipment() 
    {
        return $this->belongsTo('App\Models\MasterLookup', 'access_equipment_id');
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.administrative", $input);

        return $id;
    }
}
