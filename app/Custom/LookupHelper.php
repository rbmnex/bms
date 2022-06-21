<?php

namespace App\Custom;

use Illuminate\Support\Facades\DB;

class LookupHelper
{
    /**
     * Load lookup from define table from $table
     * by reference id from $refname or name by join with define $refTable
     * 
     * loadFromTable(string $table, string $refName = NULL , string $refTable = NULL, $refVal = NULL)
     * 
     * @return array
     */
    public function loadLookup(
        string $table,
        string $colName = NULL,
        string $refName = NULL,
        $refVal = NULL,
        string $refTable = NULL
    ) 
    {
        $name = empty($colName) ? ".name" : ".".$colName;
        if (!empty($refName)) {
            if (!empty($refVal)) {
                if (is_numeric($refVal)) {
                    $lookup = DB::table($table)->select($table . '.id', $table . $name)
                        ->where([
                            [$table . '.enabled', '=', '1'],
                            [$table . '.' . $refName, '=', $refVal]
                        ])->get();

                    return $lookup;
                } elseif (is_string($refVal)) {
                    if (!empty($refTable)) {
                        $lookup = DB::table($table)->select($table . '.id', $table . $name)
                            ->join($refTable, $table . '.' . $refName, '=', $refTable . '.id')
                            ->where([
                                [$table . '.enabled', '=', '1'],
                                [$refTable . $name, '=', $refVal]
                            ])->get();

                        return $lookup;
                    } else {
                        return [];
                    }
                } else {
                    return [];
                }
            } else {
                return [];
            }
        } else {
            $lookup = DB::table($table)->select($table . '.id', $table . $name)
                ->where($table . '.enabled', '=', '1')->get();

            return $lookup;
        }
    }
}
