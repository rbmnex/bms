<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\Transaction;

class Geometry extends Model
{
    //
    protected $table = 'geometry';

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.geometry", $input);

        return $id;
    }
}
