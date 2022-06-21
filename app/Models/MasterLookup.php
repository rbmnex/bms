<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Facades\Transaction;

class MasterLookup extends Model
{
    //
    protected $table = "master_lookup";

    public function category() 
    {
        return $this->belongsTo('App\Models\MasterCategory','category_id');
    }

    /**
     * Load lookup from table master_lookup 
     * by reference id or name by join with table master category
     * 
     * load (string|int $category)
     * 
     * @return array
     */
    public static function loadLookup($category)
    {
        if (is_int($category)) {
            $lookup = DB::table('bms.public.master_lookup')->select('bms.public.master_lookup.id', 'bms.public.master_lookup.name')
                ->where([
                    ['bms.public.master_lookup.enabled', '=', '1'],
                    ['bms.public.master_lookup.category_id', '=', $category]
                ])->get();

            return $lookup;
        } elseif (is_string($category)) {
            $lookup = DB::table('bms.public.master_lookup')->select('bms.public.master_lookup.id', 'bms.public.master_lookup.name')
                ->join('bms.public.master_category', 'bms.public.master_lookup.category_id', '=', 'bms.public.master_category.id')
                ->where([
                    ['bms.public.master_lookup.enabled', '=', '1'],
                    ['bms.public.master_category.name', '=', $category]
                ])->get();

            return $lookup;
        } else {
            return [];
        }
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.master_lookup", $input);

        return $id;
    }
}
