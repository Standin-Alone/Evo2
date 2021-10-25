@extends('global.base')
@section('title', 'KYC Profiles')




{{-- import in this section your css files --}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css">
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


        
        #file-data-datatable > thead > tr > th  ,#load-datatable > thead > tr > th {
            color:white;
            background-color: #008a8a;
            font-size: 20px;
            font-family: calibri
        }

 

        #load-datatable> thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            padding: 5px !important;
        }           

              
        #file-summary-datatable > thead > tr > th  ,#load-datatable > thead > tr > th {
            color:white;
            background-color: #008a8a;
            font-size: 20px;
            font-family: calibri
        }


        #file-data-datatable > tbody > tr > td , #load-datatable > tbody > tr > td{
            background-color: white;
        }

        #file-summary-datatable > tbody > tr > td , #load-datatable > tbody > tr > td{
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

    <script>
     
        $(document).ready(function(){

            // load cards
            load_cards = ()=>{

                $.ajax({
                        url:"{{route('kyc-today-reports')}}",
                        type:'get',
                        success:function(data){
                            let result_json = JSON.parse(data);
                            
                            $('#spti_value').html(result_json.count_spti);
                            $('#ussc_value').html(result_json.count_ussc);
                            $('#files_value').html(result_json.count_files_today);
                            $('#records_value').html(result_json.count_records_today);
                            
                        }                    
                    })
            }

            load_cards()
    
    
            $is_uploading = false;
            // load datatable list of uploaded records
                load_datatable = $("#load-datatable").DataTable({
                                pageLength : 5,
                                destroy:true,
                                serverSide:true,
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
                                "order": [[ 5, "desc" ]], 
                                

                            })

                    // summary files report
                     $("#file-summary-datatable").DataTable({
                                pageLength : 5,
                                destroy:true,
                                serverSide:true,
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
                                        {data:'total_files',title:'Total Files',orderable:false},                                 
                                        {data:'total_inserted',title:'Total Records Saved'},                                                                                
                                        {data:'date_uploaded',title:'Date Uploaded'},
                            
                                        
                                ],
             
                                

                            });
                    
                    // file data reports datatable
                    $("#file-data-datatable").DataTable({
                                pageLength : 5,
                                destroy:true,
                                serverSide:true,
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
                                                    title: 'Report: List of Files Uploaded',
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
                                                    title: 'List of Files Uploaded',
                                                    extend: 'excelHtml5',
                                                    footer: true,
                                                    exportOptions: {
                                                        columns: ':visible'
                                                    }
                                                }, 
                                                {
                                                    text: '<i class="far fa-file-excel"></i> CSV',
                                                    title: 'List of Files Uploaded',
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
                                        {data:'total_inserted',title:'Total Records Saved',orderable:false},
                                        {data:'total_rows',title:'Total Records',orderable:false},

                                        {data:'date_uploaded',title:'Date Uploaded'},
      
                                ],                

                            })
            
                // filter by region
                $("#filter-region").change(function(){
                    region_code = $("option:selected",this).val();
                    region_name = $("option:selected",this).text();
                 
                    if(region_code == ""){
                        $("#file-data-datatable").DataTable().column(0).search('').draw();
                        $("#load-datatable").DataTable().column(6).search('').draw();                   
                    }else{
                        $("#file-data-datatable").DataTable().column(0).search(region_name).draw();
                        $("#load-datatable").DataTable().column(6).search(region_name).draw();                   
                    }
                     
                });
            // import file
            $("#ImportForm").validate({

            rules:{                      
                file:{
                    required:true,
                    accept: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
                }
            },
            messages:{                                
                file:{
                    required: '<div class="text-danger">Please select file to upload.</div>',
                    accept: '<div class="text-danger">Please upload valid files formats .xlsx, . xls only.</div>'
                }
            },
            submitHandler: function(){
                let fd = this;
                
                
                
                var spiel = document.createElement('div');
                    spiel.innerHTML = "Are you sure you want to import this file?";
                swal({
                title: "Wait!",
                content: spiel,
                icon: "warning",
                buttons: true,
                dangerMode: false,
                })
                .then((confirm) => {
                    let fd = new FormData();
                                        
                    fd.append('_token','{{csrf_token()}}')                    
                    fd.append('file',$("input[name='file']")[0].files[0])
                    $(".import-btn").prop('disabled',true)
                    // check if confirm
                    if (confirm) {         
                        // $is_uploading = true;
                        $(window).on('beforeunload', function(e){
                            return e.originalEvent.returnValue = "Your message here";
                        });
                        $(".import-btn").html('<i class="fas fa-circle-notch fa-spin"></i> Importing');                 
                        $.ajax({                      
                            url:"{{route('import-kyc')}}",
                            type:'post',
                            data: fd,
                            processData:false,
                            contentType:false,
                            success:function(response){             
                                //      
                                parses_result = JSON.parse(response)
                                total_rows_inserted = parses_result['total_rows_inserted'];
                                total_rows = parses_result['total_rows'];
                                
                                if(parses_result['message'] == 'true'){
                                    swal(total_rows_inserted + ' out of ' + total_rows + ' rows has been successfully inserted.', {
                                        icon: "success",
                                    }).then(()=>{                                                            
                                        load_cards()
                                        // check if it has error data;
                                        if(parses_result['error_data'].length > 0 ){
                                            $("#ErrorDataModal").modal('show');
                                            $("#error-datatable").DataTable({
                                                destroy:true,
                                                data:parses_result['error_data'],
                                                columns:[
                                                    {data:'rsbsa_no',title:'RSBSA Number'},                                                                                                        
                                                    {data:'fintech_provider',title:'Provider',orderable:false},
                                                    {title:'Name',orderable:false,render:function(data,type,row){
                                                        return row.first_name + ' ' + row.last_name;
                                                    }},                                                    
                                                    {data:'barangay',title:'Barangay',orderable:false},
                                                    {data:'municipality',title:'Municipality',orderable:false},
                                                    {data:'province',title:'Province',orderable:false},
                                                    {data:'region',title:'Region',orderable:false},
                                                    {data:'remarks',title:'Remarks',orderable:false}
                                                    

                                                ]
                                            });
                                        }

                                        $(window).unbind('beforeunload');
                                        $("#ImportForm")[0].reset();
                                        $(".import-btn").prop('disabled',false)      
                                        $(".import-btn").html('<i class="fas fa-cloud-download-alt "></i> Import');                                 
                                        $("#load-datatable").DataTable().ajax.reload();
                                        $("#file-data-datatable").DataTable().ajax.reload();
                                        $("#file-summary-datatable").DataTable().ajax.reload();
                                    });
                                }else if(parses_result['message'] == 'filename error'){
                                        swal("Error!Wrong file name format.", {
                                            icon: "error",
                                            });
                                            $(window).unbind('beforeunload');
                                            $("#ImportForm")[0].reset();
                                            $(".import-btn").prop('disabled',false) 
                                            $(".import-btn").html('<i class="fas fa-cloud-download-alt "></i> Import');                                               
                                }                                
                                else{
                                
                                    swal("Error!Wrong excel format.", {
                                            icon: "error",
                                        });
                                    $(window).unbind('beforeunload');
                                    $("#ImportForm")[0].reset();
                                    $(".import-btn").prop('disabled',false) 
                                    $(".import-btn").html('<i class="fas fa-cloud-download-alt "></i> Import');   
                                }
                                
                            },
                            error:function(response){
                                console.warn(response);
                                $(window).unbind('beforeunload');
                                $("#ImportForm")[0].reset();
                                $(".import-btn").prop('disabled',false)
                                $(".import-btn").html('<i class="fas fa-cloud-download-alt"></i> Import');   
                            }   
                        })
                        
                    } else {
                        $(".import-btn").prop('disabled',false)                            
                        swal("Operation Cancelled.", {
                            icon: "error",
                        });
                    }
                });
            }
            })


            if($is_uploading == true){
                window.onbeforeunload = function(){
                   return "Are you sure you want to refresh? You are still uploading the data.";
                }
            }
        })

    </script>
