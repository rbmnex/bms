<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterCategory extends Model
{
    //
    protected $table = "bms.public.master_category";
    protected $fillable = ["name","enabled","decription","updated_by","created_by"];
}
