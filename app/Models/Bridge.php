<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Facades\Transaction;
use App\Models\ConstructionYear;
use App\Models\Task;

class Bridge extends Model
{
    //
    protected $table = 'bridge';

    public function years()
    {
        return $this->hasMany('App\Models\ConstructionYear', 'bridge_id')->orderBy('year', 'desc');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\MasterInspection', 'bridge_id')->orderBy('inspection_date', 'desc');
    }

    public function road()
    {
        return $this->belongsTo('App\Models\Passage', 'passage_id')->with('route');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District', 'district_id')->with('state');
    }

    public function asset()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'asset_id');
    }

    public function ramp()
    {
        return $this->belongsTo('App\Models\MasterLookup', 'ramp_id');
    }

    public static function insert(array $input)
    {
        $id = Transaction::saveToTable("bms.public.bridge", $input);
        return $id;
    }



    public static function fetch($ids = array())
    {
        $query = DB::table('bms.public.bridge as bridge')
            ->join('bms.public.passage as road', 'bridge.passage_id', 'road.id')
            ->join('bms.public.route as route', 'road.route_id', 'route.id')
            ->join('bms.public.district as district', 'bridge.district_id', 'district.id')
            ->join('bms.public.state as state', 'district.state_id', 'state.id')
            ->join('bms.public.master_lookup as lookup', 'bridge.asset_id', 'lookup.id')
            ->select(
                'bridge.id as id',
                'bridge.name as bridge_name',
                'bridge.structure_no as structure_no',
                'route.code as code',
                'route.name as road_name',
                'road.number as section',
                'district.name as district_name',
                'state.name as state_name',
                'lookup.name as asset',
                'bridge.remark as remark'
            );

        $queryYears = ConstructionYear::where('status', Task::APPROVED_STATUS);

        if (empty($ids)) {
            $years = $queryYears
                ->orderBy('bridge_id', 'desc')
                ->orderBy('year', 'asc')->get();
        } else {
            $years = $queryYears->whereIn('bridge_id', $ids)
                ->orderBy('bridge_id', 'desc')
                ->orderBy('year', 'asc')->get();
        }

        $arrBridges = array();
        $arrYears = array();
        $results = array();
        $bridge = (object)[];

        foreach ($years as $year) {
            array_push($arrBridges, $year->bridge_id);
            array_push($arrYears, $year);
        }

        if (isset($arrBridges)) {
            $bridges = $query->whereIn('bridge.id', $arrBridges)->get();
            if (isset($bridges)) {
                foreach ($bridges as $bridge) {
                    $v = $bridge->id;
                    $entry = array_filter($arrYears, function ($e) use ($v) {
                        return $e->bridge_id == $v;
                    });
                    if (isset($entry)) {
                        $bridge->years = $entry;
                    } else {
                        $bridge->years = array();
                    }

                    array_push($results, $bridge);
                }
            }
        }

        return $results;
    }

    public static function fetchForRating(array $condition = array())
    {
        $query = DB::table('public.bridge as bridge')
            ->join('public.passage as road', 'bridge.passage_id', 'road.id')
            ->join('public.route as route', 'road.route_id', 'route.id')
            ->join('public.district as district', 'bridge.district_id', 'district.id')
            ->join('public.state as state', 'district.state_id', 'state.id')
            ->join('public.master_lookup as lookup', 'bridge.asset_id', 'lookup.id')
            ->select(
                'bridge.id as id',
                'bridge.name as bridge_name',
                'bridge.structure_no as structure_no',
                'route.code as code',
                'route.name as road_name',
                'district.name as district_name',
                'state.name as state_name',
                'lookup.name as asset',
                DB::raw('(select im.rating from public.inspection_master im where im.bridge_id = bridge.id order by im.created_at desc limit 1) as rate')
            );

        if (isset($condition)) {
            $query = $query->where($condition);
        }

        $queryYears = ConstructionYear::where('status', Task::APPROVED_STATUS)->orderBy('bridge_id', 'desc')
            ->orderBy('year', 'desc')->get();

            $arrBridges = array();
            $arrYears = array();
            $results = array();
    
            foreach ($queryYears as $year) {
                array_push($arrBridges, $year->bridge_id);
                array_push($arrYears, $year);
            }
    
            if (isset($arrBridges)) {
                $bridges = $query->whereIn('bridge.id', $arrBridges)->get();
                if (isset($bridges)) {
                    foreach ($bridges as $bridge) {
                        $v = $bridge->id;
                        $entry = array_filter($arrYears, function ($e) use ($v) {
                            return $e->bridge_id == $v;
                        });
                        if (isset($entry)) {
                            $bridge->years = $entry;
                        } else {
                            $bridge->years = array();
                        }
    
                        array_push($results, $bridge);
                    }
                }
            }
    
            return $results;
    }

    public static function fetchByStatus(array $status, array $condition = array())
    {
        $query = DB::table('public.bridge as bridge')
            ->join('public.passage as road', 'bridge.passage_id', 'road.id')
            ->join('public.route as route', 'road.route_id', 'route.id')
            ->join('public.district as district', 'bridge.district_id', 'district.id')
            ->join('public.state as state', 'district.state_id', 'state.id')
            ->join('public.master_lookup as lookup', 'bridge.asset_id', 'lookup.id')
            ->select(
                'bridge.id as id',
                'bridge.name as bridge_name',
                'bridge.structure_no as structure_no',
                'route.code as code',
                'route.name as road_name',
                'road.number as section',
                'district.name as district_name',
                'state.name as state_name',
                'lookup.name as asset',
                'bridge.remark as remark'
            );

        if (isset($condition)) {
            $query = $query->where($condition);
        }

        $years = ConstructionYear::whereIn('status', $status)->orderBy('bridge_id', 'desc')->orderBy('year', 'asc')->get();
        $arrBridges = array();
        $arrYears = array();
        $results = array();

        foreach ($years as $year) {
            array_push($arrBridges, $year->bridge_id);
            array_push($arrYears, $year);
        }

        if (isset($arrBridges)) {
            $bridges = $query->whereIn('bridge.id', $arrBridges)->get();
            if (isset($bridges)) {
                foreach ($bridges as $bridge) {
                    $v = $bridge->id;
                    $entry = array_filter($arrYears, function ($e) use ($v) {
                        return $e->bridge_id == $v;
                    });
                    if (isset($entry)) {
                        $bridge->years = $entry;
                    } else {
                        $bridge->years = array();
                    }

                    array_push($results, $bridge);
                }
            }
        }

        return $results;
    }

    public static function fetchTask($userId, $type)
    {
        $results = DB::table('public.bridge as bridge')
            ->join('public.passage as road', 'bridge.passage_id', 'road.id')
            ->join('public.route as route', 'road.route_id', 'route.id')
            ->join('public.district as district', 'bridge.district_id', 'district.id')
            ->join('public.state as state', 'district.state_id', 'state.id')
            ->join('public.master_lookup as lookup', 'bridge.asset_id', 'lookup.id')
            ->join('public.task as task', 'bridge.id', 'task.bridge_id')
            ->join('public.construction_year as cyear', 'cyear.id', 'task.year_id')
            ->select(
                'bridge.id as id',
                'bridge.name as bridge_name',
                'bridge.structure_no as structure_no',
                'route.code as code',
                'route.name as road_name',
                'road.number as section',
                'district.name as district_name',
                'state.name as state_name',
                'lookup.name as asset',
                'task.id as task_id',
                'cyear.id as year_id',
                'cyear.year as year',
                'task.current_status as status'
            )->where([
                ["task.current_status", "<>", Task::APPROVED_STATUS],
                ["user_id", "=", $userId],
                ["process", '=', $type]
            ])->get();

        return $results;
    }
}
