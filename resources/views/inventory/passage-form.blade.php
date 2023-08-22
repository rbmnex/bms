@extends('layouts.main')

@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.6/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.css"/>
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Road Form</h3>
            </div>
            <div class="card-body">
                <form method="POST" id="roadForm" action="{{ route('save.road') }}">
                    @csrf
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="passage_number" class="my-1 mr-2">Section Number <span style="color : red;">*</span></label>
                                <input type="text" name="passage_number" style="text-transform: uppercase" class="form-control" id="passageNoTxt" value="" required>
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="form-group">
                                <label class="my-1 mr-2" for="passage_type">Type <span style="color : red;">*</span></label>
                                <select class="form-select" name="passage_type" id="passageTypeSlt" required>
                                    <option value="" selected>Please select</option>
                                    @isset($lookup)
                                    @foreach($lookup as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <input class="form-check-input" name="passage_primary" type="checkbox" value="1" id="passagePrimaryChk">
                                <label for="passage_primary" class="my-1 mr-2 ml">Primary Passage?</label>
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group ">
                                <label for="passage_ou" class="my-1 mr-2">Over Bridge/Under Bridge? <span style="color : red;">*</span></label>
                                <select class="form-select" name="passage_ou" id="passageOUSlt" aria-label="Over Bridge/Under Bridge?" required>
                                    <option value="" selected>Please select</option>
                                    <option value="OB" selected>Over Bridge</option>
                                    <option value="UB" selected>Under Bridge</option>
                                </select>
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center ">
                        <button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#modal-route">
                            <span class="fas fa-plus mr-2"></span>Add Route
                        </button>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="route_code" class="my-1 mr-2">Code <span style="color : red;">*</span></label>
                                <input type="text" name="route_code" class="form-control" id="routeCodeTxt1" value="" data-readonly required>
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="route_name" class="my-1 mr-2">Name</label>
                                <input type="text" name="route_name" class="form-control" id="routeNameTxt1" value="" data-readonly>
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                        <input type="hidden" name="route_id" id="routeIdhdn">
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="km" class="my-1 mr-2">KM <span style="color : red;">*</span></label>
                                <input type="number" name="km" class="form-control" id="kmTxt" value="" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1" required>
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="m" class="my-1 mr-2">Meter <span style="color : red;">*</span></label>
                                <input type="number" name="m" class="form-control" id="mTxt" min="0" pattern="[0-9]" onkeypress="return !(event.charCode == 46)" step="1" value="" required>
                                <div class="invalid-feedback" style="display:block;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label class="my-1 mr-2" for="state">State <span style="color : red;">*</span></label>
                            <select class="form-select" onchange="loadDistrict();" name="state" id="stateSlt">
                                <option value="" selected>Please select</option>
                                @isset($states)
                                @foreach($states as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                            <div class="invalid-feedback" style="display: block;"></div>
                        </div>
                        <div class="col">
                            <label class="my-1 mr-2" for="district">District <span style="color : red;">*</span></label>
                            <select class="form-select" name="district" disabled id="districtSlt">
                                <option value="" selected>Please select</option>
                            </select>
                            <div class="invalid-feedback" style="display: block;"></div>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="idHdn">
                    <div class="pt-2 px-2">
                        <button class="btn btn-primary" type="submit"><span class="far fa-paper-plane mr-2"></span>Submit</button>
                        <a class="btn btn-primary" href="{{route('road.list')}}"><span class="fas fa-backward mr-2"></span>Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-route" tabindex="-1" role="dialog" aria-labelledby="modal-route" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Route Lookup</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!--
                <form class="mb-2" id="routeFrm">
                    @csrf
                    <div class="form-row">
                        <div class="col-auto my-1">
                            <label class="sr-only" for="route_code">Route Code</label>
                            <input type="text" name="code" class="form-control " id="routeCodeTxt" placeholder="Route Code">
                        </div>
                        <div class="col-auto my-1">
                            <label class="sr-only" for="route_code">Route Name</label>
                            <input type="text" name="name" class="form-control" id="routeNameTxt" placeholder="Route Name">
                        </div>
                        <div class="col-auto my-1">
                            <button type="button" onclick="searchRoute();" class="btn btn-primary"><span class="fa fa-search mr-2"></span>Search</button>
                        </div>
                    </div>
                </form>
                -->
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded" id="tableRoute">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">#</th>
                                <th class="border-0">Route Code</th>
                                <th class="border-0">Route Name</th>
                                <th class="border-0">Route Type</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyR">
                            <!-- Item -->
                            @foreach ($routes as $route)
                            <tr>
                                <td class="border-0"><input class="form-check-input" id="idRouteRdo" type="radio" name="routeid" value="{{ $route->id }}"></td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $route->code }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $route->name }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $route->type->name }}</div>
                                </td>
                            </tr>
                            @endforeach
                            <!-- End of Item -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" onclick="selectedItem();" class="btn btn-primary"><span class="fa fa-plus mr-2"></span>Add Route</button>
                    <button type="button" class="btn btn-link text-danger ml-auto" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('vendor/DataTables/datatables.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/DataTables-1.10.23/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('js/passage.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.validate-1.19.3.min.js')}}"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('#tableRoute').DataTable({
        "lengthMenu": [ 5, 10, 15, 20, 30, 35, 45, 50 ]
    });

    $('#menu-registration').removeClass("collapsed");
    $('#menu-registration').attr("aria-expanded", "true");
    $('#submenu-app-registration').addClass("show");
    $('#passageList').addClass("active");

    var validator = $('#roadForm').validate();

    @isset($road)
    $('#idHdn').val("{{$road->id}}");
    $('#passageNoTxt').val("{{$road->number}}");
    $('#passageTypeSlt').val("{{$road->type->id}}")
    @if($road->primary == "1")
    $('#passagePrimaryChk').attr("checked", "checked");
    @endif
    @isset($road->district)
    $('#stateSlt').val("{{$road->district->state_id}}").attr("selected", "selected");
    loadDistrict();
    setTimeout(function() {
        $('#districtSlt').val("{{$road->district->id}}").attr("selected", "selected");
    }, 1000);
    @endisset
    $('#routeCodeTxt1').val("{{$road->route->code}}");
    $('#routeNameTxt1').val("{{$road->route->name}}");
    $('#routeIdhdn').val("{{$road->route->id}}");
    $('#kmTxt').val("{{$road->kilometer}}");
    $('#mTxt').val("{{$road->meter}}");
    $('#roadForm').attr("action", "{{ route('update.road') }}")
    @endisset
} );
    function loadDistrict() {
        var input = $('#stateSlt').val();
        if (input) {
            $.get("{{route('lookup.district')}}", { "val": input }, function(data) {
                $('#districtSlt').empty();

                $('#districtSlt').append('<option value="" selected>Please select</option>');

                $('#districtSlt').rules("add",
                {
                    required: true,
                    messages: {
                        required: "District is required"
                    }
                });

                if (Array.isArray(data) && data.length) {


                    data.forEach(element => {
                        $('#districtSlt').append('<option value="' + element.id + '">' + element.name + '</option>');
                    });

                }
            });
            $('#districtSlt').prop('disabled', false);
        } else {
            $('#districtSlt').empty();
            $('#districtSlt').append('<option value="" selected>Please select</option>');
            $('#districtSlt').prop('disabled', true);
        }

    }
</script>
@endsection
