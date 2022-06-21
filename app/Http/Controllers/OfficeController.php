<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;
use Yajra\DataTables\Facades\DataTables;
use App\Facades\Lookup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class OfficeController extends Controller
{
    //
    public function load(Request $request) {
        if ($request->ajax()) {
            $offices = Office::all();
            return Datatables::of($offices)->addColumn('action', function ($office) {

                $btn = '<a href="'.route('office.edit').'?id='.$office->id.'" class="btn btn-sm btn-info"><span class="ml-1 fa fa-edit"></span>Edit</a>';

                return $btn;
            })->addColumn('state',function ($office) {
                if($office->state) {
                    $state = $office->state;
                } else {
                    $state = NULL;
                }
                return isset($state) ? $state->name : '';
            })->addColumn('district',function ($office) {
                if($office->district) {
                    $district = $office->district;
                } else {
                    $district = NULL;
                }
                return isset($district) ? $district->name : '';
            })
            ->rawColumns(['action','state','district'])
            ->make(true);
        }
    }

    public function show() {
        $states = Lookup::loadLookup('public.state');

        return view('setting.office-form',compact('states'));
    }

    public function view(Request $request) {
        $states = Lookup::loadLookup('public.state');
        $office = Office::find($request->id);

        return view('setting.office-form',compact('states','office'));
    }

    public function save(Request $request) {
        $data = $request->all();
        $officeData = [
            'name' => $data['name'],
            'state_id' => $data['state'],
            'hq' => isset($data['hq']) ? "1" : 0,
            'district_id' => isset($data['district']) ? $data['district'] : NULL,
            'created_by' => Auth::user()->ic_no,
            'created_at' => Date::now()
        ];

        Office::insert($officeData);

        return redirect(route('office.list'))->with('message','This office been saved');
    }

    public function update(Request $request) {
        $data = $request->all();
        $officeData = [
            'name' => $data['name'],
            'state_id' => $data['state'],
            'hq' => isset($data['hq']) ? "1" : 0,
            'district_id' => isset($data['district']) ? $data['district'] : NULL,
            'updated_by' => Auth::user()->ic_no,
            'updated_at' => Date::now()
        ];

        Office::where('id',$data['id'])->update($officeData);

        return redirect(route('office.list'))->with('message','This office been updated');
    }
}
