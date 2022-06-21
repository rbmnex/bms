@extends('layouts.main')

@section('head')
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
                    @isset($inpects)
                    <div class="accordion" id="myAccordion">
                        @foreach($inpects as $inspect)
                        <div class="card">
                            <div class="card-header" id="heading-{{$inspect->id}}">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link mx-2" data-toggle="collapse" data-target="#collapse-{{$inspect->id}}">Inspection Date: {{isset($inspect) ? $inspect->inspection_date : ''}}</button>
                                    <a class="btn btn-primary" href="{{route('export.detail-rating').'?id='.$inspect->id}}">Export</a>
                                </h2>
                            </div>
                            <div id="collapse-{{$inspect->id}}" class="collapse" aria-labelledby="heading-{{$inspect->id}}" data-parent="#myAccordion">
                                <div class="card-body">
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
                                    <div class="row">
                                        <div class="col-3"><label class="my-1 mr-2" for="component">Component Name :</label></div>
                                        <div class="col"><label class="my-1 mr-2" for="rating">Condition Rating :</label></div>
                                        <div class="col"><label class="my-1 mr-2" for="rating">Damage Type :</label></div>
                                        <div class="col"><label class="my-1 mr-2" for="state">Remark :</label></div>
                                    </div>
                                    @isset($inspect->members)
                                    @foreach($inspect->members as $item)
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
                                                @endisset
                                            </div>
                                        </div>
                                        <div class="col">
                                            <span>{{$item->remark}}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endisset
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endisset
                    @if(!count($inpects))
                    <div class="row">
                    <span><strong><i>This bridge not been rated yet</i></strong></span>
                    </div>
                    @endif
                    <button class="btn btn-primary" id="backBtn" onclick="history.back();" type="button"><span class="fas fa-backward mr-2"></span>Back</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection