<?php

namespace App\Custom;

use Illuminate\Support\Facades\DB;

class TransactionHelper
{
    public function save(string $table, array $cols, array $values)
    {
        $id = DB::table($table)->insertGetId($this->prepareData($cols,$values));

        return $id;
    }

    public function simpleSave(string $table, array $cols, array $values)
    {
        DB::table($table)->insert($this->prepareData($cols,$values));

    }

    public function saveToTable(string $table, array $datas)
    {
        $id = DB::table($table)->insertGetId($datas);

        return $id;
    }

    public function remove(string $table, array $condition)
    {
        DB::table($table)->where($condition)->delete();
    }

    public function update(string $table, $id, array $data)
    {
        DB::table($table)->where('id','=',$id)->update($data);
    }

    private function prepareData(array $cols, array $values)
    {
        $arr = array();

        for($i = 0; $i < count($cols); $i++){
            $arr[$cols[$i]] = $values[$i];
        }

        return $arr;
    }
 
}