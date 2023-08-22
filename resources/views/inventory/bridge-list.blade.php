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
            <!--
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center ">
                    <button type="button" id="exportBtn" class="btn btn-primary btn-sm mr-2">
                        <span class="fas fa-plus mr-2"></span>Export
                    </button>
                </div>
            -->
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded" id="tblListBridge">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0"></th>
                                <th class="border-0">Structure No.</th>
                                <th class="border-0">Bridge Name</th>
                                <th class="border-0">Road No.</th>
                                <th class="border-0">Road Name</th>
                                <th class="border-0">Section</th>
                                <th class="border-0">Route</th>
                                <th class="border-0">State</th>
                                <th class="border-0">District</th>
                                <th class="border-0">Year Built</th>
                                <th class="border-0">Region</th>
                                <th class="border-0">Spans</th>
                                <th class="border-0">Max Span</th>
                                <th class="border-0">Total Length</th>
                                <th class="border-0">Bridge Width</th>
                                <th class="border-0">Width Kerb to Kerb</th>
                                <th class="border-0">Skew</th>
                                <th class="border-0">Deck Type</th>
                                <th class="border-0">System Type</th>
                                <th class="border-0">Material Type</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">Action</th>
                            </tr>
                        <tbody id="tbodyR">
                        </tbody>
                        </thead>
                    </table>
                </div>
                <form id="exportForm" action="{{route('export.bridge')}}" method="POST">
                    @csrf
                    <input type="hidden" name="ids" id="idsHdn"/>
                    <input type="hidden" type="type" id="typeHdn"/>
                </form>
                <form id="deleteForm" action="{{route('remove.bridge')}}" method="POST">
                    @csrf
                    <input type="hidden" name="bridge_id" id="bridge_id"/>
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
        var table = $('#tblListBridge').DataTable({
            dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-3'B><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [ {
                extend: 'copyHtml5',
                title: 'Bridge Directory',
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 12, 13, 14, 15, 16, 17, 18, 19, 20],
                }
            }, {
                extend: 'excelHtml5',
                title: 'Bridge Directory',
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 12, 13, 14, 15, 16, 17, 18, 19, 20],
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'Bridge Directory',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 12, 13, 14, 15, 16, 17, 18, 19, 20],
                }
            }],
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
                    data: 'route_code',
                    name: 'route_code',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'route_name',
                    name: 'route_name',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'road.number',
                    name: 'road.number',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'place',
                    name: 'place',
                    className: "border-0"

                },
                {
                    data: 'state_name',
                    name: 'state_name',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'district.name',
                    name: 'district.name',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'year',
                    name: 'year',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'region',
                    name: 'region',
                    className: "border-0"

                },
                {
                    data: 'span',
                    name: 'span',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'max_span',
                    name: 'max_span',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'total_length',
                    name: 'total_length',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'bridge_width',
                    name: 'bridge_width',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'c2c',
                    name: 'c2c',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'skew',
                    name: 'skew',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'deck',
                    name: 'deck',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'system',
                    name: 'system',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'material',
                    name: 'material',
                    className: "border-0",
                    visible: false

                },
                {
                    data: 'asset.name',
                    name: 'asset.name',
                    className: "border-0"

                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: "border-0"
                },
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
                'style': 'multi',
                'items': 'row'
            },
            'order': [[1, 'asc']]
        });

        /*

        */

        table.buttons().container()
              .appendTo('#datatable_wrapper .col-md-6:eq(0)');

        @if (session('message'))
        $('#modal-msg').modal('show');
        @endif


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
            $('#exportForm').submit();
       });
    });

    $('#delbtn').click(function() {
        $('#deleteForm').submit();
    });
    function deleteThis(id) {
        $('#bridge_id').val(id);
        $('#modal-delete').modal('show');
    //    $('#deleteForm').submit();
    }

    $('#menu-inventory').removeClass("collapsed");
    $('#menu-inventory').attr("aria-expanded", "true");
    $('#submenu-app-inventory').addClass("show");
 //   $('#categoryMgnt').addClass("active");
</script>
@endsection
