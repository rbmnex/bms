<?php
$file="detail_rating_" . date('Ymd') . ".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");
?>
<html>

<head>
</head>

<body>
    <h1>ROUTINE CONDITION INSPECTION</h1>
    <h3>- Summary Report Form -</h3>
    <br/>
    <br/>
    <br/>
    <h4>Location Data:</h4>
    <br/>
    <table>
        <tbody>
            <tr>
                <td>Route No. :</td>
                <td>{{$road->route->code}}</td>
                <td>River/Bridge Name :</td>
                <td>{{$bridge->name}}</td>
            </tr>
            <tr>
                <td>Structure No. :</td>
                <td>{{$bridge->structure_no}}</td>
                <td>District :</td>
                <td>{{$bridge->district->name}}</td>
                <td>State</td>
                <td>{{$bridge->district->state->name}}</td>
            </tr>
        </tbody>
    </table>
    <br/>
    <h4>Bridge Type:</h4>
    <br/>
    <table>
        <tbody>
            <tr>
                <td>System Type</td>
                <td>{{$superstructure->system->name}}</td>
                <td>Deck Type</td>
                <td>{{$deck}}</td>
            </tr>
            <tr>
                <td>Abutment Type</td>
                <td>{{isset($abutment->type) ? $abutment->type->name : ''}}</td>
                <td>Pier Type</td>
                <td>{{isset($pier->type) ? $pier->type->name : ''}}</td>
            </tr>
        </tbody>
    </table>
    <br/>
    <h4>Structure Data:</h4>
    <table>
        <tbody>
            <tr>
            <!--
                <td>Road Width</td>
                <td></td>
            -->
                <td>Bridge Width</td>
                <td>{{isset($geometry->overall_width) ? $geometry->overall_width : 0}}</td>
                <td>Skew Angle</td>
                <td>{{isset($geometry->skew_angle) ? $geometry->skew_angle : 0}}</td>
            </tr>
            <tr>
                <td>No. of Span</td>
                <td>{{$geometry->spans_no}}</td>
                <td>Span(s)</td>
                <td>{{$geometry->max_span}} M ({{$geometry->spans_no}} SPAN(S)) </td>
            </tr>
            <tr>
                <td>Bridge Length</td>
                <td>{{$geometry->total_length}}</td>
                <td>Year Built</td>
                <td>{{$year->year}}</td>
            </tr>
        </tbody>
    </table>
    <br/>
    <h4>Summary Report:</h4>
    <table>
        <thead>
            <tr>
                <th>BRIDGE MEMBER</th>
                <th>RATING</th>
                <th>DAMAGE TYPE</th>
                <th>PHOTO</th>
                <th>REMARK</th>
            </tr>
        </thead>
        <tbody>
        @if(isset($components))
        @foreach($components as $member)
            <tr>
                <td>{{$member->component->name}}</td>
                <td>
                @isset($member->condition_rating)
                @if($member->condition_rating == 1)
                1 - No damages found and no maintenance required as a result of the inspection
                @elseif($member->condition_rating == 2)
                2 - Damaged detected and its necessary to record the condition for observation purposes
                @elseif($member->condition_rating == 3)
                3 - Damaged detected are slightly critical and thus its is necessary to implement routine
                maintenance work
                @elseif($member->condition_rating == 4)
                4 - Damaged detected is critical and thus it is necessary to implement repair work or to carry out
                a detailed inspection to determine whether ny rehabilitation works are required or not
                @elseif($member->condition_rating == 5)
                5 - Being heavily and critically damaged and possibly affecting the safety of traffic, it is necessary
                to implement emergency temporary repair work immediately or rehabilitation work without delay after the provision of a load limitation traffic sign
                @elseif($member->condition_rating == 0)
                0 - Bridge cannot fully inspected because of access problem such as submerged structures. Reinspection Necessary whenever possible
                @else
                Not Applicable
                @endif
                @endisset
                </td>
                <td>{{isset($member->damage) ? $member->damage->name : ''}}</td>
                <td>@isset($member->photos)
                                @foreach($member->photos as $photo)

                                    <img class="" id="img-{{$photo->id}}" src="{{ isset($photo->path) ? URL::asset('storage/inspection/'.$photo->path) : '' }}">

                                @endforeach
                                @php
                                $photo = NULL;
                                @endphp
                            @endisset
                </td>
                <td>{{$member->remark}}</td>
            </tr>
        @endforeach
        @else
        @foreach($items as $obj)
        <tr>
            <td>{{$obj->name}}</td>
            <td>This component not yet been rated</td>
            <td>No damage been reported</td>
            <td></td>
        </tr>
        @endforeach
        @endif
        </tbody>
    </table>
</body>

</html>
