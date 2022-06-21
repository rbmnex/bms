<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class Position extends Model
{
    //
    protected $table = "position";

    public static function searchId(string $name) {
      $position = DB::table('public.position')->where('name',strtoupper($name))->first();
      if($position) {
        return $position->id;
      } else {
        $id = DB::table('public.position')->insertGetId(
            ['name' => strtoupper($name), 'enabled' => '1',
          'created_by' => 'Mykj', 'created_at' => Date::now()]
        );

        return $id;
      }
    }
}
