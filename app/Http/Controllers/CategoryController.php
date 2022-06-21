<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use App\Models\MasterCategory;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function loadCategory(Request $request)
    {
        if ($request->ajax()) 
        {
            $categories = MasterCategory::all();

            return Datatables::of($categories)
            ->addColumn('action', function ($category) {

                $btn = '<a href="'.route('category.edit').'?id='.$category->id.'" class="btn btn-sm btn-info"><span class="mx-1 fa fa-edit"></span>Edit</a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->editColumn('enabled', function ($category) {
                return $category->enabled == '1' ? 'Yes' : 'No';
            })
            ->make(true);
        }
    }

    public function save(Request $request)
    {
        $data = $request->all();

        $categoryData = [
            'name' => $data['name'], 
            'enabled' => isset($data['enabled']) ? $data['enabled'] : '0', 
            'description' => $data['description'],
            'created_by' => Auth::user()->ic_no,
            'created_at' => Date::now()
        ];

        $category = MasterCategory::create($categoryData);

        return redirect(route('category.list'))->with('message','Details successful been saved');
    }

    public function show(Request $request)
    {
        $category = MasterCategory::find($request->id);

        return view("setting.category-form", compact('category'));
    }

    public function update(Request $request)
    {
        $data = $request->all();

        $categoryData = [
            'name' => $data['name'], 
            'enabled' => isset($data['enabled']) ? $data['enabled'] : '0',
            'description' => $data['description'],
            'updated_by' => Auth::user()->ic_no,
            'updated_at' => Date::now()
        ];

        MasterCategory::where('id','=',$data['id'])->update($categoryData);

        return redirect(route('category.list'))->with('message','Details successful been updated');
    }
}
