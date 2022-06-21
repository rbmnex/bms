<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterLookup;
use App\Models\Route;

class RouteController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {
        $lookup = MasterLookup::loadLookup(1);

        return view("inventory.route-form", compact('lookup'));
    }

    public function show(Request $request)
    {
        $lookup = MasterLookup::loadLookup(1);
        $route = Route::find($request->routeid);

        return view("inventory.route-form", compact('lookup','route'));
    }

    public function list(Request $request)
    {
        $list = Route::all();

        return view("inventory.route-list", compact('list'));
    }

    public function saveRoute(Request $request)
    {
        $data = $request->all();

        $routeData = [
            'code' => $data['route_code'], 
            'name' => $data['route_name'], 
            'stretches_id' => $data['route_type'], 
            'created_by' => Auth::user()->ic_no, 
            'created_at' => Date::now()
        ];

        $id = Route::insert($routeData);

        return redirect(route('route.list'))->with('message','Details successful been saved');
    }

    public function updateRoute(Request $request)
    {
        $data = $request->all();

        $routeData = [
            'code' => $data['route_code'], 
            'name' => $data['route_name'], 
            'stretches_id' => $data['route_type'], 
            'updated_by' => Auth::user()->ic_no, 
            'updated_at' => Date::now()
        ];

        Route::where('id','=',$data['routeid'])->update($routeData);

        return redirect(route('route.list'))->with('message','Details successful been updated');
    }

    public function searchRoute(Request $request)
    {
        $data = $request->all();
        $colArr = array();

        if (!empty($data['code'])) {
            array_push($colArr, ['bms.public.route.code', 'like', '%' . $data['code'] . '%']);
        } elseif (!empty($data['name'])) {
            array_push($colArr, ['bms.public.route.name', 'like', '%' . $data['name'] . '%']);
        } else {
        }

        $result = Route::search($colArr);

        return $result;
    }
}
