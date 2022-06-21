<html>
<head>
<style>
th,td,p,div,b {margin:0;padding:0}
@page { margin: 0px; }
body { margin: 0px; }
</style>
</head>
<body>
<table>
    <thead>
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
        <th>Type</th>
        <th>Remark</th>
    </thead>
    <tbody>
    @foreach($bridges as $bridge)
        <tr>
            <td>{{$bridge->structure_no}}</td>
            <td>{{$bridge->bridge_name}}</td>
            <td>{{$bridge->code}}</td>
            <td>{{$bridge->road_name}}</td>
            <td>{{$bridge->section}}</td>
            <td>{{$bridge->state_name}}</td>
            <td>{{$bridge->district_name}}</td>
            <td style="white-space:pre-line; word-break: break-all;">
            @foreach($bridge->years as $year)
            {{$year->year.PHP_EOL}}
            @endforeach
            </td>
            <td>
            @foreach($bridge->years as $year)
            @isset($year->geometry)
            {{((isset($year->geometry->spans_no)) ? $year->geometry->spans_no : ' ').PHP_EOL}}
            @endisset
            @empty($year->geometry)
            {{' </br>'}}
            @endempty
            @endforeach
            </td>
            <td>
            @foreach($bridge->years as $year)
            @isset($year->geometry)
            {{((isset($year->geometry->max_span)) ? $year->geometry->max_span : ' ').PHP_EOL}}
            @endisset
            @empty($year->geometry)
            {{' </br>'}}
            @endempty
            @endforeach
            </td>
            <td>
            @foreach($bridge->years as $year)
            @isset($year->geometry)
            {{((isset($year->geometry->total_length)) ? $year->geometry->total_length : ' ').PHP_EOL}}
            @endisset
            @empty($year->geometry)
            {{' </br>'}}
            @endempty
            @endforeach
            </td>
            <td>
            @foreach($bridge->years as $year)
            @isset($year->geometry)
            {{((isset($year->geometry->overall_width)) ? $year->geometry->overall_width : ' ').PHP_EOL}}
            @endisset
            @empty($year->geometry)
            {{' </br>'}}
            @endempty
            @endforeach
            </td>
            <td>
            @foreach($bridge->years as $year)
            @isset($year->geometry)
            {{((isset($year->geometry->crub_width)) ? $year->geometry->crub_width : ' ').PHP_EOL}}
            @endisset
            @empty($year->geometry)
            {{' </br>'}}
            @endempty
            @endforeach
            </td>
            <td>
            @foreach($bridge->years as $year)
            @isset($year->geometry)
            {{((isset($year->geometry->skew_angle)) ? $year->geometry->skew_angle : ' ').PHP_EOL}}
            @endisset
            @empty($year->geometry)
            {{' </br>'}}
            @endempty
            @endforeach
            </td>
            <td>
            @foreach($bridge->years as $year)
            @isset($year->superstructures)
            @php
            $year->loadMissing('superstructures');
            $superstructure = $year->superstructures->first();
            @endphp
            {{((isset($superstructure->deck)) ? $superstructure->deck->name : ' ').PHP_EOL}}
            @endisset
            @empty($year->superstructures)
            {{' </br>'}}
            @endempty
            @endforeach
            </td>
            <td>
            @foreach($bridge->years as $year)
            @isset($year->superstructures)
            @php
            $year->loadMissing('superstructures');
            $superstructure = $year->superstructures->first();
            @endphp
            {{((isset($superstructure->system)) ? $superstructure->system->name : ' ').PHP_EOL}}
            @endisset
            @empty($year->superstructures)
            {{' </br>'}}
            @endempty
            @endforeach
            </td>
            <td>
            @foreach($bridge->years as $year)
            @isset($year->superstructures)
            @php
            $year->loadMissing('superstructures');
            $superstructure = $year->superstructures->first();
            @endphp
            {{((isset($superstructure->material)) ? $superstructure->material->name : ' ').PHP_EOL}}
            @endisset
            @empty($year->superstructures)
            {{' </br>'}}
            @endempty
            @endforeach
            </td>
            <td>{{$bridge->asset}}</td>
            <td>{{$bridge->remark}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>