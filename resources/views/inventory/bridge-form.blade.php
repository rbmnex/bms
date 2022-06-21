@extends('layouts.main')

@section('head')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/DataTables/DataTables-1.10.23/css/dataTables.bootstrap4.min.css')}}" />
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Bridge Form</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="invalid-feedback" style="display: block;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="invalid-feedback" id="errDiv">
                    <ul>
                    </ul>
                </div>

                <form method="POST" id="bridgeForm" action="{{ route('save.bridge') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="bridgeid" id="bridgeid">
                    <div class="form-group">
                        <label for="bridge_name" class="my-1 mr-2">Bridge Name <span style="color : red;">*</span></label>
                        <input type="text" name="bridge_name" class="form-control" oninput="this.value = this.value.toUpperCase()" id="bridgeNameTxt" value="{{ old('bridge_name') }}">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label class="my-1 mr-2" for="state">Asset Type <span style="color : red;">*</span></label>
                            <select class="form-select" onchange="toogleRamp();" name="asset" id="assetSlt">
                                <option value="" selected>Please select</option>
                                @isset($assets)
                                @foreach($assets as $item)
                                <option value="{{$item->id}}" {{ old('asset') == $item->id ? 'selected="selected"' : '' }}>{{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                            <div class="invalid-feedback" style="display: block;"></div>
                        </div>
                        <div class="col">
                            <label class="my-1 mr-2" for="district">Ramp Type</label>
                            <select class="form-select" name="ramp" disabled id="rampSlt">
                                <option value="" selected>Please select</option>
                                @isset($ramps)
                                @foreach($ramps as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                                @endisset
                            </select>
                            <div class="invalid-feedback" style="display: block;"></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center ">
                        <button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#modal-passage">
                            <span class="fas fa-plus mr-2"></span>Add Road
                        </button>
                    </div>
                    <div class="row">
                        <div class="col form-group">
                            <label for="route_code" class="my-1 mr-2">Route No. <span style="color : red;">*</span></label>
                            <input type="text" name="route_code" class="form-control" id="routeCodeTxt" value="" data-readonly>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col form-group">
                            <label for="route_name" class="my-1 mr-2">Route Name</label>
                            <input type="text" name="route_name" class="form-control" id="routeNameTxt" value="" data-readonly>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3 form-group">
                            <label for="passage_no" class="my-1 mr-2">Number</label>
                            <input type="text" name="passage_no" class="form-control" id="passageNoTxt" value="" data-readonly>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-4 form-group">
                            <label for="passage_type" class="my-1 mr-2">Road Type</label>
                            <input type="text" name="passage_type" class="form-control" id="passageTypeTxt" value="" data-readonly>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-5 form-group ">
                            <label for="kilometer" class="my-1 mr-2">Over Bridge/Under Bridge?</label>
                            <input type="text" name="ou" class="form-control" id="passageOUTxt" value="" data-readonly>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3 pt-4 form-group">
                            <input class="form-check-input" type="checkbox" value="" id="passagePrimaryChk" onclick="return false;">
                            <label for="passage_primary" class="my-1 mr-2 ml">Primary Road?</label>
                            <div class="invalid-feedback"></div>
                        </div>
                        <!--

                        -->
                        <div class="col-9 form-group ">
                            <label for="kilometer" class="my-1 mr-2">Kilometer</label>
                            <input type="text" name="kilometer" class="form-control" id="kilometerTxt" value="" data-readonly>
                            <div class="invalid-feedback"></div>
                        </div>
                        <input type="hidden" name="passage_id" id="passageIdHdn">
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label class="my-1 mr-2" for="state">State <span style="color : red;">*</span></label>
                            <select class="form-select" onchange="loadDistrict();" name="state" id="stateSlt">
                                <option value="" selected>Please select</option>
                                @isset($states)
                                @foreach($states as $item)
                                <option value="{{$item->id}}" {{ old('state') == $item->id ? 'selected="selected"' : '' }}>{{$item->name}}</option>
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
                    <div class="form-group">
                        <label class="my-1 mr-2" for="remark">Remark</label>
                        <textarea class="form-control" name="remark_bridge" placeholder="" id="remarkTxt" rows="4" value="{{old('remark_bridge')}}">
                        </textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="nav-wrapper position-relative mb-2">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-text-1-tab" data-toggle="tab" href="#tabs-text-1" role="tab" aria-controls="tabs-text-1" aria-selected="true">
                                    Administrative
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-text-2-tab" data-toggle="tab" href="#tabs-text-2" role="tab" aria-controls="tabs-text-2" aria-selected="false">
                                    Technical
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-text-3-tab" data-toggle="tab" href="#tabs-text-3" role="tab" aria-controls="tabs-text-3" aria-selected="false">Road (Additional Info)</a>
                            </li>
                            <li class="nav-item"><a class="nav-link mb-sm-3 mb-md-0" id="tabs-text-4-tab" data-toggle="tab" href="#tabs-text-4" role="tab" aria-controls="tabs-text-4" aria-selected="false">Miscellaneous</a></li>
                            <li class="nav-item"><a class="nav-link mb-sm-3 mb-md-0" id="tabs-text-5-tab" data-toggle="tab" href="#tabs-text-5" role="tab" aria-controls="tabs-text-5" aria-selected="false">Acknowledgement</a></li>
                        </ul>
                    </div>
                    <div class="card border-0">
                        <div class="card-body p-0">
                            <div class="tab-content">
                                <div id="tabs-text-1" role="tabpanel" aria-labelledby="tabs-text-1-tab" class="tab-pane fade show active">
                                    <div class="row">
                                    <!--
                                        <div class="col form-group">
                                            <input class="form-check-input" type="checkbox" name="special_bridge" value="1" id="specialBridgeChk" {{ old('special_bridge') == '1' ? 'checked' : '' }}>
                                            <label for="special_bridge" class="my-1 mr-2 ml">Special Bridge?</label>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    -->
                                        <div class="col form-group">
                                            <label for="construction_year" class="my-1 mr-2">Year of Contruction / Reconstruction <span style="color : red;">*</span></label>
                                            <input type="number" name="construction_year" class="form-control" id="consYearTxt" value="{{old('construction_year')}}">
                                            <div class="invalid-feedback"></div>
                                            <input type="hidden" name="previousyear" id="previousTxt">
                                            <input type="hidden" name="yearid" id="yearIdHdn">
                                        </div>
                                        <div class="col form-group">
                                            <label class="my-1 mr-2" for="access_equipment">Access Equipment needed?</label>
                                            <select class="form-select" name="access_equipment" id="accessEquipSlt" aria-label="Default select example">
                                                <option value="" selected>Please select</option>
                                                @isset($equips)
                                                @foreach($equips as $equip)
                                                <option value="{{$equip->id}}" {{ old('access_equipment') == $equip->id ? 'selected="selected"' : '' }}>{{$equip->name}}</option>
                                                @endforeach
                                                @endisset
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="row">

                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="maintenance_date" class="my-1 mr-2">Maintenance Date</label>
                                                <input data-datepicker="" name="maintenance_date" class="form-control" id="mainDateTxt" value="{{old('maintenance_date')}}">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="maintenance_cost" class="my-1 mr-2">Maintenance Cost</label>
                                                <input type="number" name="maintenance_cost" class="form-control" id="mainCostTxt" value="{{old('maintenance_cost')}}">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    -->
                                    <div class="form-file mt-3">
                                        <input type="file" accept="image/gif, image/jpeg, image/png" onchange="readURL(this);" name="photo" class="form-file-input" id="bridgeFile">
                                        <label class="form-file-label" for="customFile">
                                            <span class="form-file-text">Choose bridge photo...</span>
                                            <span class="form-file-button">Browse</span>
                                        </label>
                                        <input type="hidden" name="photo_path" id="photoHdn">
                                        <img class="pt-1" id="imgBridge">
                                    </div>
                                    <input type="hidden" name="adminId" id="adminIdHdn">
                                </div>
                                <div id="tabs-text-2" role="tabpanel" aria-labelledby="tabs-text-2-tab" class="tab-pane fade">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="h6">Geometry</h6>
                                        </div>
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="spans_no">Number of spans <span style="color : red;">*</span></label>
                                                        <input type="number" name="spans_no" class="form-control" id="spansNoTxt" value="{{old('spans_no')}}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="span_min">Min span length (m) <span style="color : red;">*</span></label>
                                                        <input type="number" step="0.001" name="span_min" class="form-control" id="spanMinTxt" value="{{old('span_min')}}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="span_max">Max span length (m) <span style="color : red;">*</span></label>
                                                        <input type="number" step="0.001" name="span_max" class="form-control" id="spanMaxTxt" value="{{old('span_max')}}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="total_length">Total length (m) <span style="color : red;">*</span></label>
                                                        <input type="number" step="0.001" name="total_length" class="form-control" id="totalLengthTxt" onblur="calArea();" value="{{old('total_length')}}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="overall_width">Overall width (m) <span style="color : red;">*</span></label>
                                                        <input type="number" step="0.001" name="overall_width" class="form-control" id="overallWidthTxt" onblur="calArea();" value="{{old('overall_width')}}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="area">Area (m2)</label>
                                                        <input type="text" name="area" step="0.001" class="form-control" id="areaTxt" value="{{old('area')}}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="median_width">Width of Median (m)</label>
                                                        <input type="number" step="0.001" name="median_width" class="form-control" id="medianWidthTxt" value="{{old('median_width')}}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="carriageways_width">Width of Carriageways (m)</label>
                                                        <input type="number" step="0.001" name="carriageways_width" class="form-control" id="carriagewaysWidthTxt" value="{{old('carriageways_width')}}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="c2c_width">Width of Curb to Curb (m)</label>
                                                        <input type="number" step="0.001" name="c2c_width" class="form-control" id="c2cWidthTxt" value="{{old('median_width')}}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="approach_width">Width of Approach (m)</label>
                                                        <input type="number" step="0.001" name="approach_width" class="form-control" id="approachWidthTxt" value="{{old('approach_width')}}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="sidewalkl_width">Width of Sidewalk Left (m)</label>
                                                        <input type="number" step="0.001" name="sidewalkl_width" class="form-control" id="sidewalkl_widthWidthTxt" value="{{old('sidewalkl_width')}}">
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="sidewalkr_width">Width of Sidewalk Right (m)</label>
                                                        <input type="number" step="0.001" name="sidewalkr_width" class="form-control" id="sidewalkrWidthTxt" value="{{old('sidewalkr_width')}}">
                                                        </input>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="skew_angle">Skew Angle (deg) <span style="color : red;">*</span></label>
                                                        <input type="text" name="skew_angle" step="0.001" class="form-control" id="skewAngleTxt" value="{{old('skew_angle')}}"></input>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <input type="hidden" name="geometryId" id="geometryIdHdn">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="h6">Superstructure</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row pb-4">
                                                <div class="col">
                                                    <h6 class="h6">Main type:</h6>

                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="deck">Deck Type <span style="color : red;">*</span></label>
                                                        <select class="form-select" name="deck" id="deckSlt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($decks)
                                                            @foreach($decks as $deck)
                                                            <option value="{{$deck->id}}" {{old('deck') == $deck->id ? 'checked="checked"' : ''}}>{{$deck->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="system">System Type <span style="color : red;">*</span></label>
                                                        <select class="form-select" name="system" id="systemSlt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($systems)
                                                            @foreach($systems as $system)
                                                            <option value="{{$system->id}}" {{old('system') == $system->id ? 'checked="checked"' : ''}}>{{$system->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="material">Material of Primary Members <span style="color : red;">*</span></label>
                                                        <select class="form-select" name="material" id="materialSlt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($materials)
                                                            @foreach($materials as $material)
                                                            <option value="{{$material->id}}" {{old('material') == $material->id ? 'checked="checked"' : ''}}>
                                                            {{$material->name}}
                                                            </option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h6 class="h6">Secondary type (if any):</h6>

                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="deck2">Deck Type</label>
                                                        <select class="form-select" name="deck2" id="deck2Slt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($decks)
                                                            @foreach($decks as $deck)
                                                            <option value="{{$deck->id}}" {{old('deck2') == $deck->id ? 'checked="checked"' : ''}}>{{$deck->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="system2">System Type</label>
                                                        <select class="form-select" name="system2" id="system2Slt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($systems)
                                                            @foreach($systems as $system)
                                                            <option value="{{$system->id}}" {{old('system2') == $system->id ? 'checked="checked"' : ''}}>{{$system->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="material2">Material of Primary Members</label>
                                                        <select class="form-select" name="material2" id="material2Slt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($materials)
                                                            @foreach($materials as $material)
                                                            <option value="{{$material->id}}" {{old('material2') == $material->id ? 'checked="checked"' : ''}}>{{$material->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="superstructureId" id="superstructureIdHdn">
                                                <input type="hidden" name="superstructureId2" id="superstructureId2Hdn">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="h6">Substructure</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row pb-4">
                                                <div class="col">
                                                    <h6 class="h6">Abutment:</h6>

                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="abutment_type">Type <span style="color : red;">*</span></label>
                                                        <select class="form-select" name="abutment_type" id="abutmentTypeSlt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($abudments)
                                                            @foreach($abudments as $item)
                                                            <option value="{{$item->id}}" {{old('abutment_type') == $item->id ? 'checked="checked"' : ''}}>{{$item->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="abutment_material">Material <span style="color : red;">*</span></label>
                                                        <select class="form-select" name="abutment_material" id="abutmentMaterialSlt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($materials)
                                                            @foreach($materials as $material)
                                                            <option value="{{$material->id}}" {{old('abutment_material') == $material->id ? 'checked="checked"' : ''}}>{{$material->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="abutment_foundation">Foundation <span style="color : red;">*</span></label>
                                                        <select class="form-select" name="abutment_foundation" id="abutmentFoundationSlt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($foundations)
                                                            @foreach($foundations as $item)
                                                            <option value="{{$item->id}}" {{old('abutment_foundation') == $item->id ? 'checked="checked"' : ''}}>{{$item->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h6 class="h6">Pier:</h6>

                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="pier_type">Type</label>
                                                        <select class="form-select" name="pier_type" id="pierTypeSlt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($piers)
                                                            @foreach($piers as $item)
                                                            <option value="{{$item->id}}" {{old('pier_type') == $item->id ? 'checked="checked"' : ''}}>{{$item->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="pier_material">Material</label>
                                                        <select class="form-select" name="pier_material" id="pierMaterialSlt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($materials)
                                                            @foreach($materials as $material)
                                                            <option value="{{$material->id}}" {{old('pier_material') == $material->id ? 'checked="checked"' : ''}}>{{$material->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="pier_foundation">Foundation</label>
                                                        <select class="form-select" name="pier_foundation" id="pierFoundationSlt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($foundations)
                                                            @foreach($foundations as $item)
                                                            <option value="{{$item->id}}" {{old('pier_foundation') == $item->id ? 'checked="checked"' : ''}}>{{$item->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="substructureId" id="substructureIdHdn">
                                            <input type="hidden" name="substructureId2" id="substructureId2Hdn">
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="h6">Bearings</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="fixed_abutment">Type of fixed bearings on abutment</label>
                                                        <select class="form-select" name="fixed_abutment" id="fixedAbutmentSlt" aria-label=>
                                                            <option value="" selected>Please select</option>
                                                            @isset($bearingLists)
                                                            @foreach($bearingLists as $item)
                                                            <option value="{{$item->id}}" {{old('fixed_abutment') == $item->id ? 'checked="checked"' : ''}}>{{$item->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="free_abutment">Type of free bearings on abutment</label>
                                                        <select class="form-select" name="free_abutment" id="freeAbutmentSlt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($bearingLists)
                                                            @foreach($bearingLists as $item)
                                                            <option value="{{$item->id}}" {{old('free_abutment') == $item->id ? 'checked="checked"' : ''}}>{{$item->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="fixed_pier">Type of fixed bearings on pier</label>
                                                        <select class="form-select" name="fixed_pier" id="fixedPierSlt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($bearingLists)
                                                            @foreach($bearingLists as $item)
                                                            <option value="{{$item->id}}" {{old('fixed_pier') == $item->id ? 'checked="checked"' : ''}}>{{$item->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="my-1 mr-2" for="free_pier">Type of free bearings on pier</label>
                                                        <select class="form-select" name="free_pier" id="freePierSlt">
                                                            <option value="" selected>Please select</option>
                                                            @isset($bearingLists)
                                                            @foreach($bearingLists as $item)
                                                            <option value="{{$item->id}}" {{old('free_pier') == $item->id ? 'checked="checked"' : ''}}>{{$item->name}}</option>
                                                            @endforeach
                                                            @endisset
                                                        </select>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="bearingId" id="bearingIdHdn">
                                                <input type="hidden" name="bearingId2" id="bearing2IdHdn">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="h6">Other Elements</h6>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="h6">Details:</h6>
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="parapet">Type of Parapet</label>
                                                <select class="form-select" name="parapet" id="parapetSlt">
                                                    <option value="" selected>Please select</option>
                                                    @isset($parapets)
                                                    @foreach($parapets as $item)
                                                    <option value="{{$item->id}}" {{old('parapet') == $item->id ? 'checked="checked"' : ''}}>{{$item->name}}</option>
                                                    @endforeach
                                                    @endisset
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="wearing_surface">Type of Wearing Surface</label>
                                                <select class="form-select" name="wearing_surface" id="wearingSurfaceSlt">
                                                    <option value="" selected>Please select</option>
                                                    @isset($wearings)
                                                    @foreach($wearings as $item)
                                                    <option value="{{$item->id}}" {{old('wearing_surface') == $item->id ? 'checked="checked"' : ''}}>{{$item->name}}</option>
                                                    @endforeach
                                                    @endisset
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group pb-4">
                                                <label class="my-1 mr-2" for="expansion_joint">Type of Expansion Joint</label>
                                                <select class="form-select" name="expansion_joint" id="expansionJointSlt">
                                                    <option value="" selected>Please select</option>
                                                    @isset($expansions)
                                                    @foreach($expansions as $item)
                                                    <option value="{{$item->id}}" {{old('expansion_joint') == $item->id ? 'checked="checked"' : ''}}>{{$item->name}}</option>
                                                    @endforeach
                                                    @endisset
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <h6 class="h6">Signs</h6>
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="max_load">Max Load (tonnes)</label>
                                                <input type="number" name="max_load" class="form-control" id="maxLoadTxt" value="{{old('max_load')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="other_element">Other</label>
                                                <textarea type="text" rows="4" name="other_element" class="form-control" id="otherBTxt" value="{{old('other_element')}}">
                                                </textarea>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="elementId" id="elementIdHdn">
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="h6">Services</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="tnb" type="checkbox" value="1" id="tnbChk" {{old('tnb') == '1' ? 'checked' : ''}}>
                                                        <label for="tnb" class="my-1 mr-2 ml">TNB Cables?</label>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="telekom" type="checkbox" value="1" id="telecomChk" {{old('telekom') == '1' ? 'checked' : ''}}>
                                                        <label for="telekom" class="my-1 mr-2 ml">Telecom Cables?</label>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="watermain" type="checkbox" value="1" id="watermainChk" {{old('watermain') == '1' ? 'checked' : ''}}>
                                                        <label for="watermain" class="my-1 mr-2 ml">Watermain?</label>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="lighting" type="checkbox" value="1" id="lightingChk" {{old('lighting') == '1' ? 'checked' : ''}}>
                                                        <label for="lighting" class="my-1 mr-2 ml">Lighting?</label>
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="other_services">Other</label>
                                                <textarea type="text" rows="4" name="other_services" class="form-control" id="otherSTxt" value="{{old('other_services')}}">
                                                </textarea>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <input type="hidden" name="serviceId" id="serviceIdHdn">
                                        </div>
                                    </div>
                                </div>
                                <div id="tabs-text-3" role="tabpanel" aria-labelledby="tabs-text-3-tab" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="design_load">Design Load (kN)</label>
                                                <input type="text" name="design_load" oninput="this.value = this.value.toUpperCase()" class="form-control" id="designLoadTxt" value="{{old('design_load')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="design_code">Design Code</label>
                                                <input type="text" name="design_code" oninput="this.value = this.value.toUpperCase()" class="form-control" id="designCodeTxt" value="{{old('design_code')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="capacity">Capacity</label>
                                                <select class="form-select" name="capacity" id="capacitySlt" aria-label="Default select example">
                                                    <option value="" selected>Please select</option>
                                                    @isset($capacities)
                                                    @foreach($capacities as $item)
                                                    <option value="{{$item->id}}" {{old('capacity') == $item->id ? 'selected="selected"' : ''}}>{{$item->name}}</option>
                                                    @endforeach
                                                    @endisset
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group pb-2">
                                                <label class="my-1 mr-2" for="discounted_capacity">Discounted Capacity (SV)</label>
                                                <input type="text" oninput="this.value = this.value.toUpperCase()" name="discounted_capacity" class="form-control" id="discountedCapacityTxt" value="{{old('discounted_capacity')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="h6">Vertical Clearance (m)</h6>
                                    <div class="form-group">
                                    <!--    <label class="my-1 mr-2" for="vc"></label> -->
                                        <input type="number" step="0.001" name="o" class="form-control" id="oTxt" value="{{old('o')}}">
                                        </input>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <!--
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="l">Left</label>
                                                <input type="number" step="0.001" name="l" class="form-control" id="lTxt" value="{{old('l')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="lm">Left Middle</label>
                                                <input type="number" step="0.001" name="lm" class="form-control" id="lmTxt" value="{{old('lm')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="rm">Right Middle</label>
                                                <input type="number" step="0.001" name="rm" class="form-control" id="rmTxt" value="{{old('rm')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="r">Right</label>
                                                <input type="number" step="0.001" name="r" class="form-control" id="rTxt" value="{{old('r')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    -->
                                    <input type="hidden" name="roadId" id="roadIdHdn">
                                </div>
                                <div id="tabs-text-4" role="tabpanel" aria-labelledby="tabs-text-4-tab" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="owner">Owner</label>
                                                <input type="text" name="owner" oninput="this.value = this.value.toUpperCase()" class="form-control" id="ownerTxt" value="{{old('owner')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="designer">Designer</label>
                                                <input type="text" name="designer" oninput="this.value = this.value.toUpperCase()" class="form-control" id="designerTxt" value="{{old('designer')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="inpection_responsible">Inspection Responsible</label>
                                                <input type="text" name="inpection_responsible" oninput="this.value = this.value.toUpperCase()" class="form-control" id="inpectionResponsibleTxt" value="{{old('inpection_responsible')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="maintenance_department">Maintenance Department</label>
                                                <input type="text" name="maintenance_department" oninput="this.value = this.value.toUpperCase()" class="form-control" id="maintenanceDepartmentTxt" value="{{old('maintenance_department')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="coordinatex">Latitude <span style="color : red;">*</span><span class="fa fa-question-circle ml-2" data-toggle="tooltip" data-placement="top" title="e.g 3.1536174"></span></label>
                                                <input type="text"  name="coordinatex" class="form-control" id="coordinateXTxt" value="{{old('coordinatex')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="coordinatey">Longitude <span style="color : red;">*</span><span class="fa fa-question-circle ml-2" data-toggle="tooltip" data-placement="top" title="e.g 101.6899371"></span></label>
                                                <input type="text" name="coordinatey" class="form-control" id="coordinateYTxt" value="{{old('coordinatey')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="form-group">
                                        <label class="my-1 mr-2" for="accident_record">Accident Record (POL)</label>
                                        <input type="text" name="accident_record" class="form-control" id="accidentRecordTxt" value="">
                                        </input>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="my-1 mr-2" for="flood_level">Flood Level</label>
                                        <input type="text" name="flood_level" class="form-control" id="floodLevelTxt" value="">
                                        </input>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    -->
                                    <input type="hidden" name="miscellaneousId" id="miscellaneousIdHdn">
                                </div>
                                <div id="tabs-text-5" role="tabpanel" aria-labelledby="tabs-text-5-tab" class="tab-pane fade">
                                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center ">
                                        <button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#modal-user">
                                            <span class="fas fa-plus mr-2"></span>Assign User
                                        </button>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="name">Name <span style="color : red;">*</span></label>
                                                <input type="text" name="name_user" class="form-control" id="nameTxt" value="{{old('name_user')}}" data-readonly>
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="position">Position</label>
                                                <input type="text" name="position" class="form-control" id="positionTxt" value="{{old('position')}}" data-readonly>
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <!--
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="department">Department</label>
                                                <input type="text" name="department" class="form-control" id="departmentTxt" value="{{old('department')}}" data-readonly>
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    -->
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="phone">Phone</label>
                                                <input type="text" name="phone" class="form-control" id="phoneTxt" value="{{old('phone')}}" data-readonly>
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="office">Office</label>
                                                <input type="text" name="office" class="form-control" id="officeTxt" value="{{old('office')}}" data-readonly>
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="my-1 mr-2" for="email">Email <span style="color : red;">*</span></label>
                                                <input type="text" name="email" class="form-control" id="emailTxt" value="{{old('email')}}">
                                                </input>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="user_id" id="user_id">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="task_id" id="taskTxt" />
                    <input type="hidden" name="previous" value={{isset($previous) ? $previous : route('bridge.form') }}/>
                    <input type="hidden" name="add" id="{{isset($add) ? $add : ''}}" />
                    @isset($comments)
                    <hr>
                    @foreach($comments as $comment)
                    <div class="row">
                    <blockquote class="blockquote">
                        <p class="mb-3">{{$comment->comment}}</p>
                        <footer class="blockquote-footer">{{$comment->user->name}} <cite title="Source Title">{{$comment->created_at}}</cite></footer>
                    </blockquote>
                    </div>
                    @endforeach
                    @endisset
                    <div class="pt-2 px">
                        <button class="btn btn-primary" id="submitBtn" type="submit"><span class="far fa-paper-plane mr-2"></span>Submit</button>
                        <button class="btn btn-primary" id="saveBtn" onclick="saveInfo();" type="button"><span class="far fa-paper-plane mr-2"></span>Save</button>
                        @isset($year)
                        @if($year->status == 'ONHOLD')
                        <button class="btn btn-primary" id="deleteBtn" onclick="deleteInfo();" type="button"><span class="fa fa-trash mr-2"></span>Delete</button>
                        @endif
                        @endisset
                        <button class="btn btn-primary" id="backBtn" onclick="history.back();" type="button"><span class="fas fa-backward mr-2"></span>Back</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- modal search passage -->
<div class="modal fade" id="modal-passage" tabindex="-1" role="dialog" aria-labelledby="modal-passage" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Road Lookup</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded" id="tableRoadList">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">#</th>
                                <th class="border-0">Route Code</th>
                                <th class="border-0">Route Name</th>
                                <th class="border-0">Section Number</th>
                                <th class="border-0">Road Type</th>
                                <th class="border-0">Distance</th>
                                <th class="border-0">Primary?</th>
                                <th class="border-0">Over Bridge/Under Bridge</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyPassage">
                            <!-- Item -->
                            @foreach($passages as $roaditem)
                            <tr>
                                <td class="border-0"><input class="form-check-input" type="radio" name="idRoadRdo" value="{{ $roaditem->id.'-'.(isset($roaditem->district_id) ? $roaditem->district_id : '0').'-'.(isset($roaditem->state_id) ? $roaditem->state_id : '0')}}"></td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $roaditem->code }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $roaditem->name }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $roaditem->number }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $roaditem->type }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $roaditem->km . "." . str_pad($roaditem->meter, 3, "0", STR_PAD_RIGHT) }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ ($roaditem->primary == "1") ? 'Yes' : 'No' }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ ($roaditem->ou == 'OB') ? 'Over Bridge' : 'Under Bridge' }}</div>
                                </td>
                            </tr>
                            @endforeach
                            <!-- End of Item -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" onclick="selectedRoad()" class="btn btn-primary"><span class="fa fa-plus mr-2"></span>Add Road</button>
                <button type="button" class="btn btn-link text-danger ml-auto" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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
                            <!-- Item -->
                            @isset($users)
                            @foreach($users as $user)
                            <tr>
                                <td class="border-0"><input class="form-check-input" type="radio" name="idUserRdo" value="{{ $user->id }}"></td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $user->name }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $user->position }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $user->department }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $user->office }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $user->tel_no }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $user->email }}</div>
                                </td>
                            </tr>
                            @endforeach
                            @endisset
                            <!-- End of Item -->
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
<script src="{{asset('vendor/DataTables/datatables.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/DataTables-1.10.23/js/dataTables.bootstrap4.min.js')}}"></script>

<script src="{{asset('vendor/vanillajs-datepicker/dist/js/datepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.validate-1.19.3.min.js')}}"></script>

<script src="{{asset('js/bridge.js')}}"></script>
<script>
    @isset($year)
@if($year->status == 'ONHOLD')
    function deleteInfo() {
        $('#nameTxt').rules('remove');
        $('#emailTxt').rules('remove');
        $('#bridgeForm').attr("action", "{{route('delete.bridge')}}");
        $('#bridgeForm')[0].submit();
    }
@endif
@endisset
    function saveInfo() {
        $('#nameTxt').rules('remove');
        $('#emailTxt').rules('remove');
        $('#bridgeForm').attr("action", "{{route('hold.bridge')}}");
        $('#bridgeForm')[0].submit();
    }
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
    $(document).ready(function() {
        $('#menu-inventory').removeClass("collapsed");
        $('#menu-inventory').attr("aria-expanded", "true");
        $('#submenu-app-inventory').addClass("show");

        $('#tableRoadList').DataTable({
            "lengthMenu": [5, 10, 20],
            "searching": true
        });
        $('#tableUsersList').DataTable({
            "lengthMenu": [5, 10, 20]
        });

        var validator = $('#bridgeForm').validate({
            ignore: "",
            errorContainer: "#errDiv",
            errorLabelContainer: "#errDiv ul",
            wrapper: "li",
            focusInvalid: false,
            invalidHandler: function(form, validator) {

                if (!validator.numberOfInvalids())
                    return;

                $('html, body').animate({
                    scrollTop: $(validator.errorList[0].element).offset().top
                }, 1000);

            },
            rules : {
                bridge_name : {
                    required : true,
                    minlength : 10
                },
                asset : {
                    required : true
                },
                state : {
                    required : true
                },
                district : {
                    required : true
                },
                construction_year : {
                    required : true,
                    digits : true
                },
                spans_no : {
                    required : true,
                    digits : true
                },
                span_min : {
                    required : true,
                    number : true
                },
                span_max : {
                    required : true,
                    number : true
                },
                total_length : {
                    required : true,
                    number : true
                },
                overall_width : {
                    required : true,
                    number : true
                },
                skew_angle : {
                    required : true,
                    number : true
                },
                deck : {
                    required : true
                },
                system : {
                    required : true
                },
                material : {
                    required : true
                },
                abutment_type : {
                    required : true
                },
                abutment_material : {
                    required : true
                },
                abutment_foundation : {
                    required : true
                },
                coordinatex : {
                    required : true
                },
                coordinatey : {
                    required : true
                },
                name_user : {
                    required : true
                },
                email : {
                    required : true,
                    email : true
                },
                route_code : {
                    required : true
                }
            },
            messages : {
                bridge_name : {
                    required : "Bridge Name is required",
                    minlength : "Bridge need more than 10 characters"
                },
                asset : {
                    required : "Asset Type is required"
                },
                state : {
                    required : "State is required"
                },
                district : {
                    required : "District is required"
                },
                construction_year : {
                    required : "Construction Year is required",
                    digits : "Please input valid year in Construction Year"
                },
                spans_no : {
                    required : "Number of Spans is required",
                    digits : "Please input valid number in Number of Spans"
                },
                span_min : {
                    required : "Min Span Length is required",
                    number : "Please input valid number in Min Span Length"
                },
                span_max : {
                    required : "Max Span Length is required",
                    number : "Please input valid number in Max Span Length"
                },
                total_length : {
                    required : "Total Length is required",
                    number : "Please input valid number in Total Length"
                },
                overall_width : {
                    required : "Overall Width is required",
                    number : "Please input valid number in Overall Width"
                },
                skew_angle : {
                    required : "Skew Angle is required",
                    number : "Please input valid number in Skew Angle"
                },
                deck : {
                    required : "Main's Deck is required"
                },
                system : {
                    required : "Main's System is required"
                },
                material : {
                    required : "Main's Material of Primary Members is required"
                },
                abutment_type : {
                    required : "Abutment Type is required"
                },
                abutment_material : {
                    required : "Abutment Material is required"
                },
                abutment_foundation : {
                    required : "Abutment Foundation is required"
                },
                coordinatex : {
                    required : "Latitude is required",
                    number : "Please input valid value in Latitude"
                },
                coordinatey : {
                    required : "Longitude is required",
                    number : "Please input valid value in Longitude"
                },
                name_user : {
                    required : "Please Assign a User to procced"
                },
                email : {
                    required : "Email is required",
                    email : "Please input valid email"
                },
                route_code : {
                    required : "Road Information is required"
                }
            }
        });

        @if (session('message'))
        $('#modal-msg').modal('show');
        @endif

        const elem = document.getElementById('mainDateTxt');
        const datepicker = new Datepicker(elem, {
        // ...options
            format : 'yyyy-mm-dd'
        });



    });
    @if(count($users) === 1)
        @php
        $user = $users->first();
        @endphp

        $('#user_id').val("{{$user->id}}");
        $('#nameTxt').val("{{$user->name}}");
        $('#positionTxt').val("{{ isset($user->position) ?  $user->position : ''}}");
        //$('#departmentTxt').val(selector.find('td:eq(3)').find('div').html());
        $('#phoneTxt').val("{{$user->tel_no}}");
        $('#officeTxt').val("{{ $user->office }}");
        $('#emailTxt').val("{{$user->email}}");
        @endif

    $('#backBtn').hide();
    @isset($bridge)
    $('#bridgeForm').attr("action", "{{$action}}");
    $('#bridgeid').val("{{ $bridge-> id }}");
    $('#bridgeNameTxt').val("{{$bridge->name}}");
    $('#assetSlt').val("{{$bridge->asset->id}}").attr("selected", "selected");
    @isset($task)
    $('#taskTxt').val("{{$task}}")
    @endisset
    $('#backBtn').show();
    // $('#submitBtn').text("Verify");
    @isset($bridge->ramp)
    $('#rampSlt').attr('disabled', true);
    $('#rampSlt').val("{{$bridge->ramp->id}}").attr("selected", "selected");
    @endisset
    $('#remarkTxt').val("{{$bridge->remark}}");
    @endisset
    @isset($road)
    $('#routeCodeTxt').val("{{$road->route->code}}");
    $('#routeNameTxt').val("{{$road->route->name}}");
    $('#passageNoTxt').val("{{$road->number}}");
    $('#passageTypeTxt').val("{{$road->type->name}}");
    @if($road->primary == '1')
    $('#passagePrimaryChk').attr("checked", "checked");
    @endif
    $('#kilometerTxt').val("{{$road->kilometer.'.'.str_pad($road->meter, 3, '0', STR_PAD_RIGHT)}}");
    $('#passageOUTxt').val("{{($road->ou == 'UB') ? 'Under Bridge' : 'Over Bridge'}}");
    $('#passageIdHdn').val("{{$road->id}}");
    @endisset
    @isset($district)
    $('#stateSlt').val("{{$district->state->id}}").attr("selected", "selected");
    loadDistrict();
    setTimeout(function() {
        $('#districtSlt').val("{{$district->id}}").attr("selected", "selected");
    }, 3000);
    @endisset
    @isset($year)
    @empty($add)
    $('#consYearTxt').val("{{$year->year}}");
    @endempty
    $('#previousTxt').val("{{$year->year}}");
    $('#yearIdHdn').val("{{$year->id}}");
    @endisset
    @isset($administrative)
    $('#adminIdHdn').val("{{$administrative->id}}");
    @if($administrative->special == '1')
    $('#specialBridgeChk').attr("checked", "checked");
    @endif
    $('#accessEquipSlt').val("{{$administrative->access_equipment_id}}");
    $('#mainDateTxt').val("{{$administrative->maintenance_date}}");
    $('#mainCostTxt').val("{{$administrative->maintenance_cost}}");
    @empty($add)
    $('#imgBridge').attr("src", "{{isset($administrative->photo_path) ? URL::asset('storage/bridge/'.$administrative->photo_path) : ''}}");
    @endempty
    $('#photoHdn').val("{{$administrative->photo_path}}");
    @endisset
    @isset($geometry)
    $('#geometryIdHdn').val("{{$geometry->id}}");
    $('#spansNoTxt').val("{{$geometry->spans_no}}");
    $('#spanMinTxt').val("{{$geometry->min_span }}");
    $('#spanMaxTxt').val("{{$geometry->max_span}}");
    $('#totalLengthTxt').val("{{$geometry->total_length }}");
    $('#overallWidthTxt').val("{{$geometry->overall_width}}");
    $('#areaTxt').val("{{$geometry->total_length*$geometry->overall_width}}");
    $('#medianWidthTxt').val("{{$geometry->median_width }}");
    $('#carriagewaysWidthTxt').val("{{$geometry->carriageways_width}}");
    $('#c2cWidthTxt').val("{{$geometry->crub_width}}");
    $('#approachWidthTxt').val("{{$geometry->approach_width}}");
    $('#sidewalkl_widthWidthTxt').val("{{$geometry->sidewalk_left }}");
    $('#sidewalkrWidthTxt').val("{{$geometry->sidewalk_right}}");
    $('#skewAngleTxt').val("{{$geometry->skew_angle}}");
    @endisset
    @isset($superstructures)
    @foreach($superstructures as $item)
    @if($item->type == '1')
    $('#superstructureIdHdn').val("{{$item->id}}");
    $('#deckSlt').val("{{$item->deck_id}}").attr("selected", "selected");
    $('#systemSlt').val("{{$item->system_id}}").attr("selected", "selected");
    $('#materialSlt').val("{{$item->material_id}}").attr("selected", "selected");
    @else
    $('#superstructureId2Hdn').val("{{$item->id}}");
    $('#deck2Slt').val("{{$item->deck_id}}").attr("selected", "selected");
    $('#system2Slt').val("{{$item->system_id}}").attr("selected", "selected");
    $('#material2Slt').val("{{$item->material_id}}").attr("selected", "selected");
    @endif
    @endforeach
    @endisset
    @isset($substructures)
    @foreach($substructures as $item)
    @if($item->structure_type == 'Abutment')
    $('#substructureIdHdn').val("{{$item->id}}")
    $('#abutmentTypeSlt').val("{{$item->type_id}}").attr("selected", "selected");
    $('#abutmentMaterialSlt').val("{{$item->material_id}}").attr("selected", "selected");
    $('#abutmentFoundationSlt').val("{{$item->foundation_id}}").attr("selected", "selected");
    @else
    $('#substructureId2Hdn').val("{{$item->id}}")
    $('#pierTypeSlt').val("{{$item->type_id}}").attr("selected", "selected");
    $('#pierMaterialSlt').val("{{$item->material_id}}").attr("selected", "selected");
    $('#pierFoundationSlt').val("{{$item->foundation_id}}").attr("selected", "selected");
    @endif
    @endforeach
    @endisset
    @isset($bearings)
    @foreach($bearings as $item)
    @if($item->structure_type == 'Abutment')
    $('#bearingIdHdn').val("{{$item->id}}");
    $('#fixedAbutmentSlt').val("{{$item->fixed_id}}").attr("selected", "selected");
    $('#freeAbutmentSlt').val("{{$item->free_id}}").attr("selected", "selected");
    @else
    $('#bearingId2Hdn').val("{{$item->id}}");
    $('#fixedPierSlt').val("{{$item->fixed_id}}").attr("selected", "selected");
    $('#freePierSlt').val("{{$item->free_id}}").attr("selected", "selected");
    @endif
    @endforeach
    @endisset
    @isset($element)
    $('#elementIdHdn').val("{{$element->id}}");
    $('#parapetSlt').val("{{$element->parapet_id}}").attr("selected", "selected");
    $('#wearingSurfaceSlt').val("{{$element->wearing_surface_id}}").attr("selected", "selected");
    $('#expansionJointSlt').val("{{$element->expansion_joint_id}}").attr("selected", "selected");
    $('#maxLoadTxt').val("{{$element->max_load}}");
    $('#otherBTxt').val("{{$element->other}}");
    @endisset
    @isset($service)
    $('#serviceIdHdn').val("{{$service->id}}");
    @if($service->tnb_cables == '1')
    $('#tnbChk').attr("checked", "checked");
    @endif
    @if($service->telecom_cables == '1')
    $('#telecomChk').attr("checked", "checked");
    @endif
    @if($service->watermain == '1')
    $('#watermainChk').attr("checked", "checked");
    @endif
    @if($service->lighting == '1')
    $('#lightingChk').attr("checked", "checked");
    @endif
    $('#otherSTxt').val("{{$service->other}}");
    @endisset
    @isset($passage)
    $('#roadIdHdn').val("{{$passage->id}}");
    $('#designLoadTxt').val("{{$passage->design_load}}");
    $('#designCodeTxt').val("{{$passage->design_code}}");
    $('#capacitySlt').val("{{$passage->capacity_id}}").attr("selected", "selected");
    $('#discountedCapacityTxt').val("{{$passage->discounted_capacity}}");
    //$('#lTxt').val("{{$passage->vertical_clearance_l}}");
    //$('#lmTxt').val("{{$passage->vertical_clearance_lm}}");
    //$('#rmTxt').val("{{$passage->vertical_clearance_rm}}");
    //$('#rTxt').val("{{$passage->vertical_clearance_r}}");
    $('#oTxt').val("{{$passage->vertical_clearance_o}}");
    @endisset
    @isset($miscellaneous)
    $('#miscellaneousIdHdn').val("{{$miscellaneous->id}}");
    $('#ownerTxt').val("{{$miscellaneous->owner}}");
    $('#designerTxt').val("{{$miscellaneous->designer}}");
    $('#inpectionResponsibleTxt').val("{{$miscellaneous->inspection_responsible}}");
    $('#maintenanceDepartmentTxt').val("{{$miscellaneous->maintenance_dept}}");
    $('#coordinateXTxt').val("{{$miscellaneous->coordinate_x}}");
    $('#coordinateYTxt').val("{{$miscellaneous->coordinate_y}}");
    $('#accidentRecordTxt').val("{{$miscellaneous->accident_record}}");
    $('#floodLevelTxt').val("{{$miscellaneous->flood_level}}");
    @endisset
</script>
@endsection
