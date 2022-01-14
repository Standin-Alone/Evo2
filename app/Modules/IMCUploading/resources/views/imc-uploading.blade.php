@extends('global.base')
@section('title', 'KYC Profiles')




{{-- import in this section your css files --}}
@section('page-css')
    <link href="{{url('assets/plugins/animate/animate.min.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/gritter/css/jquery.gritter.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css">

    	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	{{-- <link href="assets/plugins/dropzone/min/dropzone.min.css" rel="stylesheet" /> --}}
	<!-- ================== END PAGE LEVEL STYLE ================== -->
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
        },
        
        input[type="checkbox"] {
            cursor: pointer;
        },
        .no-click {
                pointer-events: none;
            }
    </style>
@endsection




{{-- import in this section your javascript files --}}
@section('page-js')
    <script src="{{url('assets/plugins/gritter/js/jquery.gritter.js')}}"></script>
    <script src="{{url('assets/plugins/bootstrap-sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{url('assets/js/demo/ui-modal-notification.demo.min.js')}}"></script>
    <script src="{{url('assets/plugins/DataTables/media/js/jquery.dataTables.js')}}"></script>
    <script src="{{url('assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{url('assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('assets/js/demo/table-manage-default.demo.min.js')}}"></script>

   

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

    {{-- <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.1/socket.io.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.0/socket.io.js"></script>
    
    

    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    {{-- <script src="{{url('assets/plugins/dropzone/min/dropzone.min.js')}}"></script>
	<script src="{{url('assets/plugins/highlight/highlight.common.js')}}"></script>
	<script src="{{url('assets/js/demo/render.highlight.js')}}"></script> --}}
    
	<!-- ================== END PAGE LEVEL JS ================== -->

    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/pgv/backend-script.js"></script> --}}
    

    <script>
        // for https websocket
        // var socket = io.connect('wss://devsysadd.da.gov.ph:7980',{ transports: ['websocket','polling'],allowEIO3:true,rejectUnauthorized: true});
        // var socket = io.connect('wss://devsysadd.da.gov.ph/evoucher',{ transports: ['websocket','polling'],allowEIO3:true,rejectUnauthorized: true});
        // for http websocket
        // var socket = io('127.0.0.1:7980',{ transports: ['websocket','polling'],allowEIO3:true});
        // var get_width = 0;

   
       

        


        // start websocket
         
   
        //  connect to websocket
        
        
        // socket.on("connect", function() {
        //         //  get progress of uploading
        //         console.warn('connected');

                                  
        //         socket.on('progress',function(data){
        //             // console.warn(data);
        //             if(data.room == "{{session('uuid')}}"){
                      
        //                 if(data.percentage == '100%'){
        //                     $(".progress-load").css('width','0%')
        //                     $(".progress-load").html('0%')
        //                 }else{
        //                     $(".progress-load").css('width',data.percentage)
        //                     $(".progress-load").html(data.percentage)

        //                 }
        //             }
               
                
                    
        //         })
       

                
        //     socket.emit('room',"{{session('uuid')}}"); 

          
        //     if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
          
          
        //             console.warn('connected client side');
        //             socket.emit('reset',['false']); 
          
                
        
            
        //         $(".progress-load").css('width','0%')    
        //     }


            
          
        // });





        $(document).ready(function(){
      
            
            $("#check-all").click(function(){
                $("input[type=check-all]").prop('checked',this.checked);
            })

            $("#ingest-file-datatable").DataTable({
                                pageLength : 5,
                                destroy:true,
                                serverSide:true,
                                responsive:true,
                                ajax: {"url":"{{route('get-ingest-files')}}","type":'get'},
                                dom: 'lBfrtip',
                                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                "buttons": [
                                        {
                                            extend: 'collection',
                                            text: 'Export',
                                            buttons: [
                                                {
                                                    text: '<i class="fas fa-print"></i> PRINT',
                                                    title: 'Report: List of Files to Ingest',
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
                                        {data:'ingest_file_id',title:'&nbsp;',                           
                                        orderable:false,                                        
                                        render:function(data,type,row){

                                            return   '<div class="checkbox checkbox-css">'+
                                                            '<input type="checkbox" id="checkbox'+row['ingest_file_id']+'"   class="permission_chk"  value="'+row['file_name']+'"  />"'+
                                                            '<label for="checkbox'+row['ingest_file_id']+'"></label>'+
                                                        '</div>'   
                                        }},    
                                        {data:'file_name',title:'File'},     
                                        {data:'total_inserted',title:'Total Records Saved'},                                        
                                        {data:'total_rows',title:'Total Rows'},                                        
                                        {data:'date_created',title:'Date Uploaded'},
                                     
                            
                                        
                                ],                     
                                order: [[ 4, "desc" ]]                                                  
                            });


            $("#ingest-file-datatable").on('change','input[type=checkbox]',function(){
                
                $(this).closest('tbody tr').toggleClass('selected');
            })


        
         
         
            // ingest files
            $(document).on('click','.ingest-btn',function(){
                file_name = [];
           
                $("input[type=checkbox]:checked").each(function(){
                
                    file_name.push($(this).val());
                })

              
                

                

                if(file_name.length > 0 ){
                        
                    let payload = {
                        file_name:file_name,
                        _token :'{{csrf_token()}}'
                    }
                    var spiel = document.createElement('div');
                                    spiel.innerHTML = "Are you sure you want to ingest these files?";
                    swal({
                        title: "Wait!",
                        content: spiel,
                        icon: "warning",
                        buttons: true,
                        dangerMode: false,
                        })
                        .then((confirm) => {
                    
                            if(confirm){
                                window.onbeforeunload = function(){
                                return "Are you sure you want to refresh? You are still uploading the data.";
                                }   

                          
                                
                                $(".ingest-btn").prop('disabled',true);
                                $(".ingest-btn").html('<i class="fas fa-circle-notch fa-spin"></i> Ingesting');                 
                                $.ajax({                      
                                    url:"{{route('ingest-file')}}",
                                    type:'post',
                                    data: payload,                                                   
                                    success:function(response){
                                        parses_result = JSON.parse(response)
                                        window.onbeforeunload = null;
                                        if(parses_result['message'] == 'true'){
                                     

                                            swal('Files has been successfully uploaded.', {
                                                    icon: "success",
                                                }).then(()=>{                                                            
                                                                     
                                                    
                                                $(".progress-load").css('width','0%')    
                                        
                                            if(parses_result['error_data'].length != 0){

                                                console.warn(parses_result['error_data'].length)
                                                $("#ErrorDataModal").modal('show');
                                                $("#error-datatable").DataTable({
                                                    destroy:true,
                                                    data:parses_result['error_data'].length == 1 ?parses_result['error_data'][0] : parses_result['error_data'] ,
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
                                                        {data:'remarks',title:'Remarks',orderable:false},
                                                        {data:'file_name',title:'',visible:false},                                                                                                        
                                                    ],
                                                    drawCallback:function(data){
                                                                let api = this.api();
                                                                let rows = api.rows({page:'current'}).nodes();
                                                                let last = null ;

                                                                api.column(8,{page:"current"})
                                                                    .data()
                                                                    .each((group,i)=>{
                                                                        
                                                                        console.warn(group);
                                                                            if(last != group && group != null){
                                                                                $(rows).eq(i).before('<tr  class="bg-warning font-weight-bold  text-white h1 " ><td colspan="8" >'+group+'</td></tr>')
                                                                                last = group;
                                                                            }
                                                                    });
                                                            },     
                                                });
                                            }   

                                            $("#ingest-file-datatable").DataTable().ajax.reload();
                                            $(".ingest-btn").html('<i class="fas fa-cloud-download-alt "></i> Ingest');                                               
                                            $(".ingest-btn").prop('disabled',false)                                                                        
                                            });

                                              
                                        }else{
                                            swal("Error!Something went wrong", {
                                                            icon: "error",
                                                        });
                                            $("#ingest-file-datatable").DataTable().ajax.reload();
                                            $(".ingest-btn").html('<i class="fas fa-cloud-download-alt "></i> Ingest');                                               
                                            $(".ingest-btn").prop('disabled',false)                                            
                                            window.onbeforeunload = null;                                                                                        
                                        }
                                        
                                    },
                                    error:function(error){

                                  

                                        swal("Error!Something went wrong", {
                                                            icon: "error",
                                                        });
                                                        
                                        $("#ingest-file-datatable").DataTable().ajax.reload();
                                        $(".ingest-btn").html('<i class="fas fa-cloud-download-alt "></i> Ingest');                                               
                                        $(".ingest-btn").prop('disabled',false)    
                                        
                                        window.onbeforeunload = null; 
                                    }
                                });


                            }else{
                                swal("Operation cancelled.", {
                                                            icon: "error",
                                                        });
                                
                                $(".ingest-btn").html('<i class="fas fa-cloud-download-alt "></i> Ingest');                                               
                                $(".ingest-btn").prop('disabled',false)   
                                window.onbeforeunload = null; 
                            }

                            
                        });
                


                }else{

                    swal("Please select files first.", {
                            icon: "warning",
                        });
                    
                }

                

            });

            // check if the page is reloading when user ingest
   
                
                  
     

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
                                spiel.innerHTML = "Are you sure you want to upload these files?";
                            swal({
                            title: "Wait!",
                            content: spiel,
                            icon: "warning",
                            buttons: true,
                            dangerMode: false,
                            })
                            .then((confirm) => {
                                let fd = new FormData();
                                                    
                                fd.append('_token','{{csrf_token()}}');
                                
                        
                                for (var i = 0; i < $("input[name='file']")[0].files.length  ; ++i) {
                                    fd.append('file[]', $("input[name='file']")[0].files[i]);
                                }

                                
                                $(".import-btn").prop('disabled',true)
                                // check if confirm
                                if (confirm) {         
                                    // $is_uploading = true;
                                    $(window).on('beforeunload', function(e){
                                        return e.originalEvent.returnValue = "Your message here";
                                    });
                                    $(".import-btn").html('<i class="fas fa-circle-notch fa-spin"></i> Uploading');                 
                                    $.ajax({                      
                                        url:"{{route('upload-file-only')}}",
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
                                                swal('Files has been successfully uploaded.', {
                                                    icon: "success",
                                                }).then(()=>{                                                            
                                                    // load_cards()                            
                                            

                                                    $(window).unbind('beforeunload');
                                                    $("#ImportForm")[0].reset();
                                                    $(".import-btn").prop('disabled',false)      
                                                    $(".import-btn").html('<i class="fas fa-cloud-download-alt "></i> Upload');                                 
                                                    $("#ingest-file-datatable").DataTable().ajax.reload();
                                                    
                                                });
                                            }else if(parses_result['message'] == 'filename error'){
                                                    swal("Error!Wrong file name format.", {
                                                        icon: "error",
                                                        });
                                                        $(window).unbind('beforeunload');
                                                        $("#ImportForm")[0].reset();
                                                        $(".import-btn").prop('disabled',false) 
                                                        $(".import-btn").html('<i class="fas fa-cloud-download-alt "></i> Upload');                                               
                                            }
                                            else if(parses_result['message'] == 'Some files is already exist.'){
                                                    swal("Files is already exist.", {
                                                        icon: "warning",
                                                        });
                                                        $(window).unbind('beforeunload');
                                                        $("#ImportForm")[0].reset();
                                                        $(".import-btn").prop('disabled',false) 
                                                        $(".import-btn").html('<i class="fas fa-cloud-download-alt "></i> Upload');  
                                                        $("#ingest-file-datatable").DataTable().ajax.reload();                                             
                                            } 
                                            else if(parses_result['message'] == 're-upload'){
                                                    swal("Files has been successfully re uploaded.", {
                                                        icon: "success",
                                                        });
                                                        $(window).unbind('beforeunload');
                                                        $("#ImportForm")[0].reset();
                                                        $(".import-btn").prop('disabled',false) 
                                                        $(".import-btn").html('<i class="fas fa-cloud-download-alt "></i> Upload');  
                                                        $("#ingest-file-datatable").DataTable().ajax.reload();                                             
                                            }                      
                                            else{
                                            
                                                swal("Error!Wrong excel format.", {
                                                        icon: "error",
                                                    });
                                                $(window).unbind('beforeunload');
                                                $("#ImportForm")[0].reset();
                                                $(".import-btn").prop('disabled',false) 
                                                $(".import-btn").html('<i class="fas fa-cloud-download-alt "></i> Upload');   
                                            }
                                            
                                        },
                                        error:function(response){
                                            console.warn(response);
                                            $(window).unbind('beforeunload');
                                            $("#ImportForm")[0].reset();
                                            $(".import-btn").prop('disabled',false)
                                            $(".import-btn").html('<i class="fas fa-cloud-download-alt"></i> Upload');   
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
        })

    </script>
@endsection






@section('content')

    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">KYC Profiles </h1>
    <!-- end page-header -->


    <!-- begin panel -->
    
    @foreach($action as $value)
        @if($value->permission == "Create New Content")
    
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 class="panel-title">File Upload KYC</h4>
            </div>
            {{-- KYC FORM PANEL --}}       
            <div class="panel-body">
            <div class="alert alert-warning fade show">
                    
                    <strong>REMINDER {{session('progress')}} ! </strong>
                    Please don't  <a href="#" class="alert-link">CLOSE or RELOAD</a> the page when ingesting;
                   
                </div>

            <div class="panel-body">
                <div class="panel panel-primary col-md-6">
                    <div class="panel-heading">
                        <h4 class="panel-title">Upload KYC Excel Files</h4>
                    </div>
                    <form id="ImportForm" method="POST">
                        @csrf
                        <div class="panel-body border">
                            


                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Upload Excel</label><span style="color:red">*</span>
                                    <input type="file" name="file" accept=".xlsx" class="form-control" multiple='true' required="true">
                                </div>
                            </div>
                                    
                            <div class="col-lg-12">
                                <div class="form-group text-right">
                                    <button type='submit' class='btn btn-lime import-btn' >
                                        <i class='fa fa-cloud-download-alt'></i> Upload
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




            <div class="note note-success l-b-15">
                <div class="note-icon"><i class="fa fa-file-excel"></i></div>
                    <div class="note-content">
                        <h4><b>List Of Files to Ingest</b></h4>                      
                    </div>
            </div>
                <table id="ingest-file-datatable" class="table table-hover table-bordered" width="100%">            
                    <thead>                                    
                    </thead>
                    <tbody>                
                    </tbody>
                </table>

                <div class="progress rounded-corner  active  " style="height:50px">
                    <div class="progress-bar bg-green progress-bar-striped progress-bar-animated  progress-load"  >
          
                    </div>
                  </div>
            <div class="modal-footer">
              
                    <button class="btn btn-primary ingest-btn">Ingest</button>                                
                </div>
        </div>
        <!-- end panel -->
        @endif
    @endforeach
  




        {{-- </div> --}}
        <!-- end panel -->


@endsection
