<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Facades\Lookup;
use App\User;
use App\Custom\CommonHelper;
use App\Models\Passage;
use App\Models\Bridge;
use App\Models\Administrative;
use App\Models\ConstructionYear;
use App\Models\Geometry;
use App\Models\Superstructure;
use App\Models\Substructure;
use App\Models\Bearing;
use App\Models\Element;
use App\Models\Service;
use App\Models\PassageInfo;
use App\Models\Miscellaneous;
use App\Models\Task;
use App\Models\MasterLookup;
use App\Models\District;
use App\Models\Comment;
use App\Models\Office;
use App\Mail\TaskNotice;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class BridgeController extends Controller
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

    public function view()
    {
        $setup = $this->prepareParameter();

        $users = [];
        if (Auth::user()->hasRole("Administrator")) {
            $users = User::search([], ["Administrator", "Verifier"]);
        } else {
            $users = User::search(array(["office.name", '=', Auth::user()->office->name]), ["Verifier"]);
        }

        $setup['users'] = $users;

        return view("inventory.bridge-form", $setup);
    }

    public function saveBridge(Request $request)
    {
        $this->validateForm($request);

        $data = $request->all();

        if ($request->file('photo')) {

            $filename = CommonHelper::uploadImage($request->file('photo'), public_path() . '/storage/bridge');
        }

        $id = Bridge::insert($this->dataSetup($data, "Bridge", "insert"));

        $yearId = ConstructionYear::insert($id, $data['construction_year']);

        $data['filename'] = isset($filename) ? $filename : '';
        $data['bridgeId'] = $id;
        $data['yearId'] = $yearId;

        $adminId = Administrative::insert($this->dataSetup($data, 'Administrative', 'insert'));

        Geometry::insert($this->dataSetup($data, 'Geometry', 'insert'));

        Superstructure::insert($this->prepareData($data, 'Superstructure', '1', 'insert'));

        Superstructure::insert($this->prepareData($data, 'Superstructure', '2', 'insert'));

        Substructure::insert($this->prepareData($data, 'Substructure', Substructure::ABUTMENT, 'insert'));

        Substructure::insert($this->prepareData($data, 'Substructure', Substructure::PIER, 'insert'));

        Bearing::insert($this->prepareData($data, 'Bearing', Substructure::ABUTMENT, 'insert'));

        Bearing::insert($this->prepareData($data, 'Bearing', Substructure::PIER, 'insert'));

        Element::insert($this->dataSetup($data, 'Element', 'insert'));

        Service::insert($this->dataSetup($data, 'Service', 'insert'));

        PassageInfo::insert($this->dataSetup($data, 'PassageInfo', 'insert'));

        Miscellaneous::insert($this->dataSetup($data, 'Miscellaneous', 'insert'));

        $taskId = Task::insert($id, $data['user_id'], $yearId, $yearId, Task::REGISTER_PROCESS);

        if ($this->email) {
            try {
                Mail::to($data['email'])->send(new TaskNotice(Bridge::find($id), User::find($data['user_id'])));
            } catch (Exception $e) {
                report($e);
            }
        }


        return redirect(route("bridge.form"))->with('message', 'This Bridge been submitted and waiting for proccessing');
    }

    public function display(Request $request)
    {
        $route = "";
        if ($request->action == 'list') {
            $route = route('bridge.list');
            return view('inventory.bridge-list', compact('route'));
        } elseif ($request->action == 'task') {
            $route = route('bridge.task');
            return view('inventory.bridge-task', compact('route'));
        } elseif ($request->action == 'inbox') {
            $route = route('bridge.box');
            return view('inventory.bridge-task', compact('route'));
        }
        return view('inventory.bridge-list', compact('route'));
    }

    public function inbox(Request $request)
    {
        $bridges = Bridge::fetchByStatus([Task::ONHOLD_STATUS]);

        return Datatables::of($bridges)
            ->addColumn('action', function ($bridge) use ($request) {
                foreach ($bridge->years as $year) {
                    $link = route('bridge.edit'). '?id=' . $bridge->id . '&year=' . $year->id . '&task=0';
                }
                $btn = '<a href="' . $link . '" class="btn btn-sm btn-info"><span class="fas fa-search mx-1"></span>Edit</a>';
                $btn2 = '<form method="POST" action="' .route('delete.bridge'). '">' .
                    '<input type="hidden" nama="_token" value="' . csrf_token() . '">' .
                    '<input type="hidden" name="id" value="' . $bridge->id . '">' .
                    '<input type="hidden" name="year" value="' . $year->id . '">' .
                    '<button type="submit" class="btn btn-sm btn-info"><span class="fas fa-trash-alt mx-1"></span>Delete</button>' .
                    '</form>';
                return $btn;
            })
            ->addColumn('route', function ($bridge) {
                return $bridge->code . ' - ' . $bridge->road_name;
            })
            ->addColumn('region', function ($bridge) {
                return $bridge->district_name . ', ' . $bridge->state_name;
            })
            ->rawColumns(['action', 'route', 'region'])
            ->make(true);
    }

    public function list(Request $request)
    {
        /*
        if(Auth::user()->hasRole("Administrator")) {
            $condition = array();
        } else {
            $currentUser = User::find(Auth::user()->id);
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
        */

        //    $queryYears = ConstructionYear::where('status', Task::APPROVED_STATUS)->orderBy('bridge_id', 'desc')
        //        ->orderBy('year', 'desc')->get();

        //$bridges = Bridge::fetchByStatus([Task::APPROVED_STATUS],$condition);

        $bridges = Bridge::select('*')
        //  ->with('years')
            ->with('road')
            ->with('asset')
            ->with('ramp')
            ->with('district')
            ->whereHas('years',function (Builder $query) {
                $query->where('status', TASK::APPROVED_STATUS);
            })
            ->where('flag', 1);

        if (Auth::user()->hasRole(["Administrator","VIP"])) {
        //    $bridges->with('district');
        } else {
            $currentUser = User::find(Auth::user()->id);

            $office = Office::find($currentUser->office_id);
            if (isset($office)) {
                if ($office->hq == '1') {
                    $bridges->whereHas('district',function (Builder $query) use($office) {
                        $query->where('state_id',$office->state_id);
                    });
                } else {
                    $bridges->whereHas('district',function (Builder $query) use($office) {
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
                $btn = "";
                foreach ($bridge->years as $year) {
                    if ($year->status == TASK::APPROVED_STATUS) {
                        $btn .= '<a href="' . route('bridge.detail') . '?id=' . $bridge->id . '&year=' . $year->id . '" class="btn btn-sm btn-info mx-1"><span class="far fa-eye mx-1"></span>' . $year->year . '</a>';
                    }
                }
                if (Auth::user()->hasRole(["Administrator", "Verifier"])) {
                    $btn .= '<a href="' . route('bridge.year') . '?id=' . $bridge->id . '" class="btn btn-sm btn-info mx-1"><span class="fas fa-plus mx-1"></span>Add Year</a>';
                    $btn .= '<button onclick="deleteThis('. $bridge->id . ');" class="btn btn-sm btn-danger mx-1"><span class="fas fa-trash mx-1"></span>Delete</button>';
                    //mr-2 fa fa-trash
                }
                return $btn;
            })
            ->addColumn('place', function ($bridge) {
                $bridge->road->loadMissing('route');
                return $bridge->road->route->code . ' - ' . $bridge->road->route->name;
            })
            ->addColumn('route_code', function ($bridge) {
                $bridge->road->loadMissing('route');
                return $bridge->road->route->code;
            })
            ->addColumn('route_name', function ($bridge) {
                $bridge->road->loadMissing('route');
                return $bridge->road->route->name;
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
             ->addColumn('state_name', function ($bridge) {
                $bridge->loadMissing('district');
                if(isset($bridge->district)) {
                    $bridge->district->loadMissing('state');
                    return $bridge->district->state->name;

                } else {
                    return '';
                }
            })
            ->addColumn('span', function ($bridge) {
                $display = '';
                foreach ($bridge->years as $year) {
                    $year->loadMissing('geometry');
                    if (isset($year->geometry)) {
                        $display = $display . $year->geometry->spans_no . ' </br>';
                    }
                }

                return $display;
            })
            ->addColumn('max_span', function ($bridge) {
                $display = '';
                foreach ($bridge->years as $year) {
                    $year->loadMissing('geometry');
                    if (isset($year->geometry)) {
                        $display = $display . $year->geometry->max_span . ' </br>';
                    }
                }

                return $display;
            })
            ->addColumn('total_length', function ($bridge) {
                $display = '';
                foreach ($bridge->years as $year) {
                    $year->loadMissing('geometry');
                    if (isset($year->geometry)) {
                        $display = $display . $year->geometry->total_length . ' </br>';
                    }
                }
                return $display;
            })
            ->addColumn('bridge_width', function ($bridge) {
                $display = '';
                foreach ($bridge->years as $year) {
                    $year->loadMissing('geometry');
                    if (isset($year->geometry)) {
                        $display = $display . $year->geometry->overall_width . ' </br>';
                    }
                }
                return $display;
            })
            ->addColumn('c2c', function ($bridge) {
                $display = '';
                foreach ($bridge->years as $year) {
                    $year->loadMissing('geometry');
                    if (isset($year->geometry)) {
                        $display = $display . $year->geometry->crub_width . ' </br>';
                    }
                }
                return $display;
            })
            ->addColumn('skew', function ($bridge) {
                $display = '';
                foreach ($bridge->years as $year) {
                    $year->loadMissing('geometry');
                    if (isset($year->geometry)) {
                        $display = $display . $year->geometry->skew_angle . ' </br>';
                    }
                }
                return $display;
            })
            ->addColumn('year', function ($bridge) {
                $display = '';
                foreach ($bridge->years as $year) {
                    $display = $display . $year->year . ' </br>';
                }
                return $display;
            })
            ->addColumn('deck', function ($bridge) {
                $display = '';
                $index = 0;
                foreach ($bridge->years as $year) {
                    if (isset($year->superstructures)) {
                        $year->loadMissing('superstructures');
                        $superstructures = $year->superstructures;
                        foreach ($superstructures as $superstructure) {
                            if (isset($superstructure->deck)) {
                                if ($index > 0) {
                                    $display = $display . ' & ';
                                }
                                $display = $display . $superstructure->deck->name;
                                $index = $index + 1;
                            }
                        }
                        $index = 0;
                    }
                }
                return $display;
            })
            ->addColumn('system', function ($bridge) {
                $display = '';
                foreach ($bridge->years as $year) {
                    if (isset($year->superstructures)) {
                        $year->loadMissing('superstructures');
                        $superstructure = $year->superstructures->first();
                        if (isset($superstructure->system)) {
                            $display = $display . $superstructure->system->name . ' </br>';
                        }
                    }
                }
                return $display;
            })
            ->addColumn('material', function ($bridge) {
                $display = '';
                foreach ($bridge->years as $year) {
                    if (isset($year->superstructures)) {
                        $year->loadMissing('superstructures');
                        $superstructure = $year->superstructures->first();
                        if (isset($superstructure->material)) {
                            $display = $display . $superstructure->material->name . ' </br>';
                        }
                    }
                }
                return $display;
            })
            ->rawColumns([
                'action',
                'route',
                'region',
                'span',
                'max_span',
                'total_length',
                'bridge_width',
                'c2c',
                'skew',
                'year',
                'deck',
                'system',
                'material',
                'route_code',
                'route_name',
                'state_name'
            ])
            ->make(true);
    }

    public function task(Request $request)
    {
        $bridges = Bridge::fetchTask(Auth::user()->id, Task::REGISTER_PROCESS);

        return Datatables::of($bridges)
            ->addColumn('action', function ($bridge) use ($request) {
                $btn = '';
                if (Auth::user()->hasRole(["Administrator", "Verifier"]) && ($bridge->status == 'NEW' || $bridge->status == 'REVERIFY')) {
                    $link = route('bridge.edit') . '?id=' . $bridge->id . '&year=' . $bridge->year_id . '&task=' . $bridge->task_id;
                    $btn = $btn.'<a href="' . $link . '" class="btn btn-sm btn-info"><span class="fas fa-search mx-1"></span>' . $bridge->year . '</a>';
                } elseif (Auth::user()->hasRole(["Certifier", "Administrator"] && $bridge->status == 'PENDING')) {
                    $link = route('bridge.detail') . '?id=' . $bridge->id . '&year=' . $bridge->year_id . '&task=' . $bridge->task_id;
                    $btn = $btn.'<a href="' . $link . '" class="btn btn-sm btn-info"><span class="fas fa-search mx-1"></span>' . $bridge->year . '</a>';
                }


                return $btn;
            })
            ->addColumn('route', function ($bridge) {
                return $bridge->code . ' - ' . $bridge->road_name;
            })
            ->addColumn('region', function ($bridge) {
                return $bridge->district_name . ', ' . $bridge->state_name;
            })
            ->rawColumns(['action', 'route', 'region'])
            ->make(true);
    }

    public function detail(Request $request)
    {
        $data = $request->all();

        $bridge = Bridge::find($data['id']);
        $year = ConstructionYear::find($data['year']);
        $road = Passage::find($bridge->passage_id);
        $district = District::find($bridge->district_id);
        $task = isset($data['task']) ? $data['task'] : '';

        $conditions = [
            ["bridge_id", "=", $bridge->id],
            ["year_id", "=", $year->id]
        ];

        $administrative = Administrative::where($conditions)->first();
        $geometry = Geometry::where($conditions)->first();
        $superstructures = Superstructure::where($conditions)->orderBy('type', 'asc')->get();
        $substructures = Substructure::where($conditions)->orderBy('structure_type', 'asc')->get();
        $bearings = Bearing::where($conditions)->orderBy('structure_type', 'asc')->get();
        $element = Element::where($conditions)->first();
        $service = Service::where($conditions)->first();
        $passage = PassageInfo::where($conditions)->first();
        $miscellaneous = Miscellaneous::where($conditions)->first();

        $comments = empty($task) ? [] : Comment::where('task_id', '=', $task)->orderBy('created_at', 'desc')->get();

        return view('inventory.bridge-view', compact(
            'bridge',
            'year',
            'administrative',
            'geometry',
            'superstructures',
            'substructures',
            'bearings',
            'element',
            'service',
            'passage',
            'miscellaneous',
            'road',
            'district',
            'task',
            'comments'
        ));
    }

    public function preview(Request $request)
    {
        $setup = $this->prepareParameter();
        $data = $request->all();

        $bridge = Bridge::find($data['id']);
        $year = ConstructionYear::find($data['year']);
        $road = Passage::find($bridge->passage_id);
        $district = District::find($bridge->district_id);

        $condition = [];
        $users = [];

        if ($year->status == TASK::ONHOLD_STATUS) {
            if (Auth::user()->hasRole("Administrator")) {
                $users = User::search([], ["Administrator", "Verifier"]);
            } else {
                $users = User::search(array(["office.id", '=', Auth::user()->office_id]), ["Verifier"]);
            }
        } else {
            if (Auth::user()->hasRole("Administrator")) {
                $users = User::search([], ["Administrator", "Certifier"]);
            } else {
                $users = User::search(array(["office.id", '=', Auth::user()->office_id]), ["Certifier"]);
            }
        }

        $setup['users'] = $users;


        $bridge = Bridge::find($data['id']);
        $year = ConstructionYear::find($data['year']);
        $road = Passage::find($bridge->passage_id);
        $district = District::find($bridge->district_id);

        $setup['bridge'] = $bridge;
        $setup['year'] = $year;
        $setup['road'] = $road;
        $setup['district'] = $district;

        $task = isset($data['task']) ? $data['task'] : '';
        $setup['task'] = $task;

        $comments = isset($task) ? Comment::where('task_id', '=', $task)->orderBy('created_at', 'desc')->get() : [];
        $setup['comments'] = $comments;

        $conditions = [
            ["bridge_id", "=", $bridge->id],
            ["year_id", "=", $year->id]
        ];

        $administrative = Administrative::where($conditions)->first();
        $geometry = Geometry::where($conditions)->first();
        $superstructures = Superstructure::where($conditions)->orderBy('type', 'asc')->get();
        $substructures = Substructure::where($conditions)->orderBy('structure_type', 'asc')->get();
        $bearings = Bearing::where($conditions)->orderBy('structure_type', 'asc')->get();
        $element = Element::where($conditions)->first();
        $service = Service::where($conditions)->first();
        $passage = PassageInfo::where($conditions)->first();
        $miscellaneous = Miscellaneous::where($conditions)->first();

        $setup['administrative'] = $administrative;
        $setup['geometry'] = $geometry;
        $setup['superstructures'] = $superstructures;
        $setup['substructures'] = $substructures;
        $setup['bearings'] = $bearings;
        $setup['element'] = $element;
        $setup['service'] = $service;
        $setup['passage'] = $passage;
        $setup['miscellaneous'] = $miscellaneous;
        if ($task == 0) {
            $setup['action'] = route('apply.bridge');
        } else {
            $setup['action'] = route('bridge.verify');
        }
        $setup['previous'] = URL::previous();

        return view('inventory.bridge-form', $setup);
    }

    public function approve(Request $request)
    {
        $data = $request->all();
        Task::edit($data['task_id'], Task::APPROVED_STATUS, Auth::user()->id);
        ConstructionYear::where("id", '=', $data['year_id'])
            ->update([
                'status' => Task::APPROVED_STATUS,
                'updated_by' => Auth::user()->ic_no,
                'updated_at' => Date::now()
            ]);
        if (isset($data['comment'])) {
            $commentData = [
                'task_id' => $data['task_id'],
                'user_id' => Auth::user()->id,
                'created_at' => Date::now()
            ];
            Comment::insert($commentData);
        }
        return redirect(route("bridge.view"). "?action=task")->with('message', 'This bridge succsseful updated');
    }

    public function revert(Request $request)
    {
        $data = $request->all();
        $history = Task::previousOwner($data['task_id']);

        Task::edit($data['task_id'], Task::REVERIFY_STATUS, $history->user_id);
        $task = Task::find($data['task_id']);
        ConstructionYear::where("id", '=', $data['year_id'])
            ->update([
                'status' => Task::REVERIFY_STATUS,
                'updated_by' => Auth::user()->ic_no,
                'updated_at' => Date::now()
            ]);
        if (isset($data['comment'])) {
            $commentData = [
                'task_id' => $data['task_id'],
                'user_id' => Auth::user()->id,
                'comment' => $data['comment'],
                'created_at' => Date::now()
            ];
            Comment::insert($commentData);
        }
        $user = User::find($history->user_id);

        if ($this->email) {
            try {
                Mail::to($user->email)->send(new TaskNotice(Bridge::find($task->bridge_id), $user));
            } catch (Exception $e) {
                report($e);
            }
        }

        return redirect(route("bridge.view") . "?action=task")->with('message', 'This bridge successful reverted');
    }

    public function verify(Request $request)
    {
        $this->validateForm($request);

        $data = $request->all();

        if ($request->file('photo')) {
            $cFilename = $request->file('photo')->getClientOriginalName() . '.' . $request->file('photo')->getClientOriginalExtension();
            if ($data['photo_path'] != $cFilename) {
                $myFile = public_path() . '/storage/bridge/' . $data['photo_path'];
                File::delete($myFile);
                $filename = CommonHelper::uploadImage($request->file('photo'), public_path() . '/storage/bridge');
            }
        }

        Bridge::where("id", '=', $data['bridgeid'])->update($this->dataSetup($data, 'Bridge', 'update'));

        ConstructionYear::where("id", '=', $data['yearid'])
            ->update([
                'year' => $data['construction_year'],
                'status' => Task::PENDING_STATUS,
                'updated_by' => Auth::user()->ic_no,
                'updated_at' => Date::now()
            ]);

        $data['filename'] = isset($filename) ? $filename : '';

        Administrative::where('id', '=', $data['adminId'])->update($this->dataSetup($data, 'Administrative', 'update'));

        Geometry::where('id', '=', $data['geometryId'])->update($this->dataSetup($data, 'Geometry', 'update'));

        Superstructure::where('id', '=', $data['superstructureId'])->update($this->prepareData($data, 'Superstructure', '1', 'update'));

        Superstructure::where('id', '=', $data['superstructureId2'])->update($this->prepareData($data, 'Superstructure', '2', 'update'));

        Substructure::where('id', '=', $data['substructureId'])->update($this->prepareData($data, 'Substructure', Substructure::ABUTMENT, 'update'));

        Substructure::where('id', '=', $data['substructureId2'])->update($this->prepareData($data, 'Substructure', Substructure::PIER, 'update'));

        Bearing::where('id', '=', $data['bearingId'])->update($this->prepareData($data, 'Bearing', Substructure::ABUTMENT, 'update'));

        Bearing::where('id', '=', $data['bearingId2'])->update($this->prepareData($data, 'Bearing', Substructure::PIER, 'update'));

        Element::where('id', '=', $data['elementId'])->update($this->dataSetup($data, 'Element', 'update'));

        Service::where('id', '=', $data['serviceId'])->update($this->dataSetup($data, 'Service', 'update'));

        PassageInfo::where('id', '=', $data['roadId'])->update($this->dataSetup($data, 'PassageInfo', 'update'));

        Miscellaneous::where('id', '=', $data['miscellaneousId'])->update($this->dataSetup($data, 'Miscellaneous', 'update'));

        Task::edit($data['task_id'], Task::PENDING_STATUS, $data['user_id']);

        if ($this->email) {
            try {
                Mail::to($data['email'])->send(new TaskNotice(Bridge::find($data['bridgeid']), User::find($data['user_id'])));
            } catch (Exception $e) {
                report($e);
            }
        }
        return redirect(route("bridge.view") . "?action=task")->with('message', 'This bridge successful submitted and waiting approval');
    }

    public function apply(Request $request)
    {
        $this->validateForm($request);

        $data = $request->all();

        if ($request->file('photo')) {
            $cFilename = $request->file('photo')->getClientOriginalName() . '.' . $request->file('photo')->getClientOriginalExtension();
            if ($data['photo_path'] != $cFilename) {
                $myFile = public_path() . '/storage/bridge/' . $data['photo_path'];
                File::delete($myFile);
                $filename = CommonHelper::uploadImage($request->file('photo'), public_path() . '/storage/bridge');
            }
        }

        Bridge::where("id", '=', $data['bridgeid'])->update($this->dataSetup($data, 'Bridge', 'update'));

        ConstructionYear::where("id", '=', $data['yearid'])
            ->update([
                'year' => $data['construction_year'],
                'status' => Task::NEW_STATUS,
                'updated_by' => Auth::user()->ic_no,
                'updated_at' => Date::now()
            ]);

        $data['filename'] = isset($filename) ? $filename : '';

        Administrative::where('id', '=', $data['adminId'])->update($this->dataSetup($data, 'Administrative', 'update'));

        Geometry::where('id', '=', $data['geometryId'])->update($this->dataSetup($data, 'Geometry', 'update'));

        Superstructure::where('id', '=', $data['superstructureId'])->update($this->prepareData($data, 'Superstructure', '1', 'update'));

        Superstructure::where('id', '=', $data['superstructureId2'])->update($this->prepareData($data, 'Superstructure', '2', 'update'));

        Substructure::where('id', '=', $data['substructureId'])->update($this->prepareData($data, 'Substructure', Substructure::ABUTMENT, 'update'));

        Substructure::where('id', '=', $data['substructureId2'])->update($this->prepareData($data, 'Substructure', Substructure::PIER, 'update'));

        Bearing::where('id', '=', $data['bearingId'])->update($this->prepareData($data, 'Bearing', Substructure::ABUTMENT, 'update'));

        Bearing::where('id', '=', $data['bearingId2'])->update($this->prepareData($data, 'Bearing', Substructure::PIER, 'update'));

        Element::where('id', '=', $data['elementId'])->update($this->dataSetup($data, 'Element', 'update'));

        Service::where('id', '=', $data['serviceId'])->update($this->dataSetup($data, 'Service', 'update'));

        PassageInfo::where('id', '=', $data['roadId'])->update($this->dataSetup($data, 'PassageInfo', 'update'));

        Miscellaneous::where('id', '=', $data['miscellaneousId'])->update($this->dataSetup($data, 'Miscellaneous', 'update'));

        $taskId = Task::insert($data['bridgeid'], $data['user_id'], $data['yearid'], $data['yearid'], Task::REGISTER_PROCESS);

        if ($this->email) {
            try {
                Mail::to($data['email'])->send(new TaskNotice(Bridge::find($data['bridgeid']), User::find($data['user_id'])));
            } catch (Exception $e) {
                report($e);
            }
        }

        return redirect(route("bridge.view") . "?action=inbox")->with('message', 'This bridge successful submitted and waiting for approval');
    }

    public function edit(Request $request)
    {
        $setup = $this->prepareParameter();

        $users = [];
        if (Auth::user()->hasRole("Administrator")) {
            $users = User::search([], ["Administrator", "Certifier"]);
        } else {
            $users = User::search(array(["office.name", '=', Auth::user()->office->name]), ["Certifier"]);
        }

        $setup['users'] = $users;

        $data = $request->all();

        $bridge = Bridge::find($data['id']);
        $year = ConstructionYear::where('bridge_id', '=', $data['id'])->orderBy('year', 'desc')->first();
        $road = Passage::find($bridge->passage_id);
        $district = District::find($bridge->district_id);

        $setup['bridge'] = $bridge;
        $setup['year'] = $year;
        $setup['road'] = $road;
        $setup['district'] = $district;

        $conditions = [
            ["bridge_id", "=", $bridge->id],
            ["year_id", "=", $year->id]
        ];

        $administrative = Administrative::where($conditions)->first();
        $geometry = Geometry::where($conditions)->first();
        $superstructures = Superstructure::where($conditions)->orderBy('type', 'asc')->get();
        $substructures = Substructure::where($conditions)->orderBy('structure_type', 'asc')->get();
        $bearings = Bearing::where($conditions)->orderBy('structure_type', 'asc')->get();
        $element = Element::where($conditions)->first();
        $service = Service::where($conditions)->first();
        $passage = PassageInfo::where($conditions)->first();
        $miscellaneous = Miscellaneous::where($conditions)->first();

        $setup['administrative'] = $administrative;
        $setup['geometry'] = $geometry;
        $setup['superstructures'] = $superstructures;
        $setup['substructures'] = $substructures;
        $setup['bearings'] = $bearings;
        $setup['element'] = $element;
        $setup['service'] = $service;
        $setup['passage'] = $passage;
        $setup['miscellaneous'] = $miscellaneous;

        $setup['action'] = route('bridge.add');
        $setup['previous'] = URL::previous();
        $setup['add'] = 'add';

        return view('inventory.bridge-form', $setup);
    }

    public function review(Request $request)
    {
        $setup = $this->prepareParameter();
        $data = $request->all();

        $bridge = Bridge::find($data['bridge_id']);
        $year = ConstructionYear::find($data['year_id']);
        $road = Passage::find($bridge->passage_id);
        $district = District::find($bridge->district_id);

        $setup['bridge'] = $bridge;
        $setup['year'] = $year;
        $setup['road'] = $road;
        $setup['district'] = $district;

        $conditions = [
            ["bridge_id", "=", $bridge->id],
            ["year_id", "=", $year->id]
        ];

        $administrative = Administrative::where($conditions)->first();
        $geometry = Geometry::where($conditions)->first();
        $superstructures = Superstructure::where($conditions)->orderBy('type', 'asc')->get();
        $substructures = Substructure::where($conditions)->orderBy('structure_type', 'asc')->get();
        $bearings = Bearing::where($conditions)->orderBy('structure_type', 'asc')->get();
        $element = Element::where($conditions)->first();
        $service = Service::where($conditions)->first();
        $passage = PassageInfo::where($conditions)->first();
        $miscellaneous = Miscellaneous::where($conditions)->first();

        $setup['administrative'] = $administrative;
        $setup['geometry'] = $geometry;
        $setup['superstructures'] = $superstructures;
        $setup['substructures'] = $substructures;
        $setup['bearings'] = $bearings;
        $setup['element'] = $element;
        $setup['service'] = $service;
        $setup['passage'] = $passage;
        $setup['miscellaneous'] = $miscellaneous;

        return view('inventory.bridge-edit', $setup);
    }

    public function alter(Request $request)
    {
        $this->validateForm($request, false);

        $data = $request->all();

        if ($request->file('photo')) {
            $cFilename = $request->file('photo')->getClientOriginalName() . '.' . $request->file('photo')->getClientOriginalExtension();
            if ($data['photo_path'] != $cFilename) {
                $myFile = public_path() . '/storage/bridge/' . $data['photo_path'];
                File::delete($myFile);
                $filename = CommonHelper::uploadImage($request->file('photo'), public_path() . '/storage/bridge');
            }
        }

        Bridge::where("id", '=', $data['bridgeid'])->update($this->dataSetup($data, 'Bridge', 'update'));

        ConstructionYear::where("id", '=', $data['yearid'])
            ->update([
                'year' => $data['construction_year'],
                'status' => Task::APPROVED_STATUS,
                'updated_by' => Auth::user()->ic_no,
                'updated_at' => Date::now()
            ]);

        $data['filename'] = isset($filename) ? $filename : '';

        Administrative::where('id', '=', $data['adminId'])->update($this->dataSetup($data, 'Administrative', 'update'));

        Geometry::where('id', '=', $data['geometryId'])->update($this->dataSetup($data, 'Geometry', 'update'));

        Superstructure::where('id', '=', $data['superstructureId'])->update($this->prepareData($data, 'Superstructure', '1', 'update'));

        Superstructure::where('id', '=', $data['superstructureId2'])->update($this->prepareData($data, 'Superstructure', '2', 'update'));

        Substructure::where('id', '=', $data['substructureId'])->update($this->prepareData($data, 'Substructure', Substructure::ABUTMENT, 'update'));

        Substructure::where('id', '=', $data['substructureId2'])->update($this->prepareData($data, 'Substructure', Substructure::PIER, 'update'));

        Bearing::where('id', '=', $data['bearingId'])->update($this->prepareData($data, 'Bearing', Substructure::ABUTMENT, 'update'));

        Bearing::where('id', '=', $data['bearingId2'])->update($this->prepareData($data, 'Bearing', Substructure::PIER, 'update'));

        Element::where('id', '=', $data['elementId'])->update($this->dataSetup($data, 'Element', 'update'));

        Service::where('id', '=', $data['serviceId'])->update($this->dataSetup($data, 'Service', 'update'));

        PassageInfo::where('id', '=', $data['roadId'])->update($this->dataSetup($data, 'PassageInfo', 'update'));

        Miscellaneous::where('id', '=', $data['miscellaneousId'])->update($this->dataSetup($data, 'Miscellaneous', 'update'));

        return redirect(route("bridge.view") . "?action=list")->with('message', 'This bridge successful updated');
    }

    public function add(Request $request)
    {
        $this->validateForm($request);

        $data = $request->all();
        if ($request->file('photo')) {
            $filename = CommonHelper::uploadImage($request->file('photo'), public_path() . '/storage/bridge');
        }

        Bridge::where("id", '=', $data['bridgeid'])->update($this->dataSetup($data, 'Bridge', 'update'));

        $id = $data['bridgeid'];

        $yearId = ConstructionYear::insert($id, $data['construction_year']);

        $data['bridgeId'] = $id;
        $data['yearId'] = $yearId;
        $data['filename'] = isset($filename) ? $filename : '';

        $adminId = Administrative::insert($this->dataSetup($data, 'Administrative', 'insert'));

        Geometry::insert($this->dataSetup($data, 'Geometry', 'insert'));

        Superstructure::insert($this->prepareData($data, 'Superstructure', '1', 'insert'));

        Superstructure::insert($this->prepareData($data, 'Superstructure', '2', 'insert'));

        Substructure::insert($this->prepareData($data, 'Substructure', Substructure::ABUTMENT, 'insert'));

        Substructure::insert($this->prepareData($data, 'Substructure', Substructure::PIER, 'insert'));

        Bearing::insert($this->prepareData($data, 'Bearing', Substructure::ABUTMENT, 'insert'));

        Bearing::insert($this->prepareData($data, 'Bearing', Substructure::PIER, 'insert'));

        Element::insert($this->dataSetup($data, 'Element', 'insert'));

        Service::insert($this->dataSetup($data, 'Service', 'insert'));

        PassageInfo::insert($this->dataSetup($data, 'PassageInfo', 'insert'));

        Miscellaneous::insert($this->dataSetup($data, 'Miscellaneous', 'insert'));

        $taskId = Task::insert($id, $data['user_id'], $yearId, $yearId, "register");

        if ($this->email) {
            try {
                Mail::to($data['email'])->send(new TaskNotice(Bridge::find($id), User::find($data['user_id'])));
            } catch (Exception $e) {
                report($e);
            }
        }

        return redirect(route("bridge.view") . "?action=list")->with('message', 'This bridge successful submitted and waiting verification');
    }

    public function delete(Request $request)
    {
        $bridgeId = $request->bridgeid;
        $yearId = $request->yearid;
        //    $task = $request->task;

        $condition = [['bridge_id', '=', $bridgeId], ['year_id', '=', $yearId]];

        Geometry::where($condition)->delete();

        Superstructure::where($condition)->delete();

        Substructure::where($condition)->delete();

        Bearing::where($condition)->delete();

        Element::where($condition)->delete();

        Service::where($condition)->delete();

        PassageInfo::where($condition)->delete();

        Miscellaneous::where($condition)->delete();

        Administrative::where($condition)->delete();

        ConstructionYear::where('id', $yearId)->delete();

        Bridge::where('id', $bridgeId)->delete();

        //    Task::removeTask($task);

     //   return redirect($request->url())->with('message', 'The bridge information been deleted');
        return redirect(rtrim($request->previous, '/'))->with('message', 'Bridge information been deleted');
    }

    private function prepareParameter()
    {
        $states = Lookup::loadLookup('public.state');
        $equips = MasterLookup::loadLookup(4);
        $decks = MasterLookup::loadLookup('Deck');
        $systems = MasterLookup::loadLookup('System');
        $materials = MasterLookup::loadLookup('Material');
        $abudments = MasterLookup::loadLookup(8);
        $piers = MasterLookup::loadLookup(10);
        $foundations = MasterLookup::loadLookup(9);
        $bearingLists = MasterLookup::loadLookup(11);
        $parapets = MasterLookup::loadLookup('Parapet');
        $wearings = MasterLookup::loadLookup(12);
        $expansions = MasterLookup::loadLookup(13);
        $capacities = MasterLookup::loadLookup(15);
        $state_id = isset(Auth::user()->office) ? Auth::user()->office->state_id : 0;
        if (Auth::user()->hasRole(["Administrator"])) {
            $passages = Passage::search();
        } else {
            $condition = [
                ["public.state.id", '=', $state_id]
            ];
            $passages = Passage::search($condition);
        }
        $assets = MasterLookup::loadLookup(14);
        $ramps = MasterLookup::loadLookup(3);

        $setup = [
            'states' => $states,
            'equips' => $equips,
            'decks' => $decks,
            'systems' => $systems,
            'materials' => $materials,
            'abudments' => $abudments,
            'piers' => $piers,
            'foundations' => $foundations,
            'bearingLists' => $bearingLists,
            'parapets' => $parapets,
            'wearings' => $wearings,
            'expansions' => $expansions,
            'capacities' => $capacities,
            'passages' => $passages,
            'assets' => $assets,
            'ramps' => $ramps,
        ];

        return $setup;
    }

    public function save(Request $request)
    {
        // requirement from Mr. Dzoldi
         $this->minValidationForm($request);

        $data = $request->all();


        if (isset($data['bridgeid']) && empty($data['add'])) {
            if ($request->file('photo')) {
                $cFilename = $request->file('photo')->getClientOriginalName() . '.' . $request->file('photo')->getClientOriginalExtension();
                if ($data['photo_path'] != $cFilename) {
                    $myFile = public_path() . '/storage/bridge/' . $data['photo_path'];
                    File::delete($myFile);
                    $filename = CommonHelper::uploadImage($request->file('photo'), public_path() . '/storage/bridge');

                    $data['filename'] = $filename;
                } else {
                    $data['filename'] = $data['photo_path'];
                }
            }

            Bridge::where("id", '=', $data['bridgeid'])->update($this->dataSetup($data, 'Bridge', 'update'));

            ConstructionYear::where("id", '=', $data['yearid'])
                ->update([
                    'year' => $data['construction_year'],
                    'updated_by' => Auth::user()->ic_no,
                    'updated_at' => Date::now()
                ]);



            Administrative::where('id', '=', $data['adminId'])->update($this->dataSetup($data, 'Administrative', 'update'));

            Geometry::where('id', '=', $data['geometryId'])->update($this->dataSetup($data, 'Geometry', 'update'));

            Superstructure::where('id', '=', $data['superstructureId'])->update($this->prepareData($data, 'Superstructure', '1', 'update'));

            Superstructure::where('id', '=', $data['superstructureId2'])->update($this->prepareData($data, 'Superstructure', '2', 'update'));

            Substructure::where('id', '=', $data['substructureId'])->update($this->prepareData($data, 'Substructure', Substructure::ABUTMENT, 'update'));

            Substructure::where('id', '=', $data['substructureId2'])->update($this->prepareData($data, 'Substructure', Substructure::PIER, 'update'));

            Bearing::where('id', '=', $data['bearingId'])->update($this->prepareData($data, 'Bearing', Substructure::ABUTMENT, 'update'));

            Bearing::where('id', '=', $data['bearingId2'])->update($this->prepareData($data, 'Bearing', Substructure::PIER, 'update'));

            Element::where('id', '=', $data['elementId'])->update($this->dataSetup($data, 'Element', 'update'));

            Service::where('id', '=', $data['serviceId'])->update($this->dataSetup($data, 'Service', 'update'));

            PassageInfo::where('id', '=', $data['roadId'])->update($this->dataSetup($data, 'PassageInfo', 'update'));

            Miscellaneous::where('id', '=', $data['miscellaneousId'])->update($this->dataSetup($data, 'Miscellaneous', 'update'));
        } else {
            if ($request->file('photo')) {

                $filename = CommonHelper::uploadImage($request->file('photo'), public_path() . '/storage/bridge');
            }

            $id = Bridge::insert($this->dataSetup($data, "Bridge", "insert"));

            $yearId = ConstructionYear::insert($id, $data['construction_year'], TASK::ONHOLD_STATUS);

            $data['filename'] = isset($filename) ? $filename : '';
            $data['bridgeId'] = $id;
            $data['yearId'] = $yearId;

            $adminId = Administrative::insert($this->dataSetup($data, 'Administrative', 'insert'));

            Geometry::insert($this->dataSetup($data, 'Geometry', 'insert'));

            Superstructure::insert($this->prepareData($data, 'Superstructure', '1', 'insert'));

            Superstructure::insert($this->prepareData($data, 'Superstructure', '2', 'insert'));

            Substructure::insert($this->prepareData($data, 'Substructure', Substructure::ABUTMENT, 'insert'));

            Substructure::insert($this->prepareData($data, 'Substructure', Substructure::PIER, 'insert'));

            Bearing::insert($this->prepareData($data, 'Bearing', Substructure::ABUTMENT, 'insert'));

            Bearing::insert($this->prepareData($data, 'Bearing', Substructure::PIER, 'insert'));

            Element::insert($this->dataSetup($data, 'Element', 'insert'));

            Service::insert($this->dataSetup($data, 'Service', 'insert'));

            PassageInfo::insert($this->dataSetup($data, 'PassageInfo', 'insert'));

            Miscellaneous::insert($this->dataSetup($data, 'Miscellaneous', 'insert'));
        }

        if (substr($data['previous'], -1) == '/') {
            $path = substr($data['previous'], 0, strlen($data['previous']) - 1);
        } else {
            $path = $data['previous'];
        }
        return redirect($path)->with('message', 'Bridge information been saved');
    }

    public function removeBridge(Request $request) {
        $data = $request->all();
        Bridge::where('id',$data['bridge_id'])->update(['flag' => 0]);
        return redirect(route("bridge.view") . "?action=list")->with('message', 'This bridge successfully removed');
    }

    public function removeYear(Request $request) {
        $data = $request->all();
        ConstructionYear::where('bridge_id',$data['bridge_id'])->where('id',$data['year_id'])->update(['flag' => 0]);
        return redirect(route("bridge.view") . "?action=list")->with('message', 'This bridge successfully removed');
    }

    private function prepareData(array $data, string $model, string $type, string $ops)
    {
        $container = array();
        switch ($model) {
            case "Superstructure":
                if ($ops === "insert") {
                    if ($type === '1') {
                        $container = [
                            "bridge_id" => $data['bridgeId'],
                            "deck_id" => $data['deck'],
                            "system_id" => $data['system'],
                            "material_id" => $data['material'],
                            "type" => "1",
                            "year_id" => $data['yearId'],
                            "created_by" => Auth::user()->ic_no,
                            "created_at" => Date::now()
                        ];
                    } elseif ($type === '2') {
                        $container = [
                            "bridge_id" => $data['bridgeId'],
                            "deck_id" => $data['deck2'],
                            "system_id" => $data['system2'],
                            "material_id" => $data['material2'],
                            "type" => "2",
                            "year_id" => $data['yearId'],
                            "created_by" => Auth::user()->ic_no,
                            "created_at" => Date::now()
                        ];
                    }
                } elseif ($ops === "update") {
                    if ($type === '1') {
                        $container = [
                            "deck_id" => $data['deck'],
                            "system_id" => $data['system'],
                            "material_id" => $data['material'],
                            "type" => "1",
                            "updated_by" => Auth::user()->ic_no,
                            "updated_at" => Date::now()
                        ];
                    } elseif ($type === '2') {
                        $container = [
                            "deck_id" => $data['deck2'],
                            "system_id" => $data['system2'],
                            "material_id" => $data['material2'],
                            "type" => "2",
                            "updated_by" => Auth::user()->ic_no,
                            "updated_at" => Date::now()
                        ];
                    }
                }
                break;
            case "Substructure":
                if ($ops === "insert") {
                    if ($type === Substructure::ABUTMENT) {
                        $container = [
                            "bridge_id" => $data['bridgeId'],
                            "type_id" => $data['abutment_type'],
                            "material_id" => $data['abutment_material'],
                            "structure_type" => Substructure::ABUTMENT,
                            "foundation_id" => $data['abutment_foundation'],
                            "year_id" => $data['yearId'],
                            "created_by" => Auth::user()->ic_no,
                            "created_at" => Date::now()
                        ];
                    } elseif ($type === Substructure::PIER) {
                        $container = [
                            "bridge_id" => $data['bridgeId'],
                            "type_id" => $data['pier_type'],
                            "material_id" => $data['pier_material'],
                            "structure_type" => Substructure::PIER,
                            "foundation_id" => $data['pier_foundation'],
                            "year_id" => $data['yearId'],
                            "created_by" => Auth::user()->ic_no,
                            "created_at" => Date::now()
                        ];
                    }
                } elseif ($ops === "update") {
                    if ($type === Substructure::ABUTMENT) {
                        $container = [
                            "type_id" => $data['abutment_type'],
                            "material_id" => $data['abutment_material'],
                            "structure_type" => Substructure::ABUTMENT,
                            "foundation_id" => $data['abutment_foundation'],
                            "updated_by" => Auth::user()->ic_no,
                            "updated_at" => Date::now()
                        ];
                    } elseif ($type === Substructure::PIER) {
                        $container = [
                            "type_id" => $data['pier_type'],
                            "material_id" => $data['pier_material'],
                            "structure_type" => Substructure::PIER,
                            "foundation_id" => $data['pier_foundation'],
                            "updated_by" => Auth::user()->ic_no,
                            "updated_at" => Date::now()
                        ];
                    }
                }
                break;
            case "Bearing":
                if ($ops === "insert") {
                    if ($type === Substructure::ABUTMENT) {
                        $container = [
                            "bridge_id" => $data['bridgeId'],
                            "structure_type" => Substructure::ABUTMENT,
                            "fixed_id" => $data['fixed_abutment'],
                            "free_id" => $data['free_abutment'],
                            "year_id" => $data['yearId'],
                            "created_by" => Auth::user()->ic_no,
                            "created_at" => Date::now()
                        ];
                    } elseif ($type === Substructure::PIER) {
                        $container = [
                            "bridge_id" => $data['bridgeId'],
                            "structure_type" => Substructure::PIER,
                            "fixed_id" => $data['fixed_pier'],
                            "free_id" => $data['free_pier'],
                            "year_id" => $data['yearId'],
                            "created_by" => Auth::user()->ic_no,
                            "created_at" => Date::now()
                        ];
                    }
                } elseif ($ops === "update") {
                    if ($type === Substructure::ABUTMENT) {
                        $container = [
                            "structure_type" => Substructure::ABUTMENT,
                            "fixed_id" => $data['fixed_abutment'],
                            "free_id" => $data['free_abutment'],
                            "updated_by" => Auth::user()->ic_no,
                            "updated_at" => Date::now()
                        ];
                    } elseif ($type === Substructure::PIER) {
                        $container = [
                            "structure_type" => Substructure::PIER,
                            "fixed_id" => $data['fixed_pier'],
                            "free_id" => $data['free_pier'],
                            "updated_by" => Auth::user()->ic_no,
                            "updated_at" => Date::now()
                        ];
                    }
                }
                break;
            default:
        }

        return $container;
    }

    private function dataSetup(array $data, string $type, string $ops)
    {
        $container = array();
        switch ($type) {
            case "Bridge":
                $section = explode('.', $data['kilometer']);
                if ($ops === "insert") {
                    $container = [
                        "name" => $data['bridge_name'],
                        "structure_no" => $data['route_code'] . '/' . sprintf("%03d", $section[0]) . '/' . str_pad($section[1], 3, "0", STR_PAD_RIGHT),
                        "passage_id" => $data['passage_id'],
                        "district_id" => $data['district'],
                        "remark" => $data['remark_bridge'],
                        "asset_id" => $data['asset'],
                        "ramp_id" => isset($data['ramp']) ? $data['ramp'] : NULL,
                        "created_by" => Auth::user()->ic_no,
                        "created_at" => Date::now()
                    ];
                } elseif ($ops === "update") {
                    $container = [
                        "name" => $data['bridge_name'],
                        "structure_no" => $data['route_code'] . '/' . sprintf("%03d", $section[0]) . '/' . str_pad($section[1], 3, "0", STR_PAD_RIGHT),
                        "passage_id" => $data['passage_id'],
                        "district_id" => $data['district'],
                        "remark" => $data['remark_bridge'],
                        "asset_id" => $data['asset'],
                        "ramp_id" => isset($data['ramp']) ? $data['ramp'] : NULL,
                        "updated_by" => Auth::user()->ic_no,
                        "updated_at" => Date::now()
                    ];
                }
                break;
            case "Administrative":
                if ($ops === "insert") {
                    $container = [
                        "special" => empty($data['special_bridge']) ? '0' : '1',
                        "access_equipment_id" => $data['access_equipment'],
                    //    "maintenance_date" => $data['maintenance_date'],
                    //    "maintenance_cost" => $data['maintenance_cost'],
                        "photo_path" => $data['filename'],
                        "bridge_id" => $data['bridgeId'],
                        "year_id" => $data['yearId'],
                        "created_by" => Auth::user()->ic_no,
                        "created_at" => Date::now()
                    ];
                } elseif ($ops === "update") {
                    $container = [
                        "special" => empty($data['special_bridge']) ? '0' : '1',
                        "access_equipment_id" => $data['access_equipment'],
                    //    "maintenance_date" => $data['maintenance_date'],
                    //    "maintenance_cost" => $data['maintenance_cost'],
                    //    "photo_path" => $data['filename'],
                        "updated_by" => Auth::user()->ic_no,
                        "updated_at" => Date::now()
                    ];

                    if (!empty($filename)) {
                        $container["photo_path"] = $filename;
                    }
                }
                break;
            case "Geometry":
                if ($ops === "insert") {
                    $container = [
                        "bridge_id" => $data['bridgeId'],
                        "spans_no" => $data['spans_no'],
                        "min_span" => $data['span_min'],
                        "max_span" => $data['span_max'],
                        "total_length" => $data['total_length'],
                        "overall_width" => $data['overall_width'],
                        "median_width" => $data['median_width'],
                        "carriageways_width" => $data['carriageways_width'],
                        "crub_width" => $data['c2c_width'],
                        "approach_width" => $data['c2c_width'],
                        "sidewalk_left" => $data['sidewalkl_width'],
                        "sidewalk_right" => $data['sidewalkr_width'],
                        "skew_angle" => $data['skew_angle'],
                        "year_id" => $data['yearId'],
                        "created_by" => Auth::user()->ic_no,
                        "created_at" => Date::now()
                    ];
                } elseif ($ops === "update") {
                    $container = [
                        "spans_no" => $data['spans_no'],
                        "min_span" => $data['span_min'],
                        "max_span" => $data['span_max'],
                        "total_length" => $data['total_length'],
                        "overall_width" => $data['overall_width'],
                        "median_width" => $data['median_width'],
                        "carriageways_width" => $data['carriageways_width'],
                        "crub_width" => $data['c2c_width'],
                        "approach_width" => $data['c2c_width'],
                        "sidewalk_left" => $data['sidewalkl_width'],
                        "sidewalk_right" => $data['sidewalkr_width'],
                        "skew_angle" => $data['skew_angle'],
                        "updated_by" => Auth::user()->ic_no,
                        "updated_at" => Date::now()
                    ];
                }
                break;
            case "Element":
                if ($ops === "insert") {
                    $container = [
                        "parapet_id" => $data['parapet'],
                        "wearing_surface_id" => $data['wearing_surface'],
                        "expansion_joint_id" => $data['expansion_joint'],
                        "max_load" => $data['max_load'],
                        "other" => $data['other_element'],
                        "bridge_id" => $data['bridgeId'],
                        "year_id" => $data['yearId'],
                        "created_by" => Auth::user()->ic_no,
                        "created_at" => Date::now()
                    ];
                } elseif ($ops === "update") {
                    $container = [
                        "parapet_id" => $data['parapet'],
                        "wearing_surface_id" => $data['wearing_surface'],
                        "expansion_joint_id" => $data['expansion_joint'],
                        "max_load" => $data['max_load'],
                        "other" => $data['other_element'],
                        "updated_by" => Auth::user()->ic_no,
                        "updated_at" => Date::now()
                    ];
                }
                break;
            case "Service":
                if ($ops === "insert") {
                    $container = [
                        "bridge_id" => $data['bridgeId'],
                        "tnb_cables" => empty($data['tnb']) ? '0' : '1',
                        "telecom_cables" => empty($data['telekom']) ? '0' : '1',
                        "watermain" => empty($data['watermain']) ? '0' : '1',
                        "lighting" => empty($data['lighting']) ? '0' : '1',
                        "other" => $data['other_services'],
                        "year_id" => $data['yearId'],
                        "created_by" => Auth::user()->ic_no,
                        "created_at" => Date::now()
                    ];
                } elseif ($ops === "update") {
                    $container = [
                        "tnb_cables" => empty($data['tnb']) ? '0' : '1',
                        "telecom_cables" => empty($data['telekom']) ? '0' : '1',
                        "watermain" => empty($data['watermain']) ? '0' : '1',
                        "lighting" => empty($data['lighting']) ? '0' : '1',
                        "other" => $data['other_services'],
                        "updated_by" => Auth::user()->ic_no,
                        "updated_at" => Date::now()
                    ];
                }
                break;
            case "PassageInfo":
                if ($ops === "insert") {
                    $container = [
                        "bridge_id" => $data['bridgeId'],
                        "design_load" => $data['design_load'],
                        "design_code" => $data['design_code'],
                        "capacity_id" => $data['capacity'],
                        "discounted_capacity" => $data['discounted_capacity'],
                        "vertical_clearance_l" => isset($data['l']) ? $data['l'] : 0,
                        "vertical_clearance_lm" => isset($data['lm']) ? $data['lm'] : 0,
                        "vertical_clearance_rm" => isset($data['rm']) ? $data['rm'] : 0,
                        "vertical_clearance_r" => isset($data['r']) ? $data['r'] : 0,
                        "vertical_clearance_o" => isset($data['o']) ? $data['o'] : 0,
                        "year_id" => $data['yearId'],
                        "created_by" => Auth::user()->ic_no,
                        "created_at" => Date::now()
                    ];
                } elseif ($ops === "update") {
                    $container = [
                        "design_load" => $data['design_load'],
                        "design_code" => $data['design_code'],
                        "capacity_id" => $data['capacity'],
                        "discounted_capacity" => $data['discounted_capacity'],
                        "vertical_clearance_l" => isset($data['l']) ? $data['l'] : 0,
                        "vertical_clearance_lm" => isset($data['lm']) ? $data['lm'] : 0,
                        "vertical_clearance_rm" => isset($data['rm']) ? $data['rm'] : 0,
                        "vertical_clearance_r" => isset($data['r']) ? $data['r'] : 0,
                        "vertical_clearance_o" => isset($data['o']) ? $data['o'] : 0,
                        "updated_by" => Auth::user()->ic_no,
                        "updated_at" => Date::now()
                    ];
                }
                break;
            case "Miscellaneous":
                if ($ops === "insert") {
                    $container = [
                        "bridge_id" => $data['bridgeId'],
                        "owner" => $data['owner'],
                        "designer" => $data['designer'],
                        "inspection_responsible" => $data['inpection_responsible'],
                        "maintenance_dept" => $data['maintenance_department'],
                        "coordinate_x" => isset($data['coordinatex']) ? $data['coordinatex'] : NULL,
                        "coordinate_y" => isset($data['coordinatey']) ? $data['coordinatey'] : NULL,
                        "year_id" => $data['yearId'],
                        "created_by" => Auth::user()->ic_no,
                        "created_at" => Date::now()
                    ];
                } elseif ($ops === "update") {
                    $container = [
                        "owner" => $data['owner'],
                        "designer" => $data['designer'],
                        "inspection_responsible" => $data['inpection_responsible'],
                        "maintenance_dept" => $data['maintenance_department'],
                        "coordinate_x" => isset($data['coordinatex']) ? $data['coordinatex'] : NULL,
                        "coordinate_y" => isset($data['coordinatey']) ? $data['coordinatey'] : NULL,
                        "updated_by" => Auth::user()->ic_no,
                        "updated_at" => Date::now()
                    ];
                }
                break;
            default:
        }

        return $container;
    }

    private function validateForm(Request $request, $checkUser = true)
    {
        $validateFields = [
            'bridge_name' => 'required|string',
            'asset' => 'required',
            'route_code' => 'required',
            'state' => 'required',
            'district' => 'required',
            'construction_year' => 'required|numeric',
            'spans_no' => 'required|numeric',
            'span_min' => 'required|numeric',
            'span_max' => 'required|numeric',
            'total_length' => 'required|numeric',
            'overall_width' => 'required|numeric',
            'skew_angle' => 'required|numeric',
            'deck' => 'required',
            'system' => 'required',
            'material' => 'required',
            'abutment_type' => 'required',
            'abutment_material' => 'required',
            'abutment_foundation' => 'required',
            'coordinatex' => 'required|numeric',
            'coordinatey' => 'required|numeric',
        ];

        if ($checkUser) {
            $validateFields['name_user'] = 'required|string';
            $validateFields['email'] = 'required|email';
        }
        $request->validate($validateFields);
    }

    private function minValidationForm($request) {
        $validateFields = [
            'bridge_name' => 'required|string',
            'route_code' => 'required',
        ];
        $request->validate($validateFields);
    }
}
