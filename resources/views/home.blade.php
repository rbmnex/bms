@extends('layouts.main')

@section('head')
<link type="text/css" href="{{asset('vendor/apexchart/apexcharts.css')}}" rel="stylesheet">
<style>
    td, tr {
        text-align: center;
    }

    .zone {
        background-color: #f8c676;
    }

    .total {
        background-color: #f8768f;
    }
</style>
@endsection

@section('js')
<script src="{{asset('vendor/apexchart/apexcharts.min.js')}}"></script>

<script>

</script>
@endsection

@section('content')
<div class="container" style="height:100%">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    The Bridge Management System (BMS) provides a platform to facilitate the collection and reception of bridge data online to facilitate and expedite periodic and non-periodic monitoring processes on bridges on Federal Route roads.
This system has web-based features that allow all bridge information registration, review and approval processes to be done more effectively and systematically regardless of user location
                </div>
            </div>
            <div class="card">
                <div class="card-header">Breakdown Of Bridge/Overpass/Culvert Assets On Federal Routes</div>
                <div class="card-body">
                    <div class="col-12">
                        <table class="table table-centered table-nowrap mb-0 rounded">
                            <thead class="thead-light">
                                <tr>
                                    <th>State</th>
                                    <th>Asset Count</th>
                                    @foreach ($columns as $col)
                                    <th>{{ $col->asset_name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($middleEast as $me)
                                <tr>
                                    <td>{{ $me['state'] }}</td>
                                    <td>{{ $me['count'] }}</td>
                                    @foreach ($columns as $cme)
                                    <td>{{ isset($me[$cme->asset_name]) ? $me[$cme->asset_name] : 0 }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                                <tr class="zone">
                                    <td>Middle East Zone</td>
                                    <td>{{ $middleEast->sum('count') }}</td>
                                    @foreach ($columns as $cme)
                                    <td>{{ $middleEast->sum($cme->asset_name) }}</td>
                                    @endforeach

                                </tr>
                                @foreach ($north as $n)
                                <tr>
                                    <td>{{ $n['state'] }}</td>
                                    <td>{{ $n['count'] }}</td>
                                    @foreach ($columns as $cn)
                                    <td>{{ isset($n[$cn->asset_name]) ? $n[$cn->asset_name] : 0 }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                                <tr class="zone">
                                    <td>North Zone</td>
                                    <td>{{ $north->sum('count') }}</td>
                                    @foreach ($columns as $cn)
                                    <td>{{ $north->sum($cn->asset_name) }}</td>
                                    @endforeach
                                </tr>
                                @foreach ($south as $s)
                                <tr>
                                    <td>{{ $s['state'] }}</td>
                                    <td>{{ $s['count'] }}</td>
                                    @foreach ($columns as $cs)
                                    <td>{{ isset($s[$cs->asset_name]) ? $s[$cs->asset_name] : 0 }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                                <tr class="zone">
                                    <td>South Zone</td>
                                    <td>{{ $south->sum('count') }}</td>
                                    @foreach ($columns as $cs)
                                    <td>{{ $south->sum($cs->asset_name) }}</td>
                                    @endforeach
                                </tr>
                                @foreach ($other as $o)
                                <tr>
                                    <td>{{ $o['state'] }}</td>
                                    <td>{{ $o['count'] }}</td>
                                    @foreach ($columns as $co)
                                    <td>{{ isset($o[$co->asset_name]) ? $o[$co->asset_name] : 0 }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                                <tr class="total">
                                    <td>Overall Total</td>
                                    <td>{{ $data->sum('count') }}</td>
                                    @foreach ($columns as $col)
                                    <th>{{ $data->sum($col->asset_name) }}</th>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--
            <div class="card">
                <div class="card-header">Bridge in Federal Roads According To States</div>
                <div class="card-body">
                    <div class="col-12">
                        <div id="pie-demo"></div>
                    </div>
                </div>

        </div>
        <div class="card">
            <div class="card-header">Bridge Statistic According To Materials</div>
            <div class="card-body">
                <div class="col-12">
                    <div id="material-bar"></div>
                </div>
            </div>

    </div>
    <div class="card">
        <div class="card-header">Bridge Statistic According To System Type</div>
        <div class="card-body">
            <div class="col-12">
                <div id="system-bar"></div>
            </div>
        </div>

    </div>
    <div class="card">
        <div class="card-header">Bridge Statistic According To Deck Type</div>
        <div class="card-body">
            <div class="col-12">
                <div id="deck-bar"></div>
            </div>
        </div>

    </div>
-->






        </div>
    </div>
</div>
@endsection
