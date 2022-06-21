<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\Transaction;

class Element extends Model
{
    //
    protected $table = "elements";

    public function parapet()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'parapet_id');
    }

    public function wearing()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'wearing_surface_id');
    }

    public function expansion()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'expansion_joint_id');
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.elements", $input);

        return $id;
    }
}
