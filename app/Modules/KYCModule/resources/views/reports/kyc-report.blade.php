@extends('global.base')
@section('title', 'KYC Profiles')




{{-- import in this section your css files --}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.1.4/css/rowGroup.dataTables.min.css">
    <link href="https://cdn.datatables.net/rowgroup/1.1.3/css/rowGroup.dataTables.min.css" rel="stylesheet">
    
    <style>

table.dataTable td {
        font-size: 14px !important;
    }
    table.dataTable th {
        font-size: 14px !important;
    }
    
    /* MODIFY DATATABLE WRAPPER/MOBILE VIEW NAVAGATE ROW ICON */
    .dataTables_wrapper table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child::before{
        /* background: #008a8a !important; */
        background: #008a8a !important;
        border-radius: 10px !important;
        border: none !important;
        top: 18px !important;
        left: 5px !important;
        line-height: 16px !important;
        box-shadow: none !important;
        color: #fff !important;
        font-weight: 700 !important;
        height: 16px !important;
        width: 16px !important;
        text-align: center !important;
        text-indent: 0 !important;
        font-size: 14px !important;
    }
    
    .dataTables_wrapper table.dataTable.dtr-inline.collapsed>tbody>tr.parent>td:first-child:before, 
    .dataTables_wrapper table.dataTable.dtr-inline.collapsed>tbody>tr.parent>th:first-child:before{
        /* background: #008a8a !important; */
        background: #b31515 !important;
        border-radius: 10px !important;
        border: none !important;
        top: 18px !important;
        left: 5px !important;
        line-height: 16px !important;
        box-shadow: none !important;
        color: #fff !important;
        font-weight: 700 !important;
        height: 16px !important;
        width: 16px !important;
        text-align: center !important;
        text-indent: 0 !important;
        font-size: 14px !important;
    }

    
        td { font-size: 17px; font-weight: 500; }


        
        .reports > thead > tr > th  {
            color:white;
            background-color: #008a8a;
            font-size: 20px;
            font-family: calibri
        }

    

        .reports > thead > tr > th   {
            color:white;
            font-size: 20px;
            background-color: #008a8a;
            font-weight: bold
        }


        .reports > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            padding: 5px !important;
        }           

              
        .reports > thead > tr > th   {
            color:white;
            background-color: #008a8a;
            font-size: 20px;
            font-family: calibri
        }


        .reports > tbody > tr > td {
            background-color: white;
        }

     
        .dt-button{
            background-color: #00c3ff !important;
            color: #fff !important;
            font-size: 14px !important;
            border-radius: 5px !important;
            padding-top: 5px !important;
            padding-bottom: 5px !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
            width: 107px;
            height: 32px;
        }

        .buttons-print{
            background-color: #12abda !important;
            color: #fff !important;
        }
        .buttons-excel{
            background-color: #0cb458 !important;
            color: #fff !important;
        }
        .buttons-csv{
            background-color: #0cb458 !important;
            color: #fff !important;
        }
        .buttons-pdf{
            background-color: #e42535 !important;
            color: #fff !important;
        }
        #disbursement-generated-by-batch-datatable > tbody > tr > td{
            vertical-align: middle
        }
    </style>
@endsection




