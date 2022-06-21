<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\Transaction;

class Superstructure extends Model
{
    //
    protected $table = 'superstructure';

    public function deck()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'deck_id');
    }

    public function system()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'system_id');
    }

    public function material()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'material_id');
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.superstructure", $input);

        return $id;
    }

    
}
