<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Facades\Transaction;


class Service extends Model
{
    //
    protected $table = "services";

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.services", $input);

        return $id;
    }
}
