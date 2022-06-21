@extends('layouts.main')

@section('head')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/DataTables/DataTables-1.10.23/css/dataTables.bootstrap4.min.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('vendor/DataTables/Buttons-1.6.5/css/buttons.bootstrap4.min.css')}}" />
@endsection

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-light shadow-sm components-section">
            <div class="card-header border-bottom border-light">
                <h3 class="h5 mb-0">Bridge</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center ">
                    <button type="button" id="exportBtn" class="btn btn-primary btn-sm mr-2">
                        <span class="fas fa-plus mr-2"></span>Export
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded" id="tblListInspect">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">#</th>
                                <th class="border-0">Structure No.</th>
                                <th class="border-0">Name</th>
                                <th class="border-0">Route</th>
                                <th class="border-0">Region</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">Rating</th>
                                <th class="border-0">Action</th>
                            </tr>
                        <tbody id="tbodyR">
                        </tbody>
                        </thead>
                    </table>
                </div>
                <form id="exportForm" action="{{route('export.rating')}}" method="POST">
                    @csrf
                    <input type="hidden" name="ids" id="idsHdn"/>
                    <input type="hidden" type="type" id="typeHdn"/>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('vendor/DataTables/datatables.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/DataTables-1.10.23/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/Buttons-1.6.5/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/Buttons-1.6.5/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/Buttons-1.6.5/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/Buttons-1.6.5/js/buttons.print.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/Buttons-1.6.5/js/buttons.colVis.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/Checkbox-1.2.12/js/dataTables.checkboxes.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/JSZip-2.5.0/jszip.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/pdfmake-0.1.36/pdfmake.min.js')}}"></script>
<script src="{{asset('vendor/DataTables/pdfmake-0.1.36/vfs_fonts.js')}}"></script>
<script type="text/javascript">
    $(function() {
        var table = $('#tblListInspect').DataTable({
            processing: true,
            deferRender: true,
            serverSide: true,
            bLengthChange: false,
            searchDelay: 500,
            pageLength: 10,
            ajax: "{{ $route }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: false,
                    className: "border-0"
                },
                {
                    data: 'structure_no',
                    name: 'structure_no',
                    className: "border-0"
                },
                {
                    data: 'name',
                    name: 'name',
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
                    data: 'asset.name',
                    name: 'asset.name',
                    className: "border-0"

                },
                {
                    data: 'rating',
                    name: 'rating',
                    className: "border-0"

                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: "border-0"
                }
            ],
            'columnDefs': [
                {
                    'targets': 0,
                    'checkboxes': {
                    'selectRow': true
                    }
                }
            ],
            'select': {
            'style': 'multi'
            },
            'order': [[1, 'asc']]
        });

        $('#exportBtn').click(function() {
        //    var rows_check = table.column(0).checkboxes.select();
            var rows_selected = table.column(0).checkboxes.selected();
        //    console.log(Object.values(rows_selected)[1]);
            var count = 0
            var ids = [];
            $.each(rows_selected, function(index, rowId) {
                ids.push(rowId);
                count = count + 1; 
            });
            if(count > 0) {
                $('#idsHdn').val(JSON.stringify(ids));
            } else {
                table.column(0).checkboxes.select();
                $.each(rows_selected, function(index, rowId) {
                    ids.push(rowId);
                });
                
                $('#idsHdn').val(JSON.stringify(ids));
            }
            console.log(JSON.stringify(ids));
            $('#exportForm').submit();
       });
    });
    @if (session('message'))  
    $('#modal-msg').modal('show');
    @endif

   $('#menu-inspection').removeClass("collapsed");
   $('#menu-inspection').attr("aria-expanded", "true");
   $('#submenu-app-inspection').addClass("show");
   $('#bridgeList').addClass("active");
</script>
@endsection