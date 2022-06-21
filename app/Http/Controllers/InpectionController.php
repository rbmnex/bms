<?php

namespace App\Http\Controllers;

use Exception;
use App\Mail\TaskNotice;
use App\Models\Bridge;
use App\Models\ComponentInspection;
use App\Models\MasterInspection;
use App\Models\Task;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ConstructionYear;
use App\Models\District;
use App\Models\Geometry;
use App\Models\Passage;
use App\Models\MasterLookup;
use App\Models\Substructure;
use App\Models\Superstructure;
use App\Models\InspectionPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use App\Custom\CommonHelper;

class InpectionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function display(Request $request)
    {
        $route = "";
        $path = "";
        if ($request->action == 'list') {
            $route = route('inspect.list');
            $path = "inspection.inspect-list";
        } elseif ($request->action == 'task') {
            $route = route('inspect.task');
            $path = 'inspection.inspect-task';
        } elseif ($request->action == 'inbox') {
            $route = route('inspect.inbox');
            $path = 'inspection.inspect-inbox';
        }
        return view($path, compact('route'));
    }

    public function show(Request $request)
    {
        $bridge = Bridge::find($request->id);
        $year = ConstructionYear::where([
            ['bridge_id', '=', $request->id],
            ['status', '=', Task::APPROVED_STATUS]
        ])->orderBy('year', 'desc')->first();
        $district = District::find($bridge->district_id);
        $state = $district->state;
        $passage = Passage::find($bridge->passage_id);
        $route = $passage->route;
        $geometry = Geometry::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id]])->first();
        $super = Superstructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['type', '=', 1]])->first();
        $abutment = Substructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['structure_type', '=', Substructure::ABUTMENT]])->first();
        $pier = Substructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['structure_type', '=', Substructure::PIER]])->first();
        $members = MasterLookup::loadLookup('Bridge Component');
        $damages = MasterLookup::loadLookup(18);
        $arr = array();
        foreach ($members as $item) {
            array_push($arr, $item->id);
        }

        $users = [];
        if (Auth::user()->hasRole("Administrator")) {
            $users = User::search([], ["Administrator", "Certifier"]);
        } else {
            $users = User::search(array(["office.name", '=', Auth::user()->office->name]), ["Certifier"]);
        }

        return view('inspection.inspect-form', compact(
            'year',
            'bridge',
            'district',
            'state',
            'route',
            'geometry',
            'members',
            'abutment',
            'super',
            'pier',
            'arr',
            'users',
            'damages'
        ));
    }

    public function open(Request $request) {
        $inspect = MasterInspection::where('id','=',$request->id)->get()->first();

        $members = ComponentInspection::where('inspection_id', '=', $request->id)
        ->with('component')->with('damage')->with('photos')->get();
        $damages = MasterLookup::loadLookup(18);
        $year = ConstructionYear::find($inspect->year_id);
        $bridge = $year->bridge;
        $district = District::find($bridge->district_id);
        $state = $district->state;
        $passage = Passage::find($bridge->passage_id);
        $route = $passage->route;
        $geometry = Geometry::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id]])->first();
        $super = Superstructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['type', '=', 1]])->first();
        $abutment = Substructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['structure_type', '=', Substructure::ABUTMENT]])->first();
        $pier = Substructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['structure_type', '=', Substructure::PIER]])->first();

        $users = [];
        if (Auth::user()->hasRole("Administrator")) {
            $users = User::search([], ["Administrator", "Certifier"]);
        } else {
            $users = User::search(array(["office.name", '=', Auth::user()->office->name]), ["Certifier"]);
        }

        return view('inspection.inspect-edit', compact(
            'inspect',
            'members',
            'year',
            'bridge',
            'district',
            'state',
            'passage',
            'route',
            'geometry',
            'super',
            'abutment',
            'pier',
            'users',
            'damages'
        ));

    }

    public function view(Request $request)
    {
        $task = Task::find($request->id);
        $inspect = MasterInspection::find($task->identifier);
        $members = ComponentInspection::where('inspection_id', '=', $inspect->id)->get();
        $year = ConstructionYear::find($inspect->year_id);
        $bridge = $year->bridge;
        $district = District::find($bridge->district_id);
        $state = $district->state;
        $passage = Passage::find($bridge->passage_id);
        $route = $passage->route;
        $geometry = Geometry::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id]])->first();
        $super = Superstructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['type', '=', 1]])->first();
        $abutment = Substructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['structure_type', '=', Substructure::ABUTMENT]])->first();
        $pier = Substructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['structure_type', '=', Substructure::PIER]])->first();

        $users = [];
        if (Auth::user()->hasRole("Administrator")) {
            $users = User::search([], ["Administrator", "Certifier"]);
        } else {
            $users = User::search(array(["office.name", '=', Auth::user()->office->name]), ["Certifier"]);
        }

        return view('inspection.inspect-view', compact(
            'inspect',
            'members',
            'year',
            'bridge',
            'district',
            'state',
            'passage',
            'route',
            'geometry',
            'super',
            'abutment',
            'pier',
            'task',
            'users'
        ));
    }

    public function reveal(Request $request)
    {
        $bridge = Bridge::find($request->id);
        $year = ConstructionYear::where([
            ['bridge_id', '=', $request->id],
            ['status', '=', Task::APPROVED_STATUS]
        ])->orderBy('year', 'desc')->first();
        $district = District::find($bridge->district_id);
        $state = $district->state;
        $passage = Passage::find($bridge->passage_id);
        $route = $passage->route;
        $geometry = Geometry::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id]])->first();
        $super = Superstructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['type', '=', 1]])->first();
        $abutment = Substructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['structure_type', '=', Substructure::ABUTMENT]])->first();
        $pier = Substructure::where([['year_id', '=', $year->id], ['bridge_id', '=', $bridge->id], ['structure_type', '=', Substructure::PIER]])->first();
        $inpects = MasterInspection::loadAll($request->id);

        $users = [];
        if (Auth::user()->hasRole("Administrator")) {
            $users = User::search([], ["Administrator", "Certifier"]);
        } else {
            $users = User::search(array(["office.name", '=', Auth::user()->office->name]), ["Certifier"]);
        }

        return view('inspection.inspect-result', compact(
            'year',
            'bridge',
            'district',
            'state',
            'passage',
            'route',
            'geometry',
            'super',
            'abutment',
            'pier',
            'inpects',
            'users'
        ));
    }

    public function hold(Request $request) {
        $data = $request->all();

        $masterData = [
            'bridge_id' => $data['bridgeId'],
            'year_id' => $data['yearId'],
            'inspection_date' => $data['inspectionDate'],
            'next_year' => $data['nextYear'],
            'remark' => $data['mainRemark'],
            'status' => Task::ONHOLD_STATUS,
            'accident_record' => $data['accident_record'],
            'flood_level' => $data['flood_level'],
            'name' => $data['inspector_name'],
            'user_id' => Auth::user()->id,
            'created_at' => Date::now(),
            'created_by' => Auth::user()->ic_no
        ];

        $masterId = MasterInspection::insert($masterData);
        $comps = json_decode($data['memberIds'], true);
        $sum = 0;
        $size = 0;

        foreach ($comps as $comp) {
            $rating = $data['rating-' . $comp];
            $compData = [
                'component_id' => $comp,
                'condition_rating' => $rating,
                'remark' => $data['remark-' . $comp],
                'bridge_id' => $data['bridgeId'],
                'inspection_id' => $masterId,
                'damage_id' => isset($data['damage-'.$comp]) ? $data['damage-'.$comp] : NULL,
                'created_at' => Date::now(),
                'created_by' => Auth::user()->ic_no
            ];

            if(isset($rating)) {
                $sum = $sum + $rating;
                $size = $size + 1;
            }

            $id = ComponentInspection::insert($compData);

            if ($request->file('photo-'.$comp)) {

                $filename = CommonHelper::uploadImage($request->file('photo-'.$comp), public_path() . '/storage/inspection');
            }


            if(isset($filename)) {
                $photoData = [
                    'inspection_component_id' => $id,
                    'path' => $filename,
                    'inspection_id' => $masterId,
                    'created_at' => Date::now(),
                    'created_by' => Auth::user()->ic_no
                ];

                InspectionPhoto::insert($photoData);
            }

            $filename = NULL;
        }

        if($size>0) {
            MasterInspection::where('id',$masterId)->update(['rating' => strval($sum/$size)]);
        }

        return redirect(route('inspect.view') . '?action=list')->with('message', 'This bridge rating successful been save');
    }

    public function update(Request $request) {
        $data = $request->all();

        $sum = 0;
        $size = 0;

        $master = MasterInspection::find($data['inspect_id']);
        $master->loadMissing('components');
        $components = $master->components;
        $masterId = $master->id;

        foreach($components as $component) {
            $rating = $data['rating-' . $component->id];

            if(isset($rating)) {
                $sum = $sum + $rating;
                $size = $size + 1;
            }

            $compData = [
                'condition_rating' => $rating,
                'remark' => $data['remark-' . $component->id],
                'damage_id' => isset($data['damage-'.$component->id]) ? $data['damage-'.$component->id] : NULL,
                'updated_at' => Date::now(),
                'updated_by' => Auth::user()->ic_no
            ];

            ComponentInspection::where('id',$component->id)->update($compData);

            if ($request->file('photo-'.$component->id)) {
                $cFilename = $request->file('photo-'.$component->id)->getClientOriginalName() . '.' . $request->file('photo-'.$component->id)->getClientOriginalExtension();
            //    echo 'photo-'.$component->id;
            //    echo 'new filename : '.$cFilename;
                if(isset($data['photo_path-'.$component->id])){
                    if ($data['photo_path-'.$component->id] != $cFilename) {
                        $myFile = public_path() . '/storage/inspection/' . $data['photo_path-'.$component->id];
                      File::delete($myFile);
                        $filename = CommonHelper::uploadImage($request->file('photo-'.$component->id), public_path() . '/storage/inspection');

                    echo 'saved filename : '.$filename;
                   //     $component->loadMissing('photos');
                   //   $photo = $component->photos->first();
                        if(isset($filename)) {
                            $photoData = [
                                'path' => $filename,
                                'updated_at' => Date::now(),
                                'updated_by' => Auth::user()->ic_no
                            ];

                            InspectionPhoto::where([['inspection_component_id','=',$component->id],['inspection_id','=',$masterId]])->update($photoData);
                        }
                    }
                } else {
                    $filename = CommonHelper::uploadImage($request->file('photo-'.$component->id), public_path() . '/storage/inspection');
               //     echo 'saved filename : '. $filename;
                    if(isset($filename)) {
                        $photoData = [
                            'inspection_component_id' => $component->id,
                            'path' => $filename,
                            'inspection_id' => $masterId,
                            'created_at' => Date::now(),
                            'created_by' => Auth::user()->ic_no
                        ];

                        InspectionPhoto::insert($photoData);
                    }
                }
            }
        }

        $masterData = [
            'user_id' => Auth::user()->id,
            'inspection_date' => $data['inspectionDate'],
            'next_year' => $data['nextYear'],
            'remark' => $data['mainRemark'],
            'accident_record' => $data['accident_record'],
            'flood_level' => $data['flood_level'],
            'name' => $data['inspector_name'],
            'rating' => strval($sum/$size),
            'updated_at' => Date::now(),
            'updated_by' => Auth::user()->ic_no
        ];

        MasterInspection::where('id',$masterId)->update($masterData);

        return redirect(route('inspect.view') . '?action=inbox')->with('message', 'This bridge rating successful been updated and save');
    }

    public function submit(Request $request) {
        $data = $request->all();

        $sum = 0;
        $size = 0;

        $master = MasterInspection::find($data['inspect_id']);
        $master->loadMissing('components');
        $components = $master->components;
        $masterId = $master->id;

        foreach($components as $component) {
            $rating = $data['rating-' . $component->id];

            if(isset($rating)) {
                $sum = $sum + $rating;
                $size = $size + 1;
            }

            $compData = [
                'condition_rating' => $rating,
                'remark' => $data['remark-' . $component->id],
                'damage_id' => isset($data['damage-'.$component->id]) ? $data['damage-'.$component->id] : NULL,
                'updated_at' => Date::now(),
                'updated_by' => Auth::user()->ic_no
            ];

            ComponentInspection::where('id',$component->id)->update($compData);

            if ($request->file('photo-'.$component->id)) {
                $cFilename = $request->file('photo-'.$component->id)->getClientOriginalName() . '.' . $request->file('photo-'.$component->id)->getClientOriginalExtension();
                if(isset($data['photo_path-'.$component->id])){
                    if ($data['photo_path-'.$component->id] != $cFilename) {
                        $myFile = public_path() . '/storage/inspection/' . $data['photo_path-'.$component->id];
                        File::delete($myFile);
                        $filename = CommonHelper::uploadImage($request->file('photo-'.$component->id), public_path() . '/storage/inspection');
                        $component->loadMissing('photos');
                    //  $photo = $component->photos->first();
                        if(isset($filename)) {
                            $photoData = [
                                'path' => $filename,
                                'updated_at' => Date::now(),
                                'updated_by' => Auth::user()->ic_no
                            ];

                            InspectionPhoto::where([['inspection_component_id','=',$component->id],['inspection_id','=',$data['inspect_id']]])->update($photoData);
                        }
                    }
                } else {
                    $filename = CommonHelper::uploadImage($request->file('photo-'.$component->id), public_path() . '/storage/inspection');
                    if(isset($filename)) {
                        $photoData = [
                            'inspection_component_id' => $component->id,
                            'path' => $filename,
                            'inspection_id' => $masterId,
                            'created_at' => Date::now(),
                            'created_by' => Auth::user()->ic_no
                        ];

                        InspectionPhoto::insert($photoData);
                    }
                }
            }
        }

        $masterData = [
            'user_id' => Auth::user()->id,
            'inspection_date' => $data['inspectionDate'],
            'next_year' => $data['nextYear'],
            'remark' => $data['mainRemark'],
            'accident_record' => $data['accident_record'],
            'flood_level' => $data['flood_level'],
            'name' => $data['inspector_name'],
            'rating' => strval($sum/$size),
            'status' => Task::NEW_STATUS,
            'updated_at' => Date::now(),
            'updated_by' => Auth::user()->ic_no
        ];

        MasterInspection::where('id',$masterId)->update($masterData);

        Task::insert($data['bridgeId'], $data['user_id'], $data['yearId'], $masterId, Task::INSPECT_PROCESS);

        if($this->email) {
            try {
                Mail::to($data['email'])->send(new TaskNotice(Bridge::find($data['bridgeId']), User::find($data['user_id'])));
            } catch (Exception $e) {
                report($e);
            }
        }

        return redirect(route('inspect.view') . '?action=inbox')->with('message', 'This bridge rating successful been submitted and waiting for approval');
    }

    public function save(Request $request)
    {
        $data = $request->all();

        $masterData = [
            'user_id' => Auth::user()->id,
            'bridge_id' => $data['bridgeId'],
            'year_id' => $data['yearId'],
            'inspection_date' => $data['inspectionDate'],
            'next_year' => $data['nextYear'],
            'remark' => $data['mainRemark'],
            'status' => Task::NEW_STATUS,
            'accident_record' => $data['accident_record'],
            'flood_level' => $data['flood_level'],
            'name' => $data['inspector_name'],
            'created_at' => Date::now(),
            'created_by' => Auth::user()->ic_no
        ];

        $masterId = MasterInspection::insert($masterData);
        $comps = json_decode($data['memberIds'], true);
        $sum = 0;
        $size = 0;

        foreach ($comps as $comp) {
            $rating = $data['rating-' . $comp];
            $compData = [
                'component_id' => $comp,
                'condition_rating' => $rating,
                'remark' => $data['remark-' . $comp],
                'bridge_id' => $data['bridgeId'],
                'inspection_id' => $masterId,
                'damage_id' => isset($data['damage-'.$comp]) ? $data['damage-'.$comp] : NULL,
                'created_at' => Date::now(),
                'created_by' => Auth::user()->ic_no
            ];

             if(isset($rating)) {
                $sum = $sum + $rating;
                $size = $size + 1;
            }

            $id = ComponentInspection::insert($compData);

            if ($request->file('photo-'.$comp)) {

                $filename = CommonHelper::uploadImage($request->file('photo-'.$comp), public_path() . '/storage/inspection');
            }


            if(isset($filename)) {
                $photoData = [
                    'inspection_component_id' => $id,
                    'path' => $filename,
                    'inspection_id' => $masterId,
                    'created_at' => Date::now(),
                    'created_by' => Auth::user()->ic_no
                ];

                InspectionPhoto::insert($photoData);
            }

            $filename = NULL;
        }

        MasterInspection::where('id',$masterId)->update(['rating' => strval($sum/$size)]);

        Task::insert($data['bridgeId'], $data['user_id'], $data['yearId'], $masterId, Task::INSPECT_PROCESS);



        if($this->email) {
            try {
                Mail::to($data['email'])->send(new TaskNotice(Bridge::find($data['bridgeId']), User::find($data['user_id'])));
            } catch (Exception $e) {
                report($e);
            }
        }

        return redirect(route('inspect.view') . '?action=list')->with('message', 'This bridge rating successful been submitted and waiting for approval');
    }

    public function fetchUser(Request $request)
    {
        $users = [];
        if (Auth::user()->hasRole("Administrator")) {
            $users = User::search([], ["Administrator", "Certifier"]);
        } else {
            $users = User::search(array(["office.name", '=', Auth::user()->office->name]), ["Certifier"]);
        }

        return DataTables::of($users)
            ->addColumn('action', function ($user) {
                $radio = '<input class="form-check-input" type="radio" name="idUserRdo" value="' . $user->id . '">';
                return $radio;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function approve(Request $request)
    {
        $data = $request->all();
        $inspectData = [
            'status' => Task::APPROVED_STATUS,
            'updated_at' => Date::now(),
            'updated_by' => Auth::user()->ic_no
        ];
        MasterInspection::where('id', '=', $data['inspectId'])->update($inspectData);
        Task::edit($data['taskId'], Task::APPROVED_STATUS, Auth::user()->id);

        return redirect(route('inspect.view') . '?action=task')->with('message', 'This bridge rating successful been updated');;
    }

    public function delete(Request $request) {
        $data = $request->all();
        $members = ComponentInspection::where('inspection_id',$data['inspect_id'])->get();

        if($members) {
            foreach($members as $member) {
                InspectionPhoto::where([['inspection_component_id','=',$member->id],['inspection_id','=',$data['inspect_id']]])->delete();
                ComponentInspection::where('id',$member->id)->delete();
            }
        }

        MasterInspection::where('id',$data['inspect_id'])->delete();
        return redirect( route('inspect.view') . '?action=inbox')->with('message', 'This bridge rating successful been deleted');
    }

    public function list(Request $request)
    {
        /*
        if(Auth::user()->hasRole("Administrator")) {
            $condition = array();
        } else {
            $currentUser = Auth::user();
            $currentUser->loadMissing('office');
            $office = $currentUser->office;
            if(isset($office)) {
                if($office->hq == '1') {
                    $condition = [['state.id','=',$office->state_id]];
                } else {
                    $condition = [['district.id','=',$office->district_id]];
                }
            } else {
                $condition = [['state.id','=',0]];
            }
        }


        $bridges = Bridge::fetchForRating($condition);
        */
        $bridges = Bridge::select('*')
            ->with('years')
            ->with('road')
            ->with('asset')
            ->with('ramp')
            ->with('district')
            ->with('ratings')
            ->whereHas('years',function (Builder $query) {
                $query->where('status', TASK::APPROVED_STATUS);
            })
            ->where('flag', 1);

        if (Auth::user()->hasRole(["Administrator","VIP"])) {
            $bridges->with('district');
        } else {
            $currentUser = User::find(Auth::user()->id);
            $currentUser->loadMissing('office');
            $office = $currentUser->office;
            if (isset($office)) {
                if ($office->hq == '1') {
                    $bridges->whereHas('district',function (Builder $query) use($office){
                        $query->where('state_id',$office->state_id);
                    });
                } else {
                    $bridges->whereHas('district',function (Builder $query) use($office){
                        $query->where('id',$office->district_id);
                    });
                }
            } else {
                $bridges->whereHas('district',function (Builder $query) use($office){
                    $query->where('id',0);
                });
            }
        }

        return Datatables::of($bridges)
            ->addColumn('action', function ($bridge) use ($request) {
                $show = '<a href="' . route('inspect.result'). '?id=' . $bridge->id . '" class="btn btn-sm btn-info mx-1"><span class="far fa-eye mx-1"></span>View</a>';
                $btn = '<a href="' . route('inspect.form') . '?id=' . $bridge->id . '" class="btn btn-sm btn-info mx-1"><span class="far fa-chart-bar mx-1"></span>Rate</a>';
                if(Auth::user()->hasRole(["Administrator","Registrar","Inspector"]) ) {
                    return $show.$btn;
                } else {
                    return $show;
                }

            })
            ->addColumn('route', function ($bridge) {
                $bridge->road->loadMissing('route');
                return $bridge->road->route->code . ' - ' . $bridge->road->route->name;
            })
            ->addColumn('region', function ($bridge) {
                $bridge->loadMissing('district');
                if(isset($bridge->district)) {
                    $bridge->district->loadMissing('state');
                    return $bridge->district->name . ', ' . $bridge->district->state->name;

                } else {
                    return '';
                }
            })
            ->addColumn('rating', function ($bridge) {
                $bridge->loadMissing('ratings');
                if(isset($bridge->ratings)) {
                    $rate = $bridge->ratings->first();
                    return isset($rate->rating) ? round($rate->rating,1) : "Not Rated Yet";
                } else {
                    return "Not Rated Yet";
                }
            })
            ->rawColumns(['action', 'route', 'region', 'rating'])
            ->make(true);
    }

    public function task(Request $request)
    {
        $bridges = Bridge::fetchTask(Auth::user()->id, Task::INSPECT_PROCESS);

        return Datatables::of($bridges)
            ->addColumn('action', function ($bridge) use ($request){
            //    $link = $request->secure() ? secure_url(route('inspect.show')):route('inspect.show') . '?id=' . $bridge->task_id;
                $link = route('inspect.show') . '?id=' . $bridge->task_id;
                $btn = '<a href="' . $link . '" class="btn btn-sm btn-info"><span class="far fa-eye mx-1"></span>View</a>';
                return $btn;
            })
            ->addColumn('route', function ($bridge) {
                return $bridge->code . ' - ' . $bridge->road_name;
            })
            ->addColumn('region', function ($bridge) {
                return $bridge->district_name . ',' . $bridge->state_name;
            })
            ->rawColumns(['action', 'route', 'region'])
            ->make(true);
    }

    public function inbox(Request $request)
    {
        $masters = MasterInspection::where([['status','=',TASK::ONHOLD_STATUS],
        ['user_id','=',Auth::user()->id]])->with('bridge');

        return Datatables::of($masters)
            ->addColumn('action',function($master) use ($request){
                $id = $master->id;
            //    $link = $request->secure() ? secure_url(route('inspect.open')):route('inspect.open').'?id='.$id;
                $link = route('inspect.open').'?id='.$id;
                $btn = '<a href="' . $link . '" class="btn btn-sm btn-info"><span class="far fa-eye mx-1"></span>View</a>';
                $btn2 = '<form method="POST" action="' . route('delete.inspect'). '">' .
                    '<input type="hidden" nama="_token" value="' . csrf_token() . '">' .
                    '<input type="hidden" name="inspect_id" value="' . $id . '">' .
                    '<button type="submit" class="btn btn-sm btn-info"><span class="fas fa-trash-alt mx-1"></span>Delete</button>' .
                    '</form>';
                return $btn;
            })
            ->addColumn('route', function ($master) {
                $bridge = $master->bridge;
                $bridge->loadMissing('road');
                $road = $bridge->road;
                return $road->route->code.' - '.$road->route->name;

            })
            ->addColumn('region', function ($master) {
                $bridge = $master->bridge;
                $bridge->loadMissing('district');
                $district = $bridge->district;
                return $district->name.','.$district->state->name;

            })
            ->addColumn('asset', function ($master) {
                $bridge = $master->bridge;
                $bridge->loadMissing('asset');
                $asset = $bridge->asset;
                return $asset->name;

            })
            ->addColumn('asset', function ($master) {
                $bridge = $master->bridge;
                $bridge->loadMissing('asset');
                $asset = $bridge->asset;
                return $asset->name;

            })
            ->addColumn('structure_no', function ($master) {
                $bridge = $master->bridge;

                return $bridge->structure_no;

            })->addColumn('bridge_name', function ($master) {
                $bridge = $master->bridge;

                return $bridge->name;
            })
            ->rawColumns(['action','route','region','structure_no','bridge_name'])
            ->make(true);

    }
}
