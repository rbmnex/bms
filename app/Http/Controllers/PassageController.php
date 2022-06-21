<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Auth;
use App\Models\Passage;
use App\Models\Route;
use App\Models\MasterLookup;
use Yajra\DataTables\Facades\DataTables;
use App\Facades\Lookup;

class PassageController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $lookup = MasterLookup::loadLookup(2);
        $routes = Route::all();
        $states = Lookup::loadLookup('public.state');

        return view("inventory.passage-form", compact('lookup', 'routes', 'states'));
    }

    public function show(Request $request) 
    {
        $lookup = MasterLookup::loadLookup(2);
        $routes = Route::all();
        $road = Passage::find($request->id);
        $states = Lookup::loadLookup('public.state');

        return view("inventory.passage-form", compact('lookup', 'routes', 'road','states'));
    }

    public function saveRoad(Request $request)
    {
        $data = $request->all();

        $passageData = [
            "type_id" => $data['passage_type'],
            "primary" => empty($data['passage_primary']) ? "0" : "1",
            "ou" => $data['passage_ou'],
            "route_id" => $data['route_id'],
            "kilometer" => $data['km'],
            "meter" => $data['m'],
            "number" => $data['passage_number'],
            'district_id' => isset($data['district']) ? $data['district'] : NULL,
            'created_by' => Auth::user()->ic_no,
            'created_at' =>  Date::now()
        ];

        $id = Passage::insert($passageData);

        return redirect(route("road.list"))->with('message','Details successful been saved');
    }

    public function updateRoad(Request $request)
    {
        $data = $request->all();

        $passageData = [
            "type_id" => $data['passage_type'],
            "primary" => empty($data['passage_primary']) ? "0" : "1",
            "ou" => $data['passage_ou'],
            "route_id" => $data['route_id'],
            "kilometer" => $data['km'],
            "meter" => $data['m'],
            "number" => $data['passage_number'],
            'district_id' => isset($data['district']) ? $data['district'] : NULL,
            'updated_by' => Auth::user()->ic_no,
            'updated_at' =>  Date::now()
        ];

        Passage::where('id','=',$data['id'])->update($passageData);

        return redirect(route("road.list"))->with('message','Details successful been updated');
    }

    public function searchRoad(Request $request)
    {
        $data = $request->all();
        $colArr = array();

        if (!empty($data['code'])) {
            array_push($colArr, ['bms.public.route.code', 'like', '%' . $data['code'] . '%']);
        } elseif (!empty($data['name'])) {
            array_push($colArr, ['bms.public.route.name', 'like', '%' . $data['name'] . '%']);
        } else {
        }

        $result = Passage::search($colArr);

        return $result;
    }

    public function loadRoad(Request $request)
    {
        if ($request->ajax()) {

            $state_id = isset(Auth::user()->office) ? Auth::user()->office->state_id : 0;

            if(Auth::user()->hasRole(["Administrator"])) {
                $roads= Passage::search();
            } else {
                $condition = [
                    ["public.state.id", '=', $state_id]
                ];
                $roads= Passage::search($condition);
            }

            return Datatables::of($roads)->addColumn('action', function ($road) {

                    $btn = '<a href="'.route('road.edit').'?id='.$road->id.'" class="btn btn-sm btn-info"><span class="ml-1 fa fa-edit"></span>Edit</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