{{-- import in this section your javascript files --}}
@section('page-js')
    <script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
    <script src="assets/plugins/bootstrap-sweetalert/sweetalert.min.js"></script>
    <script src="assets/js/demo/ui-modal-notification.demo.min.js"></script>
    <script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
    <script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
    <script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/js/demo/table-manage-default.demo.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/additional-methods.min.js"></script>
    

    
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.colVis.min.js"></script>

    <script src="https://cdn.jsdelivr.net/gh/ashl1/datatables-rowsgroup@fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js"></script>


    
    {{-- date range picker --}}
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    <script>
     
        $(document).ready(function(){

            // load cards
            load_cards = ()=>{

                // $.ajax({
                //         url:"{{route('kyc-all-reports')}}",
                //         type:'get',
                //         success:function(data){
                //             let result_json = JSON.parse(data);
                            
                //             $('#spti_value').html(result_json.count_spti);
                //             $('#ussc_value').html(result_json.count_ussc);
                //             $('#files_value').html(result_json.count_files);
                //             $('#records_value').html(result_json.count_records);
                            
                //         }                    
                //     })
            }

            load_cards()
    
    
            $is_uploading = false;
            // load datatable list of uploaded records
                load_datatable = $("#load-datatable").DataTable({
                                pageLength : 5,
                                destroy:true,                                
                                responsive:true,
                                ajax: {"url":"{{route('kyc.show')}}","type":'get'},
                                dom: 'lBfrtip',
                                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                "buttons": [
                                        {
                                            extend: 'collection',
                                            text: 'Export',
                                            buttons: [
                                                {
                                                    text: '<i class="fas fa-print"></i> PRINT',
                                                    title: 'Report: List Of Uploaded KYC Profiles',
                                                    extend: 'print',
                                                    footer: true,
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                    customize: function ( doc ) {
                                                        $(doc.document.body).find('h1').css('font-size', '15pt');
                                                        $(doc.document.body)
                                                            .prepend(
                                                                '<img src="{{url('assets/img/logo/DA-Logo.png')}}" width="10%" height="5%" style="display: inline-block" class="mt-3 mb-3"/>'
                                                        );
                                                        $(doc.document.body).find('table tbody td').css('background-color', '#cccccc');
                                                    },
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-excel"></i> EXCEL',
                                                    title: 'List Of Uploaded KYC Profiles',
                                                    extend: 'excelHtml5',
                                                    footer: true,
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    }
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-excel"></i> CSV',
                                                    title: 'List Of Uploaded KYC Profiles',
                                                    extend: 'csvHtml5',
                                                    footer: true,
                                                    fieldSeparator: ';',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    }
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                                    title: 'List Of Uploaded KYC Profiles',
                                                    extend: 'pdfHtml5',
                                                    footer: true,
                                                    message: '',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                }, 
                                            ]
                                            }
                                    ],
                                columns:[
                                        {data:'rsbsa_no',title:'RSBSA Number'},
                                        {   data:'fintech_provider',
                                            title:'Provider',
                                            orderable:false,
                                            render:function(data,type,row){
                                                return data == 'UMSI' ? 'USSC' : data;
                                            }                                    
                                        },
                                        {data:'full_name',title:'Name',orderable:false},
                                        {data:'address',title:'Address',orderable:false},
                                        {data:'account_number',title:'DBP Account Number',orderable:false},
                                        {data:'date_uploaded',title:'Date Uploaded'},
                                        {data:'region',title:'Region',visible:false}
                                        
                                ],
                                order: [[ 5, "desc" ]], 
                                

                            })

                    // summary files report
                     $("#file-summary-datatable").DataTable({
                                pageLength : 5,
                                destroy:true,                                
                                responsive:true,
                                ajax: {"url":"{{route('kyc-summary-files-report')}}","type":'get'},
                                dom: 'lBfrtip',
                                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                "buttons": [
                                        {
                                            extend: 'collection',
                                            text: 'Export',
                                            buttons: [
                                                {
                                                    text: '<i class="fas fa-print"></i> PRINT',
                                                    title: 'Report: Summary Of Uploaded Files and Records',
                                                    extend: 'print',
                                                    footer: true,
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                    customize: function ( doc ) {
                                                        $(doc.document.body).find('h1').css('font-size', '15pt');
                                                        $(doc.document.body)
                                                            .prepend(
                                                                '<img src="{{url('assets/img/logo/DA-Logo.png')}}" width="10%" height="5%" style="display: inline-block" class="mt-3 mb-3"/>'
                                                        );
                                                        $(doc.document.body).find('table tbody td').css('background-color', '#cccccc');
                                                    },
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-excel"></i> EXCEL',
                                                    title: 'Summary Of Uploaded Files and Records',
                                                    extend: 'excelHtml5',
                                                    footer: true,
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    }
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-excel"></i> CSV',
                                                    title: 'Summary Of Uploaded Files and Records',
                                                    extend: 'csvHtml5',
                                                    footer: true,
                                                    fieldSeparator: ';',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    }
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                                    title: 'Summary Of Uploaded Files and Records',
                                                    extend: 'pdfHtml5',
                                                    footer: true,
                                                    message: '',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                }, 
                                            ]
                                            }
                                    ],
                                columns:[
                                        {data:'total_files',title:'Total Files Saved',render: $.fn.dataTable.render.number(',')},                                 
                                        {data:'total_inserted',title:'Total Records Saved',render: $.fn.dataTable.render.number(',')},                                                                                
                                        {data:'date_uploaded',title:'Date Uploaded'},
                            
                                        
                                ],
                                order: [[ 2, "desc" ]]         
             
                                

                            });
                    
                    // file data reports datatable
                    $("#file-data-datatable").DataTable({
                                pageLength : 5,
                                destroy:true,                                
                                responsive:true,
                                ajax: {"url":"{{route('kyc-file-data-reports')}}","type":'get'},
                                dom: 'lBfrtip',
                                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                "buttons": [
                                        {
                                            extend: 'collection',
                                            text: 'Export',
                                            buttons: [
                                                {
                                                    text: '<i class="fas fa-print"></i> PRINT',
                                                    title: 'Report: List of Uploaded Files',
                                                    extend: 'print',
                                                    footer: true,
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                    customize: function ( doc ) {
                                                        $(doc.document.body).find('h1').css('font-size', '15pt');
                                                        $(doc.document.body)
                                                            .prepend(
                                                                '<img src="{{url('assets/img/logo/DA-Logo.png')}}" width="10%" height="5%" style="display: inline-block" class="mt-3 mb-3"/>'
                                                        );
                                                        $(doc.document.body).find('table tbody td').css('background-color', '#cccccc');
                                                    },
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-excel"></i> EXCEL',
                                                    title: 'List of Uploaded Files',
                                                    extend: 'excelHtml5',
                                                    footer: true,
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    }
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-excel"></i> CSV',
                                                    title: 'List of Uploaded Files',
                                                    extend: 'csvHtml5',
                                                    footer: true,
                                                    fieldSeparator: ';',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    }
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                                    title: 'List of Files Uploaded',
                                                    extend: 'pdfHtml5',
                                                    footer: true,
                                                    message: '',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                }, 
                                            ]
                                            }
                                    ],
                                columns:[
                                        {data:'region',title:'Region'},
                                        {data:'province',title:'Province'},
                                        {data:'fintech_provider',title:'Fintech Provider'},
                                        {data:'file_name',title:'File',orderable:false},
                                        {data:'total_inserted',title:'Total Records Saved',render: $.fn.dataTable.render.number(',').display,orderable:false},
                                        {data:'total_rows',title:'Total Records',render: $.fn.dataTable.render.number(','),orderable:false},

                                        {data:'date_uploaded',title:'Date Uploaded'},
      
                                ],             
                                order: [[ 6, "desc" ]]            

                            })
            
                // region and fintech partners report

                $("#region-fintech-datatable").DataTable({
                                pageLength : 5,
                                destroy:true,                                
                                responsive:true,
                                ajax: {"url":"{{route('kyc-region-fintech-reports')}}","type":'get'},
                                dom: 'lBfrtip',
                                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                "buttons": [
                                        {
                                            extend: 'collection',
                                            text: 'Export',
                                            buttons: [
                                                {
                                                    text: '<i class="fas fa-print"></i> PRINT',
                                                    title: 'Report: List of Total Records Uploaded By Region and Fintech Partners',
                                                    extend: 'print',
                                                    footer: true,
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                    customize: function ( doc ) {
                                                        $(doc.document.body).find('h1').css('font-size', '15pt');
                                                        $(doc.document.body)
                                                            .prepend(
                                                                '<img src="{{url('assets/img/logo/DA-Logo.png')}}" width="10%" height="5%" style="display: inline-block" class="mt-3 mb-3"/>'
                                                        );
                                                        $(doc.document.body).find('table tbody td').css('background-color', '#cccccc');
                                                    },
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-excel"></i> EXCEL',
                                                    title: 'List of Total Records Uploaded By Region and Fintech Partners',
                                                    extend: 'excelHtml5',
                                                    footer: true,
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    }
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-excel"></i> CSV',
                                                    title: 'List of Total Records Uploaded By Region and Fintech Partners',
                                                    extend: 'csvHtml5',
                                                    footer: true,
                                                    fieldSeparator: ';',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    }
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                                    title: 'List of Total Records Uploaded By Region and Fintech Partners',
                                                    extend: 'pdfHtml5',
                                                    footer: true,
                                                    message: '',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                }, 
                                            ]
                                            }
                                    ],
                                columns:[
                                        {data:'region',title:'Region'},                                        
                                        {data:'fintech_provider',title:'Fintech Provider',orderable:false},
                                        {data:'total_records_uploaded',title:'Total Records Uploaded',render: $.fn.dataTable.render.number(','),orderable:false},                                                                                
      
                                ],            
                                    

                            })


                // list of generated disbursement report by batch
                $("#disbursement-generated-by-batch-datatable").DataTable({
                                pageLength : 5,
                                destroy:true,                                                                                              
                                ajax: {"url":"{{route('list-of-generated-disbursement-by-file-name')}}","type":'get'},                               
                                columns:[
                                        {data:'file_name',title:'File',name:'file_name'},
                                        {data:'batch_number',title:'Batch Number',orderable:false},     
                                        {data:'total_records',title:'Total Records',render: $.fn.dataTable.render.number(','),orderable:false},                                                                                
                                        {data:'total_amount',title:'Total Amount',render:$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display,orderable:false},                                                                                                                                        
                                        {data:'date_approved',title:'Approval Date'}                                                                                                                                                     
                                ],                
                                rowsGroup:[0],

                                
                                footerCallback: function ( row, data, start, end, display ) {
                                    var api = this.api(), data;
                                                    
                                    var intVal = function ( i ) {
                                        return typeof i === 'string' ?
                                            i.replace(/[\₱,]/g, '')*1 :
                                            typeof i === 'number' ?
                                                i : 0;
                                    };
                                    
                                    // compute total amount
                                    total_amount = api.column( 3 ).data().reduce( function (a, b) {return (a)*1 + (b)*1;}, 0 );                                    
                                    $( api.column( 3 ).footer() ).html("Overall Total Amount:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(total_amount) );

                                    // compute total records
                                    total_records = api.column( 2 ).data().reduce( function (a, b) {return (a)*1 + (b)*1;}, 0 );                                    
                                    $( api.column( 2 ).footer() ).html("Overall Total no. of Records:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_records) );
                                            
                                },
                                order: [[ 4, "desc" ]]     
                            })

                  // list of generated disbursement report

                  $("#disbursement-generated-datatable").DataTable({
                                pageLength : 5,
                                destroy:true,                            
                                responsive:true,
                                ajax: {"url":"{{route('disbursement-generated-reports')}}","type":'get'},
                                dom: 'lBfrtip',
                                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                "buttons": [
                                        {
                                            extend: 'collection',
                                            text: 'Export',
                                            buttons: [
                                                {
                                                    text: '<i class="fas fa-print"></i> PRINT',
                                                    title: 'Report: List of Generated Disbursement',
                                                    extend: 'print',
                                                    footer: true,
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                    customize: function ( doc ) {
                                                        $(doc.document.body).find('h1').css('font-size', '15pt');
                                                        $(doc.document.body)
                                                            .prepend(
                                                                '<img src="{{url('assets/img/logo/DA-Logo.png')}}" width="10%" height="5%" style="display: inline-block" class="mt-3 mb-3"/>'
                                                        );
                                                        $(doc.document.body).find('table tbody td').css('background-color', '#cccccc');
                                                    },
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-excel"></i> EXCEL',
                                                    title: 'List of Generated Disbursement',
                                                    extend: 'excelHtml5',
                                                    footer: true,
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    }
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-excel"></i> CSV',
                                                    title: 'List of Generated Disbursement',
                                                    extend: 'csvHtml5',
                                                    footer: true,
                                                    fieldSeparator: ';',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    }
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                                    title: 'List of Generated Disbursement',
                                                    extend: 'pdfHtml5',
                                                    footer: true,
                                                    message: '',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                }, 
                                            ]
                                            }
                                    ],
                                columns:[
                                        {data:'region',title:'Region'},
                                        {data:'prov_name',title:'Province'},                                        
                                        {data:'name',title:'Name',orderable:false},
                                        
                                        {data:'total_amount',title:'Total Amount',render: $.fn.dataTable.render.number(',', '.', 2, '&#8369;').display,orderable:false},                                                                                
                                        {data:'generated_by',title:'Generated By',orderable:false},                                                                                
                                        {data:'generated_at',title:'Date Generated'},                                                                                
                                        {data:'approved_by',title:'Approved By',orderable:false},                                                                                
                                        {data:'date_approved',title:'Date Approved'}, 
                                        {data:'total_beneficiaries',title:'Total Beneficiaries',render: $.fn.dataTable.render.number(',').display,orderable:false},                                                                                
                                        {data:'dbp_batch_id',title:'Action',
                                        render:function(row,type,data){
                                        
                                            return "<button type='button' class='btn view-modal-btn btn-outline-primary'  generated_by ='"+data['generated_by']+"' dbp_batch_id='"+row+"' data-toggle='modal' data-target='#ViewModal'>"+
                                                        "<i class='fa fa-edit'></i> Show More"+
                                                    "</button>"
                                        }

                                        
                                        ,orderable:false}, 

                                ],                

                                footerCallback: function ( row, data, start, end, display ) {
                                    var api = this.api(), data;
                                                    
                                    var intVal = function ( i ) {
                                        return typeof i === 'string' ?
                                            i.replace(/[\₱,]/g, '')*1 :
                                            typeof i === 'number' ?
                                                i : 0;
                                    };
                                    
                                    total_beneficiaries = api.column( 8 ).data().reduce( function (a, b) {return (a)*1 + (b)*1;}, 0 );
                                    console.warn($.fn.dataTable.render.number(',').display(total_beneficiaries));
                                    $( api.column( 8 ).footer() ).html("Overall Total no. of Beneficiaries:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$.fn.dataTable.render.number(',').display(total_beneficiaries) );
                                            
                                },

                                order: [[ 4, "desc" ]]     
                            })
                            
                // show more details button
                $("#disbursement-generated-datatable").on('click','.view-modal-btn',function(){
                    dbp_batch_id = $(this).attr('dbp_batch_id');
                    generated_by = $(this).attr('generated_by');
                    $("#generated_by").text(generated_by);
                    $("#disbursement-generated-show-more-datatable").DataTable({
                                pageLength : 5,
                                destroy:true,                                
                                responsive:true,
                                ajax: {"url":"{{route('disbursement-generated-show-more',['dbp_batch_id'=>':id'])}}".replace(':id',dbp_batch_id),"type":'get'},
                                dom: 'lBfrtip',
                                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                "buttons": [
                                        {
                                            extend: 'collection',
                                            text: 'Export',
                                            buttons: [
                                                {
                                                    text: '<i class="fas fa-print"></i> PRINT',
                                                    title: 'Report: List Of Generated Disbursement KYC Profiles',
                                                    extend: 'print',
                                                    footer: true,
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                    customize: function ( doc ) {
                                                        $(doc.document.body).find('h1').css('font-size', '15pt');
                                                        $(doc.document.body)
                                                            .prepend(
                                                                '<img src="{{url('assets/img/logo/DA-Logo.png')}}" width="10%" height="5%" style="display: inline-block" class="mt-3 mb-3"/>'
                                                        );
                                                        $(doc.document.body).find('table tbody td').css('background-color', '#cccccc');
                                                    },
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-excel"></i> EXCEL',
                                                    title: 'List Of Generated Disbursement KYC Profiles',
                                                    extend: 'excelHtml5',
                                                    footer: true,
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    }
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-excel"></i> CSV',
                                                    title: 'List Of Generated Disbursement KYC Profiles',
                                                    extend: 'csvHtml5',
                                                    footer: true,
                                                    fieldSeparator: ';',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    }
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-pdf"></i> PDF',
                                                    title: 'List Of Generated Disbursement KYC Profiles',
                                                    extend: 'pdfHtml5',
                                                    footer: true,
                                                    message: '',
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    },
                                                }, 
                                            ]
                                            }
                                    ],
                                columns:[
                                        {data:'rsbsa_no',title:'RSBSA Number'},
                                        {   data:'fintech_provider',
                                            title:'Provider',
                                            orderable:false,
                                            render:function(data,type,row){
                                                return data == 'UMSI' ? 'USSC' : data;
                                            }                                    
                                        },
                                        {data:'full_name',title:'Name',orderable:false},
                                        {data:'address',title:'Address',orderable:false},
                                        {data:'account_number',title:'DBP Account Number',orderable:false},
                                        {data:'date_uploaded',title:'Date Uploaded'},
                                        {data:'region',title:'Region',visible:false}
                                        
                                ],
                                order: [[ 5, "desc" ]], 
                                

                            })

                        })


                // filter by region
                $("#filter-region").change(function(){
                    region_code = $("option:selected",this).val();
                    region_name = $("option:selected",this).text();

                    //filter all
                    if(region_code == ""){  
                        $("#file-data-datatable").DataTable().column(0).search('').draw(); // List  Of Uploaded Files
                        $("#load-datatable").DataTable().column(6).search('').draw();         //List Of Uploaded KYC Profiles          
                        $("#region-fintech-datatable").DataTable().column(0).search('').draw(); //List of Total Records Uploaded By Region and Fintech Partners
                        $("#disbursement-generated-datatable").DataTable().column(0).search('').draw(); //List of Generated Disbursement
                    }
                    // filter by value
                    else{
                        $("#file-data-datatable").DataTable().column(0).search(region_name).draw(); // List Of Uploaded Files
                        $("#load-datatable").DataTable().column(6).search(region_name).draw();    //List Of Uploaded KYC Profiles               
                        $("#region-fintech-datatable").DataTable().column(0).search(region_name).draw(); //List of Total Records Uploaded By Region and Fintech Partners
                        $("#disbursement-generated-datatable").DataTable().column().search(region_name).draw(); //List of Generated Disbursement

                    }
                     
                });
            

             


            
        })
    </script>

    <script>
           //filter by date range 
           $(function() {

            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
            }, cb);
            

            cb(start, end);

            });
        
        $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                    var min = new Date($("input[name=daterangepicker_start]").val()).toLocaleDateString();
                    var max = new Date($("input[name=daterangepicker_end]").val()).toLocaleDateString();
                    var date = new Date( data[5] ).toLocaleDateString();
                    var date_file_data_datable = new Date( data[5] ).toLocaleDateString();
                    var date_file_summary_datatable = new Date( data[2] ).toLocaleDateString();
                    // alert(data[5]);
            
                    if (
                        ( min === null && max === null ) ||
                        ( min === null && date <= max ) ||
                        ( min <= date   && max === null ) ||
                        ( min <= date   && date <= max )
                    ) {
                        return true;
                    }


                    if (
                        ( min === null && max === null ) ||
                        ( min === null && date_file_data_datable <= max ) ||
                        ( min <= date_file_data_datable   && max === null ) ||
                        ( min <= date_file_data_datable   && date_file_data_datable <= max )
                    ) {
                        return true;
                    }


                    if (
                        ( min === null && max === null ) ||
                        ( min === null && date_file_summary_datatable <= max ) ||
                        ( min <= date_file_summary_datatable   && max === null ) ||
                        ( min <= date_file_summary_datatable   && date_file_summary_datatable <= max )
                    ) {
                        return true;
                    }



                    return false;
                }
            );

        $(document).ready(function(){
           

    
            let table =  $("#load-datatable").DataTable();
            let file_data_datable =  $("#file-data-datatable").DataTable();
            let  file_summary_datatable =  $("#file-summary-datatable").DataTable();

           
            // filter function
            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {                                
                file_data_datable.draw();
                table.draw();
                file_summary_datatable.draw();
            });
        })
            

    </script>
