@extends('layouts.main')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.6/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.css" />
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Inspection Form</h3>
            </div>
            <div class="card-body">
                <form method="POST" id="inspectForm" action="{{route('save.inspect')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="bridgeId" value="{{isset($bridge) ? $bridge->id : ''}}">
                    <input type="hidden" name="yearId" value="{{isset($year) ? $year->id : ''}}">
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
                    <div class="form-group">
                        <label for="inspector_name" class="my-1 mr-2">Inspector Name <span style="color : red;">*</span></label>
                        <input type="text" name="inspector_name" class="form-control" id="inspectorNameTxt" value="" required>
                        </input>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="inspectionDate" class="my-1 mr-2">Inspection Date <span style="color : red;">*</span></label>
                                <input data-datepicker="" name="inspectionDate" class="form-control" id="inspectionDateTxt" value="" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="nextYear" class="my-1 mr-2">Year for next inspection</label>
                                <input type="number" name="nextYear" class="form-control" id="nextYearTxt" value="">
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="my-1 mr-2" for="accident_record">Accident Record (POL)</label>
                                <input type="text" name="accident_record" class="form-control" id="accidentRecordTxt" value="">
                                </input>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="my-1 mr-2" for="flood_level">Flood Level</label>
                                <input type="text" name="flood_level" class="form-control" id="floodLevelTxt" value="">
                                </input>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="my-1 mr-2" for="state">Remark :</label>
                        <textarea class="form-control" name="mainRemark" id="mainRemarkTxt" rows="4"></textarea>
                    </div>
                    <hr>
                    <h6 class="h6">Bridge Components</h6>
                    <div class="row">
                        <div class="col-3"><label class="my-1 mr-2" for="component">Component Name :</label></div>
                        <div class="col"><label class="my-1 mr-2" for="rating">Condition Rating :</label></div>
                        <div class="col"><label class="my-1 mr-2" for="rating">Damage Type :</label></div>
                    <!--    <div class="col"><label class="my-1 mr-2" for="state">Remark :</label></div> -->
                    </div>
                    @isset($members)
                    @foreach($members as $item)
                    <div class="row mb-1">
                        <div class="col-3">
                            
                            <span>{{$item->name}}</span>
                            <!--    <input type="hidden" value="{{$item->id}}" id="idHdn-{{$item->id}}"> -->
                        </div>
                        <div class="col">
                            <div class="mb-1">
                                
                                <select class="form-select" name="rating-{{$item->id}}" id="ratingSlt-{{$item->id}}" style="word-wrap:break-word;width:100%;">
                                    <option value="">Please select rating</option>
