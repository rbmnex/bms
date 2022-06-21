<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function load_total_bridge(Request $request) {
        $records = DB::table('public.bridge as bridge')->select('state.name as state_name', DB::raw('count(bridge.id) as bridge_count'))
                ->join('public.district as district', 'bridge.district_id', 'district.id')
                ->join('public.state as state', 'district.state_id', 'state.id')
            //    ->join('public.construction_year as years', 'bridge.id', 'years.bridge_id')
                ->whereIn('bridge.id',function($query){
                    $query->selectRaw('distinct(bridge_id) as bridge_id')
                        ->from('public.construction_year')
                        ->where('status','APPROVED');
                })
                ->groupBy('state_name')
                ->get();

        $total = [];
        $state = [];

        foreach($records as $rec) {
            $total[] = $rec->bridge_count;
            $state[] = $rec->state_name;
        }

        $data = [
            'total' => $total,
            'state' => $state
        ];

        return response()->json($data);
    }

    public function load_total_material(Request $request) {
        $ids = DB::table('public.construction_year as cy')
                    ->select('cy.id', 'cy.bridge_id', DB::raw('max(cy."year") as years'))
                    ->where('cy.status','APPROVED')
                    ->groupBy('cy.id', 'cy.bridge_id')
                    ->pluck('cy.id')->all();

         $records = DB::table('public.superstructure as superstructure')
             ->select('master_lookup.name as material_name', DB::raw('count(superstructure.material_id) as total'))
             ->join('public.master_lookup as master_lookup','superstructure.material_id','master_lookup.id')
             ->whereIn('superstructure.year_id', $ids)
             ->where('superstructure.type', '1')
             ->groupBy('master_lookup.name')
             ->get();

        $data = array();
        foreach($records as $rec) {
            $col = [
                'x' => $rec->material_name,
                'y' => $rec->total
            ];
            $data[] = $col;
        }

        return response()->json($data);
    }

    public function load_total_system(Request $request) {
        $ids = DB::table('public.construction_year as cy')
                    ->select('cy.id', 'cy.bridge_id', DB::raw('max(cy."year") as years'))
                    ->where('cy.status','APPROVED')
                    ->groupBy('cy.id', 'cy.bridge_id')
                    ->pluck('cy.id')->all();

        $records = DB::table('public.superstructure as superstructure')
             ->select('master_lookup.name as system_name', DB::raw('count(superstructure.system_id) as total'))
             ->join('public.master_lookup as master_lookup','superstructure.system_id','master_lookup.id')
             ->whereIn('superstructure.year_id', $ids)
             ->where('superstructure.type', '1')
             ->groupBy('master_lookup.name')
             ->get();

        $data = array();
        foreach($records as $rec) {
            $col = [
                'x' => $rec->system_name,
                'y' => $rec->total
            ];
            $data[] = $col;
        }

        return response()->json($data);
    }

    public function load_total_deck(Request $request) {
        $ids = DB::table('public.construction_year as cy')
                    ->select('cy.id', 'cy.bridge_id', DB::raw('max(cy."year") as years'))
                    ->where('cy.status','APPROVED')
                    ->groupBy('cy.id', 'cy.bridge_id')
                    ->pluck('cy.id')->all();

        $records = DB::table('public.superstructure as superstructure')
             ->select('master_lookup.name as deck_name', DB::raw('count(superstructure.deck_id) as total'))
             ->join('public.master_lookup as master_lookup','superstructure.deck_id','master_lookup.id')
             ->whereIn('superstructure.year_id', $ids)
             ->where('superstructure.type', '1')
             ->groupBy('master_lookup.name')
             ->get();

        $data = array();
        foreach($records as $rec) {
            $col = [
                'x' => $rec->deck_name,
                'y' => $rec->total
            ];
            $data[] = $col;
        }

        return response()->json($data);
    }

    public function load_assets_breakdown(Request $request) {
        $ids = DB::table('public.construction_year as cy')
                    ->select('cy.id', 'cy.bridge_id', DB::raw('max(cy."year") as years'))
                    ->where('cy.status','APPROVED')
                    ->groupBy('cy.id', 'cy.bridge_id')
                    ->pluck('cy.id')->all();


        $columns = DB::table('bridge as b')
                       ->join('master_lookup as ml','b.asset_id','ml.id')
                       ->select(DB::raw('distinct ml."name" as asset_name'),'b.asset_id')
                       ->get();

        $results = DB::table('public.bridge as b')
                        ->join('public.district as d','b.district_id','d.id')
                        ->join('public.state as s','d.state_id','s.id')
                        ->join('public.master_lookup as ml','b.asset_id','ml.id')
                        ->select(DB::raw('distinct s.id as state_id'),'s."name" as state_name','ml."name" as asset_name',DB::raw('count(b.id) as bridge_count'))
                        ->whereIn('b.id',$ids)
                        ->groupBy('s.id','s."name"','ml."name"')
                        ->orderBy('s.id', 'asc')
                        ->get();



        foreach($columns as $col) {

        }

        $currentId = 0;
        $previousId = 0;
        $dataArray = array();
        $data = array();
        foreach($results as $r) {
            $currentId = $r->state_id;
            if ($previousId == 0) {
                $asset_name = $r->asset_name;
                $count = $r->bridge_count;
                $data = [
                    'state' => $r->state_name,
                    $asset_name => $r->bridge_count,
                    'count' => $count
                ];
            } else if($previousId == $currentId) {
                $total = $data['count'] + $r->bridge_count;
                $asset_name = $r->asset_name;
                $data[$asset_name] = $r->bridge_count;
                $data['count'] = $total;
            } else {
                $dataArray[] = $data;
                $asset_name = $r->asset_name;
                $count = $r->bridge_count;
                $data = [
                    'state' => $r->state_name,
                    $asset_name => $r->bridge_count,
                    'count' => $count
                ];
            }
            $previousId = $r->state_id;
        }

        $m = [
            'columns' => $columns,
            'data' =>$dataArray
        ];

        return view('home',$m);
    }
}
