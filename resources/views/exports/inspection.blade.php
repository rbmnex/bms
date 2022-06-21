<?php
$file="bridge_rating_" . date('Ymd') . ".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$file");
?>

<table>
    <thead>
        <tr>
            <th>Structure No.</th>
            <th>Bridge Name</th>
            <th>Road No.</th>
            <th>Road Name</th>
            <th>Section</th>
            <th>State</th>
            <th>District</th>
            <th>Year Built</th>
            <th>Spans</th>
            <th>Max Span</th>
            <th>Total Length</th>
            <th>Bridge Width</th>
            <th>Width Kerb to Kerb</th>
            <th>Skew</th>
            <th>Deck Type</th>
            <th>System Type</th>
            <th>Material Type</th>
            <!-- load component -->
            @foreach($components as $member)
            <th>{{$member->name}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
    @isset($bridges)
        @foreach($bridges as $bridge)
        @php
            $year = $bridge->years->first();
            $bridge->loadMissing('road');
            $road = $bridge->road;
            $bridge->loadMissing('district');
            $district = $bridge->district;
            $year->loadMissing('geometry');
            $geometry = $year->geometry;
            $year->loadMissing('superstructures');
            $superstructure = $year->superstructures->first();
            $bridge->loadMissing('ratings');
            $items = array();
            if(isset($bridge->ratings)) {
                $rating = $bridge->ratings->first();
                if(isset( $rating)) {
                $rating->loadMissing('components');
                foreach($rating->components as $comp) {
                    array_push($items,$comp);
                }
                }
            }
        @endphp

        <tr>
            <td>{{$bridge->structure_no}}</td>
            <td>{{$bridge->name}}</td>
            <td>{{$road->route->code}}</td>
            <td>{{$road->route->name}}</td>
            <td>{{$road->number}}</td>
            <td>{{$district->state->name}}</td>
            <td>{{$district->name}}</td>
            <td>{{$year->year}}</td>
            <td>{{$geometry->spans_no}}</td>
            <td>{{$geometry->max_span}}</td>
            <td>{{$geometry->total_length}}</td>
            <td>{{$geometry->overall_width}}</td>
            <td>{{$geometry->crub_width}}</td>
            <td>{{$geometry->skew_angle}}</td>
            <td>{{isset($superstructure->deck) ? $superstructure->deck->name : ''}}</td>
            <td>{{isset($superstructure->system) ? $superstructure->system->name : ''}}</td>
            <td>{{isset($superstructure->material) ? $superstructure->material->name : ''}}</td>
            <!-- load rating -->
            @foreach($components as $member)
            @if(empty($items))
            <td></td>
            @else
            @php
            $v = $member->id;
            $entry = array_filter($items, function ($e) use ($v) {
                        return $e->component_id == $v;
                    });
            echo'<td>'.(array_values($entry)[0])->condition_rating.'</td>';
            $entry = NULL;
            @endphp
            @endif
            @endforeach
        </tr>
        @endforeach
    @endisset
    </tbody>
</table>