@endsection






@section('content')

    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">KYC Reports </h1>
    <!-- end page-header -->


    {{-- card start here --}}
@foreach($action as $value)
    @if($value->permission == "View Content")
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-orange">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-file-excel fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 15px">Total Uploaded Files:</div>
                <div class="stats-number" id="files_value">0</div>
            </div>
        </div>
    </div>



    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-green">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-file-excel fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 15px">Total Uploaded Records:</div>
                <div class="stats-number" id="records_value">0</div>
            </div>
        </div>
    </div>


    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-blue">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-file-excel fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 15px">Total USSC Records:</div>
                <div class="stats-number" id="ussc_value" >0</div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-purple">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-file-excel fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 15px">Total SPTI  Records:</div>
                <div class="stats-number" id="spti_value" >0</div>
            </div>
        </div>
    </div>
</div>
    @endif
@endforeach
{{-- card end here --}}
    <!-- begin panel -->



    @foreach($action as $value)
        @if($value->permission == "View Content")
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 class="panel-title">Filter</h4>
            </div>
            {{-- KYC FORM PANEL --}}       
            <div class="panel-body">
        <div class="row">            
            <div class="panel panel-primary col-md-6" >
                <div class="panel-heading">Filter by Region</div>
                <div class="panel-body border">
                    <div class="form-group">
                    <label for=""></label>
                    <select  class="form-control filter-select" name="filter_region" id="filter-region">
                        <option value=""  selected>-- Select Region --</option>                        
                        @foreach ($get_region as $value)
                        <option value="{{$value->reg_code}}">{{$value->reg_name}}</option>                        
                            
                        @endforeach

                    </select>
                    </div>
                </div>
            </div>


            
            <div class="panel panel-primary col-md-6">
                <div class="panel-heading">Filter by Date</div>
                <div class="panel-body border" style="padding:32px">
                    <div class="form-group">
                    <label for=""></label>
                        <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span></span> <b class="caret"></b>
                        </div>
                    </div>
                </div>
            </div>

        </div>
            </div>
        </div>
        

                

        <div class="note note-success l-b-15">
            <div class="note-icon"><i class="fa fa-file-excel"></i></div>
                <div class="note-content">
                    <h4><b>List Of Uploaded Files</b></h4>                      
                </div>
        </div>
            <table id="file-data-datatable" class="table table-hover table-bordered reports" width="100%">            
                <thead>                                    
                </thead>
                <tbody>                
                </tbody>
            </table>

        <br><br>
        {{-- <div class="note note-success l-b-15">
            <div class="note-icon"><i class="fa fa-users"></i></div>
            <div class="note-content">
                <h4><b>List Of Uploaded KYC Profiles</b></h4>                      
            </div>
            </div>
           
            <table id="load-datatable" class="table table-hover table-bordered reports" width="100%">            
                <thead>                                    
                </thead>
                <tbody>                
                </tbody>
            </table>

        <br><br> --}}
        <div class="note note-success l-b-15">
            <div class="note-icon"><i class="fa fa-file-excel"></i></div>
                <div class="note-content">
                    <h4><b>Summary Of Uploaded Files and Records</b></h4>                      
                </div>
        </div>
            <table id="file-summary-datatable" class="table table-hover table-bordered reports" width="100%">            
                <thead>                                    
                </thead>
                <tbody>                
                </tbody>
            </table>
            
        <br><br>
        <div class="note note-success l-b-15">
                <div class="note-icon"><i class="fa fa-file-excel"></i></div>
                    <div class="note-content">
                        <h4><b>List of Total Records Uploaded By Region and Fintech Partners</b></h4>                      
                    </div>
            </div>
                <table id="region-fintech-datatable" class="table table-hover table-bordered reports" width="100%">            
                    <thead>                                    
                    </thead>
                    <tbody>                
                    </tbody>
                </table>
        <br><br>
                <div class="note note-success l-b-15">
                    <div class="note-icon"><i class="fa fa-file-excel"></i></div>
                        <div class="note-content">
                            <h4><b>List of Generated Disbursement</b></h4>                      
                        </div>
                </div>
                    <table id="disbursement-generated-datatable" class="table  table-bordered reports" width="100%">            
                        <thead>                                                                
                        </thead>
                        <tbody>                
                        </tbody>
                        <tfoot style="background-color: white" >
                          
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>                                                    
                        </tfoot>
                    </table>

            <br><br>
                    <div class="note note-success l-b-15">
                        <div class="note-icon"><i class="fa fa-file-excel"></i></div>
                            <div class="note-content">
                                <h4><b>List of Generated Disbursement By Batch Number</b></h4>                      
                            </div>
                    </div>
                        <table id="disbursement-generated-by-batch-datatable" class="table  table-bordered reports" width="100%">            
                            <thead>                                                                
                            </thead>
                            <tbody>                
                            </tbody>    
                            <tfoot style="background-color: white" >                          
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tfoot>               
                        </table>                      

            </div>
        </div>
        <br><br>
  
      
        
        @endif
    @endforeach
        



      <!-- #modal-view -->
      <div class="modal fade" id="ViewModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="width: 100%">
                <div class="modal-header" style="background-color: #007BFF">
                    <h4 class="modal-title" style="color: white">View Profile</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <form id="UpdateForm" method="post">
                    @csrf
                    
                    <div class="modal-body">
                        {{--modal body start--}}
                        <h2 id="ViewCategName" align="center"></h2>

                        
                        <div class="note note-success">
                            <div class="note-icon"><i class="fas fa-user"></i></div>
                            <div class="note-content">
                                <label style="display: block; text-align: center; font-weight:bold;  font-size:24px" id="generated_by">John Edcel Zenarosa</label>
                                <label style="display: block; text-align: center; font-weight:bold;  font-size:20px" id="name">Generated By</label>
                            </div>
                        </div>

                        <table id="disbursement-generated-show-more-datatable" class="table table-hover table-bordered reports" style="width:100%">            
                            <thead>                                    
                            </thead>
                            <tbody>                
                            </tbody>
                        </table>  
                    
                  

            

                            
                        {{--modal body end--}}
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Close</a>   
                    </div>
                </form>
            </div>
        </div>
    </div>


        {{-- </div> --}}
        <!-- end panel -->


@endsection
