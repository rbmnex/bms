@extends('layouts.main')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.6/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.css" />

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.6/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.js"></script>
<script src="{{asset('vendor/vanillajs-datepicker/dist/js/datepicker.min.js')}}"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Inspection Form</h3>
            </div>
            <div class="card-body">
                <form method="POST" id="inspectForm" action="{{route('inspect.approve')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="taskId" value="{{isset($task) ? $task->id : ''}}">
                    <input type="hidden" name="bridgeId" value="{{isset($bridge) ? $bridge->id : ''}}">
                    <input type="hidden" name="yearId" value="{{isset($year) ? $year->id : ''}}">
                    <input type="hidden" name="inspectId" value="{{isset($inspect) ? $inspect->id : ''}}">
                    <h6 class="h6">Location Data</h6>
                    <div class="row">
                        <div class="col">
                            <label class="my-1 mr-2" for="state">Route No. :</label>
                            <span>{{isset($route) ? $route->code : ''}}</span>
                        </div>
                        <div class="col">
                            <label class="my-1 mr-2" for="state">River/Bridge Name :</label>
                            <span>{{isset($bridge) ? $bridge->name : ''}}</span>
                        </div>
                    </div>
                    <div class="row pb-2">
                        <div class="col">
                            <label class="my-1 mr-2" for="state">Structure No. :</label>
                            <span>{{isset($bridge) ? $bridge->structure_no : ''}}</span>
                        </div>
                        <div class="col">
                            <label class="my-1 mr-2" for="state">District :</label>
                            <span>{{isset($district) ? $district->name : ''}}</span>
                        </div>
                        <div class="col">
                            <label class="my-1 mr-2" for="state">State :</label>
                            <span>{{isset($state) ? $state->name : ''}}</span>
                        </div>
                    </div>
                    <h6 class="h6">Bridge Type</h6>
                    <div class="row">
                        <div class="col">
                            <label class="my-1 mr-2" for="state">System Type :</label>
                            <span>{{isset($super->system) ? $super->system->name : ''}}</span>
                        </div>
                        <div class="col">
                            <label class="my-1 mr-2" for="state">Deck Type :</label>
                            <span>{{isset($super->deck) ? $super->deck->name : ''}}</span>
                        </div>
                    </div>
                    <div class="row pb-2">
                        <div class="col">
                            <label class="my-1 mr-2" for="state">Abutment Type :</label>
                            <span>{{isset($abutment->type) ? $abutment->type->name : ''}}</span>
                        </div>
                        <div class="col">
                            <label class="my-1 mr-2" for="state">Pier Type :</label>
                            <span>{{isset($pier->type) ? $pier->type->name : ''}}</span>
                        </div>
                    </div>
                    <h6 class="h6">Structure Data</h6>
                    <div class="row">
                        <!--
                        <div class="col">
                            <label class="my-1 mr-2" for="state">Road Width :</label>
                            <span></span>
                        </div>
                        -->
                        <div class="col">
                            <label class="my-1 mr-2" for="state">Bridge Width :</label>
                            <span>{{isset($geometry) ? $geometry->overall_width : ''}} m</span>
                        </div>
                        <div class="col">
                            <label class="my-1 mr-2" for="state">Skew Angle :</label>
                            <span>{{isset($geometry) ? $geometry->skew_angle : ''}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="my-1 mr-2" for="state">No. of Span :</label>
                            <span>{{isset($geometry) ? $geometry->spans_no : ''}}</span>
                        </div>
                        <div class="col">
                            <label class="my-1 mr-2" for="state">Span(s) :</label>
                            <span>{{isset($geometry) ? $geometry->max_span.' m ('.$geometry->spans_no.' SPAN)' : ''}}</span>
                        </div>
                        <div class="col">
                            <label class="my-1 mr-2" for="state">Year Built :</label>
                            <span>{{isset($year) ? $year->year : ''}}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="my-1 mr-2" for="state">Bridge Length :</label>
                            <span>{{isset($geometry) ? $geometry->total_length : ''}} m</span>
                        </div>
                        <!--
                        <div class="col">
                            <label class="my-1 mr-2" for="state">Year Repaired :</label>
                            <span></span>
                        </div>
                        -->
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="inspectionDate" class="my-1 mr-2">Inspection Date</label>
                                <span>{{isset($inspect) ? $inspect->inspection_date : ''}}</span>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="nextYear" class="my-1 mr-2">Year for next inspection</label>
                                <span>{{isset($inspect) ? $inspect->next_year : ''}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="my-1 mr-2" for="state">Remark :</label>
                        <span>{{isset($inspect) ? $inspect->remark : ''}}</span>
                    </div>
                    <hr>
                    <h6 class="h6">Bridge Components</h6>
                    <div class="row">
                        <div class="col-3"><label class="my-1 mr-2" for="component">Component Name :</label></div>
                        <div class="col"><label class="my-1 mr-2" for="rating">Condition Rating :</label></div>
                        <div class="col"><label class="my-1 mr-2" for="rating">Damage Type :</label></div>
                        <div class="col"><label class="my-1 mr-2" for="state">Remark :</label></div>
                    </div>
                    @isset($members)
                    @foreach($members as $item)
                    <div class="row">
                        <div class="col-2">
                            <span>{{$item->component->name}}</span>
                            <!--    <input type="hidden" value="{{$item->id}}" id="idHdn-{{$item->id}}"> -->
                        </div>
                        <div class="col">
                            <span class="text-wrap">
                                @isset($item->condition_rating)
                                @if($item->condition_rating == 1)
                                1 - No damages found and no maintenance required as a result of the inspection
                                @elseif($item->condition_rating == 2)
                                2 - Damaged detected and its necessary to record the condition for observation purposes
                                @elseif($item->condition_rating == 3)
                                3 - Damaged detected are slightly critical and thus its is necessary to implement routine
                                maintenance work
                                @elseif($item->condition_rating == 4)
                                4 - Damaged detected is critical and thus it is necessary to implement repair work or to carry out
                                a detailed inspection to determine whether ny rehabilitation works are required or not
                                @elseif($item->condition_rating == 5)
                                5 - Being heavily and critically damaged and possibly affecting the safety of traffic, it is necessary
                                to implement emergency temporary repair work immediately or rehabilitation work without delay after the provision of a load limitation traffic sign
                                @elseif($item->condition_rating == 0)
                                                0 - Bridge cannot fully inspected because of access problem such as submerged structures. Reinspection Necessary whenever possible
                                @else
                                Not Applicable
                                @endif
                                @endisset
                            </span>
                        </div>
                        <div class="col">
                            <span>@isset($item->damage) {{$item->damage->name}} @endisset</span>
                        </div>
                        <div class="col">
                            <div class="row">
                            @isset($item->photos)
                                @foreach($item->photos as $photo)
                                <div class="col">
                                    <img class="" id="img-{{$photo->id}}" src="{{ isset($photo->path) ? URL::asset('storage/inspection/'.$photo->path) : '' }}">
                                </div>
                                @endforeach
                                @php
                                $photo = NULL;
                                @endphp
                            @endisset
                            </div>
                        </div>

                        <div class="col">
                            <span>{{$item->remark}}</span>
                        </div>
                    </div>
                    @endforeach
                    @endisset

                    <button class="btn btn-primary" id="submitBtn" type="submit"><span class="far fa-thumbs-up mr-2"></span>Approve</button>
                    <button class="btn btn-primary" id="backBtn" onclick="history.back();" type="button"><span class="fas fa-backward mr-2"></span>Back</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- user modal -->
<div class="modal fade" id="modal-user" tabindex="-1" role="dialog" aria-labelledby="modal-user" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">User Lookup</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded" id="tableUsersList">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">#</th>
                                <th class="border-0">Name</th>
                                <th class="border-0">Designation</th>
                                <th class="border-0">Department</th>
                                <th class="border-0">Office</th>
                                <th class="border-0">Telephone No.</th>
                                <th class="border-0">Email</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyUser">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" onclick="selectedUser();" class="btn btn-primary"><span class="fa fa-plus mr-2"></span>Select User</button>
                <button type="button" class="btn btn-link text-danger ml-auto" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $(function() {
        const elem = document.getElementById('inspectionDateTxt');
        const datepicker = new Datepicker(elem, {
            // ...options
            format: 'yyyy-mm-dd'
        });
        $('#tableUsersList').DataTable({
            "lengthMenu": [5, 10, 15, 20, 30, 40, 50],
            processing: true,
            serverSide: true,
            ajax: "{{ route('inspect.user') }}",
            columns: [{
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: "border-0"
                },
                {
                    data: 'name',
                    name: 'name',
                    className: "border-0"
                },
                {
                    data: 'position',
                    name: 'position',
                    className: "border-0"
                },
                {
                    data: 'department',
                    name: 'department',
                    className: "border-0"
                },
                {
                    data: 'office',
                    name: 'office',
                    className: "border-0"
                },
                {
                    data: 'tel_no',
                    name: 'tel_no',
                    className: "border-0"
                },
                {
                    data: 'email',
                    name: 'email',
                    className: "border-0"
                }
            ]
        });
    });

    function selectedUser() {
        var selector = $('input[name="idUserRdo"]:checked').parent().parent();
        var id = $('input[name="idUserRdo"]:checked').val();
        $('#user_id').val(id);
        $('#nameTxt').val(selector.find('td:eq(1)').html());
        $('#positionTxt').val(selector.find('td:eq(2)').html());
    //  $('#departmentTxt').val(selector.find('td:eq(3)').html());
        $('#phoneTxt').val(selector.find('td:eq(5)').find('div').html());
        $('#officeTxt').val(selector.find('td:eq(4)').html());
        $('#emailTxt').val(selector.find('td:eq(6)').html());
    }
</script>
@endsection