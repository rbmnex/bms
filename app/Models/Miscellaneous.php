<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\Transaction;

class Miscellaneous extends Model
{
    //
    protected $table = "miscellaneous";

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.miscellaneous", $input);

        return $id;
    }
}
