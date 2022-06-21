<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\Transaction;

class Substructure extends Model
{
    //
    protected $table = "substructure";
    const ABUTMENT = "Abutment";
    const PIER = "Pier";

    public function type()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'type_id');
    }

    public function material()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'material_id');
    }

    public function foundation()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'foundation_id');
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.substructure", $input);

        return $id;
    }
}