<option value="0" title="Bridge cannot fully inspected because of access problem such as submerged structures. Reinspection Necessary whenever possible">0</option>
<option value="1" title="No damages found and no maintenance required as a result of the inspection">1</option>
<option value="2" title="Damaged detected and its necessary to record the condition for observation purposes">2</option>
<option value="3" title="Damaged detected are slightly critical and thus its is necessary to implement routine maintenance work">3</option>
<option value="4" title="Damaged detected is critical and thus it is necessary to implement repair work or to carry out a detailed inspection to determine whether ny rehabilitation works are required or not">4</option>
<option value="5" title="Being heavily and critically damaged and possibly affecting the safety of traffic, it is necessary to implement emergency temporary repair work immediately or rehabilitation work without delay after the provision of a load limitation traffic sign">5</option>     
                                </select>
                            </div>
                            <div>
                                <select class="form-select mb-1" name="damage-{{$item->id}}" id="damageSlt-{{$item->id}}" style="word-wrap:break-word;width:100%;">
                                <option value="">Please select damage</option>
                                @isset($damages)
                                @foreach($damages as $damage)
                                <option value="{{$damage->id}}">{{$damage->name}}</option>
                                @endforeach
                                @endisset
                                </select>
                                
                            </div>
                        </div>
                        <div class="col">
                            <textarea class="form-control mb-1" name="remark-{{$item->id}}" id="remarkTxt-{{$item->id}}" rows="3" placeholder="Remark Here...."></textarea>
                                <input type="hidden" name="idComp-{{$item->id}}" id="compId-{{$item->id}}" />
                            <div class="form-file custom-file">
                                <input type="file" accept="image/gif, image/jpeg, image/png" onchange="readURL(this,'imgComp-{{$item->id}}');" name="photo-{{$item->id}}" class="form-file-input" id="compFile-{{$item->id}}">

                                <label class="form-file-label" for="customFile">
                                    <span class="form-file-text">Choose component photo...</span>
                                    <span class="form-file-button">Browse</span>
                                </label>

                                <img class="" id="imgComp-{{$item->id}}">
                                <input type="hidden" name="photo_path-{{$item->id}}" id="photoHdn-{{$item->id}}">
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endisset
                    <input type="hidden" name="memberIds" value="{{ isset($arr) ? json_encode($arr) : '' }}">
                    <hr>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center ">
                        <button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#modal-user">
                            <span class="fas fa-plus mr-2"></span>Assign User
                        </button>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="my-1 mr-2" for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="nameTxt" value="" data-readonly required>
                                </input>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="my-1 mr-2" for="position">Position</label>
                                <input type="text" name="position" class="form-control" id="positionTxt" value="" readonly>
                                </input>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label class="my-1 mr-2" for="phone">Phone</label>
                                <input type="text" name="phone" class="form-control" id="phoneTxt" value="{{old('phone')}}" data-readonly>
                                </input>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <!--
                        <div class="col">
                            <div class="form-group">
                                <label class="my-1 mr-2" for="department">Department</label>
                                <input type="text" name="department" class="form-control" id="departmentTxt" value="" readonly>
                                </input>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        -->
                        <div class="col">
                            <div class="form-group">
                                <label class="my-1 mr-2" for="office">Office</label>
                                <input type="text" name="office" class="form-control" id="officeTxt" value="" readonly>
                                </input>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label class="my-1 mr-2" for="email">Email</label>
                                <input type="text" name="email" class="form-control" id="emailTxt" value="" required>
                                </input>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" id="user_id">
                    <input type="hidden" name="inspect_id" id="inspect_id">
                    <button class="btn btn-primary" id="saveBtn" type="button" onclick="saveInfo();"><span class="far fa-paper-plane mr-2"></span>Save</button>
                    <button class="btn btn-primary" id="submitBtn" type="submit"><span class="far fa-paper-plane mr-2"></span>Submit</button>
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
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.6/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.js"></script>
<script src="{{asset('vendor/vanillajs-datepicker/dist/js/datepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.validate-1.19.3.min.js')}}"></script>
<script type="text/javascript">
    function saveInfo() {
       
        $('#nameTxt').removeAttr("required");
        $('#emailTxt').removeAttr("required");
        $('#inspectForm').attr("action","{{route('inspect.hold')}}");
        $('#inspectForm').submit();
    }

    $(function() {
        const elem = document.getElementById('inspectionDateTxt');
        const datepicker = new Datepicker(elem, {
            // ...options
            format: 'yyyy-mm-dd'
        });
        $('#inspectForm').validate();
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

    @if(count($users) === 1)
        $('#user_id').val("{{$users[0]->id}}");
        $('#nameTxt').val("{{$users[0]->name}}");
        $('#positionTxt').val("{{ $users[0]->position }}");
        //$('#departmentTxt').val(selector.find('td:eq(3)').find('div').html());
        $('#phoneTxt').val("{{$users[0]->tel_no}}");
        $('#officeTxt').val("{{ $users[0]->office }}");
        $('#emailTxt').val("{{$users[0]->email}}");
    @endif

    function selectedUser() {
        var selector = $('input[name="idUserRdo"]:checked').parent().parent();
        var id = $('input[name="idUserRdo"]:checked').val();
        $('#user_id').val(id);
        $('#nameTxt').val(selector.find('td:eq(1)').html());
        $('#positionTxt').val(selector.find('td:eq(2)').html());
    //  $('#departmentTxt').val(selector.find('td:eq(3)').html());
        $('#phoneTxt').val(selector.find('td:eq(5)').html());
        $('#officeTxt').val(selector.find('td:eq(4)').html());
        $('#emailTxt').val(selector.find('td:eq(6)').html());
    }

    function readURL(input, selector) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#' + selector).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
</script>
@endsection