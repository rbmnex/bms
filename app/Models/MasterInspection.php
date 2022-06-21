<?php

namespace App\Models;

use App\Facades\Transaction;
use Illuminate\Database\Eloquent\Model;

class MasterInspection extends Model
{
    //
    protected $table = "public.inspection_master";

    public function bridge() 
    {
        return $this->belongsTo('App\Models\Bridge','bridge_id');
    }

    public function components()
    {
        return $this->hasMany('App\Models\ComponentInspection', 'inspection_id');
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.inspection_master", $input);

        return $id;
    }

    public static function loadAll($bridgeId)
    {
        $results = self::where([
            ['bridge_id', '=', $bridgeId], 
            ['status', '=', Task::APPROVED_STATUS]])->get();
        if (isset($results)) {
            $ids = array();
            foreach ($results as $result) {
                array_push($ids, $result->id);
            }
            $children = ComponentInspection::whereIn('inspection_id', $ids)->where('bridge_id', $bridgeId)->get();
        //    $attachs = InspectionPhoto::whereIn('inspection_id', $ids)->all();
            $members = array();
        //    $photos = array();
            foreach ($children as $child) {
                array_push($members, $child);
            }
        /*    
            foreach ($attachs as $attach) {
                array_push($photos,$attach);
            }
        */
            foreach ($results as $result) {
                $v = $result->id;
                $entries = array_filter($members, function ($e) use ($v) {
                    return $e->inspection_id == $v;
                });

                if (isset($entries)) {
                /*    
                    foreach($entries as $item) {
                        $x = $item->id;
                        $list =  array_filter($photos, function ($s) use ($x) {
                            return $s->inspection_component_id == $x;
                        });
                        if(isset($list)) {
                            $item['photos'] = $list;
                        } else {
                            $item['photos'] = array();
                        }
                    }
                */                    
                    $result['members'] = $entries;
                } else {
                    $result['members'] = array();
                }
            }
        }

        return $results;
    }
}
