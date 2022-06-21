@extends('layouts.main')

@section('head')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/DataTables/DataTables-1.10.23/css/dataTables.bootstrap4.min.css')}}" />

@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Bridge</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded" id="tblListInspect">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">Structure No.</th>
                                <th class="border-0">Name</th>
                                <th class="border-0">Route</th>
                                <th class="border-0">Region</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">Action</th>
                            </tr>
                        <tbody id="tbodyR">
                        </tbody>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('vendor/DataTables/datatables.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/DataTables-1.10.23/js/dataTables.bootstrap4.min.js')}}"></script>

<script type="text/javascript">
    $(function() {
        var table = $('#tblListInspect').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ $route }}",
            columns: [{
                    data: 'structure_no',
                    name: 'structure_no',
                    className: "border-0"
                },
                {
                    data: 'bridge_name',
                    name: 'bridge_name',
                    className: "border-0"

                },
                {
                    data: 'route',
                    name: 'route',
                    className: "border-0"

                },
                {
                    data: 'region',
                    name: 'region',
                    className: "border-0"

                },
                {
                    data: 'asset',
                    name: 'asset',
                    className: "border-0"

                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: "border-0"
                },
            ]
        });

        
    });
    @if (session('message'))  
    $('#modal-msg').modal('show');
    @endif

   $('#menu-inspection').removeClass("collapsed");
   $('#menu-inspection').attr("aria-expanded", "true");
   $('#submenu-app-inspection').addClass("show");
   $('#inspectTask').addClass("active");
</script>
@endsection