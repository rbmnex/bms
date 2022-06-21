<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\Lookup;
use App\Models\Office;
use App\Models\MasterLookup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Yajra\DataTables\Facades\DataTables;

class LookupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    //    $this->middleware('auth');
    }

    public function lookupDistrict(Request $request)
    {
        $data = $request->all();

        $result = Lookup::loadLookup('bms.public.district',NULL,'state_id',$data['val']);

        return $result;
    }

    public function lookupOffice(Request $request) 
    {
        $data = $request->all();

        $result = Office::lookupByState($data['val']);

        return $result;
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $results = MasterLookup::all();
    
            return Datatables::of($results)
                ->addColumn('action', function($result) {
                    $btn = '<a href="'.route('lookup.edit').'?id='.$result->id.'" class="btn btn-sm btn-info"><span class="fa fa-edit mx-1"></span>Edit</a>';
                    return $btn;
                })
                ->addColumn('category', function($result) {
                    return $result->category->name;
                })
                ->editColumn('enabled', function ($result) {
                    return $result->enabled == '1' ? 'Yes' : 'No';
                })
                ->rawColumns(['action','category'])
                ->make(true);
        }
    }

    public function view()
    {
        $categories = Lookup::loadLookup('bms.public.master_category');
        return view('setting.lookup-form',compact('categories'));
    }

    public function show(Request $request)
    {
        $categories = Lookup::loadLookup('bms.public.master_category');
        $lookup = MasterLookup::find($request->id);
        return view('setting.lookup-form',compact('categories','lookup'));
    }

    public function save(Request $request) 
    {
        $data = $request->all();

        $lookup = [
            'name' => $data['name'],
            'category_id' => $data['category'],
            'enabled' => isset($data['enabled']) ? '1' : '0',
            'description' => $data['description'],
            'created_at' => Date::now(),
            'created_by' => Auth::user()->ic_no
        ];

        MasterLookup::insert($lookup);

        return redirect(route('lookup.list'))->with('message','Details successful been saved');
    }

    public function update(Request $request) 
    {
        $data = $request->all();

        $lookup = [
            'name' => $data['name'],
            'category_id' => $data['category'],
            'enabled' => isset($data['enabled']) ? '1' : '0',
            'description' => $data['description'],
            'updated_at' => Date::now(),
            'updated_by' => Auth::user()->ic_no
        ];

        MasterLookup::where('id','=',$data['id'])->update($lookup);

        return redirect(route('lookup.list'))->with('message','Details successful been updated');
    }
}
