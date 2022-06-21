<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\Transaction;

class InspectionPhoto extends Model
{
    //
    protected $table = "inspection_photos";

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("public.inspection_photos", $input);

        return $id;
    }
}
