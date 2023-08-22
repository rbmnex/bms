@extends('layouts.main')

@section('head')

@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Bridge Form</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="structure_no" class="my-1 mr-2">Structure No.</label>
                            <input type="text" name="structure_no" class="form-control" id="structureNoTxt" value="{{ $bridge->structure_no }}" data-readonly>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="bridge_name" class="my-1 mr-2">Bridge Name</label>
                            <input type="text" name="bridge_name" class="form-control" id="bridgeNameTxt" value="{{ $bridge->name }}" data-readonly>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <label class="my-1 mr-2" for="state">Asset Type</label>
                        <input type="text" name="bridge_asset" class="form-control" id="bridgeAssetTxt" value="{{ $bridge->asset->name }}" data-readonly>
                        <div class="invalid-feedback" style="display: block;"></div>
                    </div>
                    <div class="col">
                        <label class="my-1 mr-2" for="Ramp">Ramp Type</label>
                        <input type="text" name="ramp" class="form-control" id="rampTxt" value="{{ isset($bridge->ramp) ? $bridge->ramp->name : '' }}" data-readonly>
                        <div class="invalid-feedback" style="display: block;"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col form-group">
                        <label for="route_code" class="my-1 mr-2">Route Code</label>
                        <input type="text" name="route_code" class="form-control" id="routeCodeTxt" value="{{ $road->route->code }}" data-readonly>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col form-group">
                        <label for="route_name" class="my-1 mr-2">Route Name</label>
                        <input type="text" name="route_name" class="form-control" id="routeNameTxt" value="{{ $road->route->name }}" data-readonly>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 form-group">
                        <label for="passage_no" class="my-1 mr-2">Number</label>
                        <input type="text" name="passage_no" class="form-control" id="passageNoTxt" value="{{ $road->number }}" data-readonly>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-9 form-group">
                        <label for="passage_type" class="my-1 mr-2">Road Type</label>
                        <input type="text" name="passage_type" class="form-control" id="passageTypeTxt" value="{{ $road->type->name }}" data-readonly>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 form-group">
                        <label for="passage_primary" class="my-1 mr-2 ml">Primary Road?</label>
                        <input type="text" name="primary" class="form-control" id="primaryTxt" value="{{ ($road->primary == '1') ? 'Yes' : 'No' }}" data-readonly>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-5 form-group ">
                        <label for="kilometer" class="my-1 mr-2">Over Bridge/Under Bridge?</label>
                        <input type="text" name="ou" class="form-control" id="passageOUTxt" value="{{ ($road->ou == 'OB') ? 'Over Bridge' : 'Under Bridge' }}" data-readonly>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-4 form-group ">
                        <label for="kilometer" class="my-1 mr-2">Kilometer</label>
                        <input type="text" name="kilometer" class="form-control" id="kilometerTxt" value="{{ $road->kilometer.'.'.str_pad($road->meter, 3, '0', STR_PAD_RIGHT)}}" data-readonly>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col">
                        <label class="my-1 mr-2" for="state">State</label>
                        <input type="text" name="state" class="form-control" id="stateTxt" value="{{ $district->state->name }}" data-readonly>
                        <div class="invalid-feedback" style="display: block;"></div>
                    </div>
                    <div class="col">
                        <label class="my-1 mr-2" for="district">District</label>
                        <input type="text" name="district" class="form-control" id="districtTxt" value="{{ $bridge->district->name }}" data-readonly>
                        <div class="invalid-feedback" style="display: block;"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="my-1 mr-2" for="remark">Remark</label>
                    <textarea class="form-control" name="remark_bridge" id="remarkTxt" rows="4" data-readonly>
                    {{ $bridge->remark }}
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
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-text-4-tab" data-toggle="tab" href="#tabs-text-4" role="tab" aria-controls="tabs-text-4" aria-selected="false">Miscellaneous</a>
                        </li>
                    </ul>
                </div>
                <!--
                <div class="card border-0">
                    <div class="card-body p-0"> -->
                <div class="tab-content">
                    <div id="tabs-text-1" role="tabpanel" aria-labelledby="tabs-text-1-tab" class="tab-pane fade show active">
                        <div class="row">
                            <div class="col form-group">
                                <label for="special_bridge" class="my-1 mr-2 ml">Special Bridge?</label>
                                <input type="text" name="primary" class="form-control" id="primaryTxt" value="{{ ($administrative->special == '1') ? 'Yes' : 'No' }}" data-readonly>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col form-group">
                                <label for="construction_year" class="my-1 mr-2">Year of Contruction / Reconstruction</label>
                                <input type="number" name="construction_year" class="form-control" id="consYearTxt" value="{{ $year->year }}" data-readonly>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="my-1 mr-2" for="access_equipment">Access Equipment</label>
                                <input type="text" name="access_equipment" class="form-control" id="equipTxt" value="{{ isset($administrative->equipment) ? $administrative->equipment->name : ''}}" data-readonly>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <!--
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="maintenance_date" class="my-1 mr-2">Maintenance Date</label>
                                    <input name="maintenance_date" class="form-control" id="mainDateTxt" value="{{ $administrative->maintenance_date }}" data-readonly>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="maintenance_cost" class="my-1 mr-2">Maintenance Cost</label>
                                    <input type="number" name="maintenance_cost" class="form-control" id="mainCostTxt" value="{{ $administrative->maintenance_cost }}" data-readonly>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    -->
                        <div class="form-file mt-3">
                            <img class="" id="imgBridge" src="{{ isset($administrative->photo_path) ? URL::asset('storage/bridge/'.$administrative->photo_path) : '' }}">
                        </div>
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
                                            <label class="my-1 mr-2" for="spans_no">Number of spans</label>
                                            <input type="number" name="spans_no" class="form-control" id="spansNoTxt" value="{{ $geometry->spans_no }}" data-readonly>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="span_min">Min span length (m)</label>
                                            <input type="number" step="0.001" name="span_min" class="form-control" data-readonly id="spanMinTxt" value="{{ $geometry->min_span }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="span_max">Max span length (m)</label>
                                            <input type="number" step="0.001" name="span_max" class="form-control" data-readonly id="spanMaxTxt" value="{{ $geometry->max_span }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="total_length">Total length (m)</label>
                                            <input type="number" step="0.001" name="total_length" class="form-control" id="totalLengthTxt" data-readonly value="{{ $geometry->total_length }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="overall_width">Overall width (m)</label>
                                            <input type="number" step="0.001" name="overall_width" class="form-control" id="overallWidthTxt" data-readonly value="{{ $geometry->overall_width }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="area">Area (m2)</label>
                                            <input type="text" name="area" step="0.001" class="form-control" id="areaTxt" data-readonly value="{{ ($geometry->total_length*$geometry->overall_width) }}">
                                            <div class="invalid-feedback"></div>
                                        </div>


                                    </div>
                                    <div class="col">

                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="median_width">Width of Median (m)</label>
                                            <input type="number" step="0.001" name="median_width" class="form-control" data-readonly id="medianWidthTxt" value="{{ $geometry->median_width }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="carriageways_width">Width of Carriageways (m)</label>
                                            <input type="number" step="0.001" name="carriageways_width" class="form-control" data-readonly id="carriagewaysWidthTxt" value="{{ $geometry->carriageways_width }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="c2c_width">Width of Curb to Curb (m)</label>
                                            <input type="number" step="0.001" name="c2c_width" class="form-control" data-readonly id="c2cWidthTxt" value="{{ $geometry->crub_width }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="approach_width">Width of Approach (m)</label>
                                            <input type="number" step="0.001" name="approach_width" class="form-control" data-readonly id="approachWidthTxt" value="{{ $geometry->approach_width }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="sidewalkl_width">Width of Sidewalk Left (m)</label>
                                            <input type="number" step="0.001" name="sidewalkl_width" class="form-control" data-readonly id="sidewalkl_widthWidthTxt" value="{{ $geometry->sidewalk_left }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="sidewalkr_width">Width of Sidewalk Right (m)</label>
                                            <input type="number" step="0.001" name="sidewalkr_width" class="form-control" data-readonly id="sidewalkrWidthTxt" value="{{ $geometry->sidewalk_right }}">
                                            </input>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="skew_angle">Skew Angle (deg)</label>
                                            <input type="number" name="skew_angle" step="0.001" class="form-control" data-readonly id="skewAngleTxt" value="{{ $geometry->skew_angle }}"></input>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h6 class="h6">Superstructure</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($superstructures as $super)
                                    <div class="col">
                                        @if($super->type == '1')
                                        <h6 class="h6">Main type:</h6>
                                        @else
                                        <h6 class="h6">Secondary type:</h6>
                                        @endif
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="deck">Deck Type</label>
                                            <input type="text" name="deck" class="form-control" data-readonly id="deckTxt-{{$super->id}}" value="{{isset($super->deck) ? $super->deck->name : ''}}"></input>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="system">System Type</label>
                                            <input type="text" name="system" class="form-control" data-readonly id="systemTxt-{{$super->id}}" value="{{isset($super->system) ? $super->system->name : ''}}"></input>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="material">Material of Primary Members</label>
                                            <input type="text" name="material" class="form-control" data-readonly id="materialTxt-{{$super->id}}" value="{{isset($super->material) ? $super->material->name : ''}}"></input>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="h6">Substructure</h6>
                            </div>
                            <div class="card-body">
                                <div class="row pb-4">
                                    @foreach($substructures as $sub)
                                    <div class="col">
                                        @if($sub->structure_type == 'Abutment')
                                        <h6 class="h6">Abutment:</h6>
                                        @else
                                        <h6 class="h6">Pier:</h6>
                                        @endif
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="abutment_type">Type</label>
                                            <input type="text" name="type" class="form-control" data-readonly id="typeSubTxt-{{$sub->id}}" value="{{ isset($sub->type) ? $sub->type->name : '' }}"></input>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="abutment_material">Material</label>
                                            <input type="text" name="material" class="form-control" data-readonly id="materialSubTxt-{{$sub->id}}" value="{{ isset($sub->material) ? $sub->material->name : '' }}"></input>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="abutment_foundation">Foundation</label>
                                            <input type="text" name="foundation" class="form-control" data-readonly id="foundationTxt-{{$sub->id}}" value="{{ isset($sub->foundation) ? $sub->foundation->name : '' }}"></input>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="h6">Bearings</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($bearings as $bearing)
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="fixed_abutment">Type of fixed bearings on {{ $bearing->structure_type }}</label>
                                            <input type="text" name="fixed" class="form-control" data-readonly id="fixedTxt-{{$bearing->id}}" value="{{ isset($bearing->fixed) ? $bearing->fixed->name : '' }}"></input>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="my-1 mr-2" for="free_abutment">Type of free bearings on {{ $bearing->structure_type }}</label>
                                            <input type="text" name="free" class="form-control" data-readonly id="freeTxt-{{$bearing->id}}" value="{{ isset($bearing->free) ? $bearing->free->name : '' }}"></input>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    @endforeach
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
                                    <input type="text" name="parapet" class="form-control" data-readonly id="parapetTxt" value="{{ isset($element->parapet) ? $element->parapet->name : ''}}"></input>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="wearing_surface">Type of Wearing Surface</label>
                                    <input type="text" name="surface" class="form-control" data-readonly id="surfaceTxt" value="{{ isset($element->wearing) ? $element->wearing->name : ''}}"></input>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group pb-4">
                                    <label class="my-1 mr-2" for="expansion_joint">Type of Expansion Joint</label>
                                    <input type="text" name="expansion" class="form-control" data-readonly id="expansionTxt" value="{{ isset($element->expansion) ? $element->expansion->name : '' }}"></input>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <h6 class="h6">Signs</h6>
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="max_load">Max Load (tonnes)</label>
                                    <input type="number" name="max_load" class="form-control" data-readonly id="maxLoadTxt" value="{{ $element->max_load }}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="other_element">Other</label>
                                    <textarea type="text" rows="4" name="other_element" class="form-control" data-readonly id="otherBTxt" value="{{ $element->other }}">
                                                </textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h6 class="h6">Services</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-check">
                                            <label for="tnb" class="my-1 mr-2 ml">TNB Cables?</label>
                                            <input type="text" name="primary" class="form-control" id="primaryTxt" value="{{ ($service->tnb_cables == '1') ? 'Yes' : 'No' }}" data-readonly>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check">

                                            <label for="telekom" class="my-1 mr-2 ml">Telecom Cables?</label>
                                            <input type="text" name="primary" class="form-control" id="primaryTxt" value="{{ ($service->telecom_cables == '1') ? 'Yes' : 'No' }}" data-readonly>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-check">

                                            <label for="watermain" class="my-1 mr-2 ml">Watermain?</label>
                                            <input type="text" name="primary" class="form-control" id="primaryTxt" value="{{ ($service->watermain == '1') ? 'Yes' : 'No' }}" data-readonly>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check">

                                            <label for="lighting" class="my-1 mr-2 ml">Lighting?</label>
                                            <input type="text" name="primary" class="form-control" id="primaryTxt" value="{{ ($service->watermain == '1') ? 'Yes' : 'No' }}" data-readonly>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="other_services">Other</label>
                                    <textarea type="text" rows="4" name="other_services" class="form-control" id="otherSTxt" value="{{ $service->other }}" data-readonly>
                                                </textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tabs-text-3" role="tabpanel" aria-labelledby="tabs-text-3-tab" class="tab-pane fade">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="design_load">Design Load</label>
                                    <input type="text" name="design_load" class="form-control" data-readonly id="designLoadTxt" value="{{isset($passage->design_load) ? $passage->design_load : '' }}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="design_code">Design Code</label>
                                    <input type="text" name="design_code" class="form-control" data-readonly id="designCodeTxt" value="{{isset($passage->design_code) ? $passage->design_code : '' }}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="capacity">Capacity</label>
                                    <input type="text" name="capacity" class="form-control" data-readonly id="capacityTxt" value="{{isset($passage->capacity) ? $passage->capacity->name : ''}}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group pb-2">
                                    <label class="my-1 mr-2" for="discounted_capacity">Discounted Capacity (SV)</label>
                                    <input type="text" name="discounted_capacity" class="form-control" data-readonly id="discountedCapacityTxt" value="{{isset($passage->discounted_capacity) ? $passage->discounted_capacity : ''}}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <h6 class="h6">Vertical Clearance (m)</h6>
                        <div class="form-group">
                                    <!--    <label class="my-1 mr-2" for="vc"></label> -->
                                        <input type="number" step="0.001" name="o" class="form-control" data-readonly id="oTxt" value="{{isset($passage->vertical_clearance_o) ? $passage->vertical_clearance_o : ''}}">
                                        </input>
                                        <div class="invalid-feedback"></div>
                                    </div>
                        <!--
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="l">Left</label>
                                    <input type="number" step="0.001" name="l" class="form-control-plaintext" readonly id="lTxt" value="{{isset($passage->vertical_clearance_l) ? $passage->vertical_clearance_l : ''}}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="lm">Lrft Middle</label>
                                    <input type="number" step="0.001" name="lm" class="form-control-plaintext" readonly id="lmTxt" value="{{isset($passage->vertical_clearance_lm) ? $passage->vertical_clearance_lm : ''}}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="rm">Right Middle</label>
                                    <input type="number" step="0.001" name="rm" class="form-control-plaintext" readonly id="rmTxt" value="{{isset($passage->vertical_clearance_rm) ? $passage->vertical_clearance_rm : ''}}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="r">Right</label>
                                    <input type="number" step="0.001" name="r" class="form-control-plaintext" readonly id="rTxt" value="{{isset($passage->vertical_clearance_r) ? $passage->vertical_clearance_r : ''}}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        -->
                    </div>
                    <div id="tabs-text-4" role="tabpanel" aria-labelledby="tabs-text-4-tab" class="tab-pane fade">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="owner">Owner</label>
                                    <input type="text" name="owner" class="form-control" data-readonly id="ownerTxt" value="{{isset($miscellaneous->owner) ? $miscellaneous->owner : ''}}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="designer">Designer</label>
                                    <input type="text" name="designer" class="form-control" data-readonly id="designerTxt" value="{{ isset($miscellaneous->designer) ? $miscellaneous->designer : ''}}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="inpection_responsible">Inpection Responsible</label>
                                    <input type="text" name="inpection_responsible" class="form-control" data-readonly id="inpectionResponsibleTxt" value="{{isset($miscellaneous->inspection_responsible) ? $miscellaneous->inspection_responsible : ''}}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="maintenance_department">Maintenance Department</label>
                                    <input type="text" name="maintenance_department" class="form-control" data-readonly id="maintenanceDepartmentTxt" value="{{isset($miscellaneous->maintenance_dept) ? $miscellaneous->maintenance_dept : ''}}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="coordinatex">Latitude</label>
                                    <input type="number" step="0.000000000001" name="coordinatex" data-readonly class="form-control" id="coordinateXTxt" value="{{isset($miscellaneous->coordinate_x) ? $miscellaneous->coordinate_x : ''}}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="my-1 mr-2" for="coordinatey">Longitude</label>
                                    <input type="number" step="0.000000000001" name="coordinatey" data-readonly class="form-control" id="coordinateYTxt" value="{{isset($miscellaneous->coordinate_y) ? $miscellaneous->coordinate_y : ''}}">
                                    </input>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="form-group">
                            <label class="my-1 mr-2" for="accident_record">Accident Record (POL)</label>
                            <input type="text" name="accident_record" readonly class="form-control-plaintext" id="accidentRecordTxt" value="{{isset($miscellaneous->accident_record) ? $miscellaneous->accident_record : ''}}">
                            </input>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label class="my-1 mr-2" for="flood_level">Flood Level</label>
                            <input type="text" name="flood_level" readonly class="form-control-plaintext" id="floodLevelTxt" value="{{isset($miscellaneous->flood_level) ? $miscellaneous->flood_level : ''}}">
                            </input>
                            <div class="invalid-feedback"></div>
                        </div>
                        -->
                    </div>
                </div>
                <!--
                    </div>
                </div>
                -->
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
                    <form method="POST" id="formCertifier" action="{{route('bridge.approve')}}">
                    @csrf
                    <input type="hidden" id="hdn_bridge_id" name="bridge_id" value="{{ $bridge->id }}"/>
                    <input type="hidden" id="hdn_year_id" name="year_id" value="{{ $year->id }}" />
                    @if($year->status == 'PENDING' && Auth::user()->hasRole(['Administrator','Certifier']))
                    <input type="hidden" name="task_id" value="{{ $task }}" />
                    <div class="form-group">
                        <label class="my-1 mr-2" for="comment">Comment</label>
                        <textarea class="form-control" name="comment" id="commentTxt" rows="4"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <button class="btn btn-primary"  type="submit"><span class="far fa-thumbs-up mr-2"></span>Approve</button>
                    <button class="btn btn-primary" id="revertBtn" type="button"><span class="fas fa-undo mr-2"></span>Revert</button>
                    @endif
                    @if($year->status == 'APPROVED' && Auth::user()->hasRole('Administrator'))
                    <button class="btn btn-primary" type="button" id="editbtn"><span class="mr-2 fa fa-edit"></span>Edit</button>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-delete" type="button" id="modelbtn"><span class="mr-2 fa fa-trash"></span>Delete</button>
                    @endif
                    <button class="btn btn-primary" onclick="history.back();" type="button"><span class="fas fa-backward mr-2"></span>Back</button>
                    </form>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Confirmation</h2>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <span>Are tou sure delete this bridge?</span>
            </div>
            <div class="modal-footer">
                <button type="button" id="delbtn" class="btn btn-sm btn-secondary">Yes</button>
                <button type="button" class="btn btn-link text-danger ml-auto" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(function() {
    $('#revertBtn').click(function() {
        $('#formCertifier').attr("action","{{route('bridge.revert')}}");
        $('#commentTxt').attr("required","required");
        $('#formCertifier').submit();
    });
    $('#editbtn').click(function() {
     //   $('#formCertifier').attr("action","{{route('bridge.review')}}");
     //   $('#formCertifier').submit();
          window.location.href = "{{route('bridge.review')}}?bridge_id="+$('#hdn_bridge_id').val()+"&year_id="+$('#hdn_year_id').val();
    });
    $('#delbtn').click(function() {
        $('#formCertifier').attr("action","{{route('remove.year')}}");
        //$('#commentTxt').attr("required","required");
        $('#formCertifier').submit();
    });
    $('#menu-inventory').removeClass("collapsed");
        $('#menu-inventory').attr("aria-expanded", "true");
        $('#submenu-app-inventory').addClass("show");
});
</script>
@endsection
