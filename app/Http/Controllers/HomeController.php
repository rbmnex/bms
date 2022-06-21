<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $ids = DB::table('public.construction_year as cy')
                    ->select('cy.id', 'cy.bridge_id', DB::raw('max(cy."year") as years'))
                    ->where('cy.status','APPROVED')
                    ->groupBy('cy.id', 'cy.bridge_id')
                    ->pluck('cy.id')->all();


        $columns = DB::table('bridge as b')
                       ->join('master_lookup as ml','b.asset_id','ml.id')
                       ->select(DB::raw('distinct ml.name as asset_name'),'b.asset_id')
                       ->get();

        $results = DB::table('public.bridge as b')
                        ->join('public.district as d','b.district_id','d.id')
                        ->join('public.state as s','d.state_id','s.id')
                        ->join('public.master_lookup as ml','b.asset_id','ml.id')
                        ->select(DB::raw('distinct s.id as state_id'),'s.name as state_name','ml.name as asset_name',DB::raw('count(b.id) as bridge_count'))
                        ->whereIn('b.id',$ids)
                        ->groupBy('s.id','s.name','ml.name')
                        ->orderBy('s.id', 'asc')
                        ->get();

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
                    'id' => $r->state_id,
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
                    'id' => $r->state_id,
                    'state' => $r->state_name,
                    $asset_name => $r->bridge_count,
                    'count' => $count
                ];
            }
            $previousId = $r->state_id;
        }

        $dataArray[] = $data;

        $allBridge = collect($dataArray);
        $middleEastCol = collect();
        $northCol = collect();
        $southCol = collect();
        $otherCol = collect();

        /*
            1	Johor
            2	Kedah
            3	Kelantan
            4	Melaka
            5	Negeri Sembilan
            6	Pahang
            7	Pulau Pinang
            8	Perak
            9	Perlis
            10	Selangor
            11	Terengganu
            12	Sabah
            13	Sarawak
            14	WP Kuala Lumpur
            15	WP Labuan
            16	WP Putrajaya
            17	Lain-lain
        */

        foreach($dataArray as $b) {
            if ($b['id'] == 10 || $b['id'] == 6 || $b['id'] == 11 || $b['id'] == 3) {
                $middleEastCol->push($b);
            } else if ($b['id'] == 9 || $b['id'] == 2 || $b['id'] == 7 || $b['id'] == 8) {
                $northCol->push($b);
            } else if ($b['id'] == 5 || $b['id'] == 4 || $b['id'] == 1) {
                $southCol->push($b);
            } else {
                $otherCol->push($b);
            }
        }


        $m = [
            'columns' => $columns,
            'data' =>$allBridge,
            'middleEast' => $middleEastCol,
            'north' => $northCol,
            'south' => $southCol,
            'other' => $otherCol
        ];
        return view('home',$m);
    }
}