@endsection






@section('content')

    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">KYC Profiles </h1>
    <!-- end page-header -->


    {{-- card start here --}}
@foreach($action as $value)
    @if($value->permission == "View Content")
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-orange">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-file-excel fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 15px">Total Uploaded Files Today:</div>
                <div class="stats-number" id="files_value">0</div>
            </div>
        </div>
    </div>



    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-green">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-file-excel fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 15px">Total Uploaded Records Today:</div>
                <div class="stats-number" id="records_value">0</div>
            </div>
        </div>
    </div>


    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-blue">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-file-excel fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 15px">Total USSC Records Today:</div>
                <div class="stats-number" id="ussc_value" >0</div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-gradient-purple">
            <div class="stats-icon stats-icon-lg"><i class="fa fa-file-excel fa-fw"></i></div>
            <div class="stats-content">
                <div class="stats-title" style="font-size: 15px">Total SPTI  Records Today:</div>
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
        @if($value->permission == "Create New Content")
    
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 class="panel-title">KYC</h4>
            </div>
            {{-- KYC FORM PANEL --}}       
            <div class="panel-body">
                <div class="panel panel-primary col-md-6">
                    <div class="panel-heading">
                        <h4 class="panel-title">Import KYC Profiles</h4>
                    </div>
                    <form id="ImportForm" method="POST">
                        @csrf
                        <div class="panel-body border">


                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Import Excel</label><span style="color:red">*</span>
                                    <input type="file" name="file" accept=".xlsx" class="form-control" required="true">
                                </div>
                            </div>
                                    
                            <div class="col-lg-12">
                                <div class="form-group text-right">
                                    <button type='submit' class='btn btn-lime import-btn' >
                                        <i class='fa fa-cloud-download-alt'></i> Import
                                    </button>      
                                </div>
                            </div>                        
                        </div>
                    
                    </form>
                    <br>
                </div>



            


                <!-- #modal-list of not inserted data to database from excel -->
                <div class="modal fade" id="ErrorDataModal"  data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog" style="max-width: 70%">                    
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #ff5b57">
                                    <h4 class="modal-title update-modal-title" style="color: white">Unsuccessful Imported Data</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                                </div>
                                <div class="modal-body">
                                    {{--modal body start--}}          

                                    <table id="error-datatable" class="table table-hover" style="width:100%">            
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
                            </div>                    
                    </div>
                </div>



        
            </div>

        </div>
        <!-- end panel -->
        @endif
    @endforeach
  



    @foreach($action as $value)
        @if($value->permission == "View Content")
   
            <div class="panel panel-inverse ">
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

                
    {{-- <div class="panel panel-success">
        <div class="panel-heading">
            <h4 class="panel-title">KYC</h4>
        </div>
           
        <div class="panel-body">             --}}
            <div class="note note-success l-b-15">
                <div class="note-icon"><i class="fa fa-file-excel"></i></div>
                    <div class="note-content">
                        <h4><b>List Of Uploaded Files</b></h4>                      
                    </div>
            </div>
                <table id="file-data-datatable" class="table table-hover table-bordered" width="100%">            
                    <thead>                                    
                    </thead>
                    <tbody>                
                    </tbody>
                </table>

            <br><br>
            <div class="note note-success l-b-15">
                <div class="note-icon"><i class="fa fa-users"></i></div>
                <div class="note-content">
                    <h4><b>List Of Uploaded KYC Profiles</b></h4>                      
                </div>
                </div>
            
                <table id="load-datatable" class="table table-hover table-bordered" width="100%">            
                    <thead>                                    
                    </thead>
                    <tbody>                
                    </tbody>
                </table>

            <br><br>
            <div class="note note-success l-b-15">
                <div class="note-icon"><i class="fa fa-file-excel"></i></div>
                    <div class="note-content">
                        <h4><b>Summary Of Uploaded Files and Records</b></h4>                      
                    </div>
            </div>
                <table id="file-summary-datatable" class="table table-hover table-bordered" width="100%">            
                    <thead>                                    
                    </thead>
                    <tbody>                
                    </tbody>
                </table>
            </div>
        {{-- </div>
    </div> --}}
        @endif
    @endforeach
        


        {{-- </div> --}}
        <!-- end panel -->


@endsection
