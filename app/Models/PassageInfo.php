<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\Transaction;

class PassageInfo extends Model
{
    //
    protected $table = "passage_info";

    public function capacity()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'capacity_id');
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.passage_info", $input);

        return $id;
    }


}
