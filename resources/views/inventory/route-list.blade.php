@extends('layouts.main')

@section('head')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.6/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.css" />

@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Route Management</h3>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center ">
                    <a href="{{route('route.form')}}" class="btn btn-primary btn-sm mr-2">
                        <span class="fas fa-plus mr-2"></span>Add Route
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded" id="tblListRoute">
                        <thead class="thead-light">
                            <tr>
                                <!--<th class="border-0">#</th> -->
                                <th class="border-0">Route Code</th>
                                <th class="border-0">Route Name</th>
                                <th class="border-0">Route Type</th>
                                <th class="border-0">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyR">
                            <!-- Item -->
                            @foreach ($list as $route)
                            <tr>
                                <!--
                                <td class="border-0"><input class="form-check-input" type="radio" name="routeid" value="{{ $route->id }}"></td>
                                -->
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $route->code }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $route->name }}</div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">{{ $route->type->name }}</div>
                                </td>
                                <td class="border-0">
                                    <form method="GET" action="{{ route('route.edit') }}">
                                        <input type="hidden" value="{{ $route->id }}" name="routeid">
                                        <div class="d-flex align-items-center">
                                            <button type="submit" class="btn btn-sm btn-info" type="button"><span class="fa fa-edit mx-1"></span>Edit</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            <!-- End of Item -->
                        </tbody>
                    </table>
                    <!--
                    <div class="d-flex align-items-center justify-content-end">
                        
                    </div>
                    -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('vendor/DataTables/datatables.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/DataTables-1.10.23/js/dataTables.bootstrap4.min.js')}}"></script>

<script>
    type = "text/javascript" >
        $(document).ready(function() {
            @if (session('message'))  
    $('#modal-msg').modal('show');
    @endif
            $('#tblListRoute').DataTable({
                "columnDefs": [{
                    "targets": 3,
                    "searchable": false,
                    "orderable": false
                }]
            });
            $('#menu-registration').removeClass("collapsed");
            $('#menu-registration').attr("aria-expanded", "true");
            $('#submenu-app-registration').addClass("show");
            $('#routeList').addClass("active");

            
        });
</script>
@endsection