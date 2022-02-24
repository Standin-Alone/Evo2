@extends('global.base')
@section('title', 'Returned Disbursement File Uploading')




{{-- import in this section your css files --}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css">    
	<link href="assets/plugins/dropzone/min/dropzone.min.css" rel="stylesheet" />
	
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.1/socket.io.js"></script>
    <script src="{{url('assets/js/socket.js')}}"></script>
    <script src="assets/plugins/dropzone/min/dropzone.min.js"></script>
    
    <script>
      
        var token = '{{Str::random(20)}}';
        socket().on("connect", function() {
                    
                //  get progress of uploading                

                socket().emit('join-room',token);

           
            });

            
            socket().on('progress',function(data){
                        
                    if(data.room == token){
                        
                        if(data.percentage == '100'){
                            $(".upload-btn").show();
                            $(".progress").hide();
                            $(".progress-load").css('width','0%')
                            $(".progress-load").html('0')
                        }else{
                            $(".progress-load").css('width',data.percentage+'%')
                            $(".progress-load").html(data.percentage+'%')
                        }
                    }
            
                
                    
                })
     
                    
        $(document).ready(function(){

    
    
            $is_uploading = false;
            // load datatable list of uploaded records
                // load_datatable = $("#load-datatable").DataTable({
                //                 pageLength : 5,
                //                 destroy:true,
                //                 serverSide:true,
                //                 responsive:true,
                //                 ajax: {"url":"{{route('kyc.show')}}","type":'get'},
                //                 dom: 'lBfrtip',
                //                 "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                //                 "buttons": [
                //                         {
                //                             extend: 'collection',
                //                             text: 'Export',
                //                             buttons: [
                //                                 {
                //                                     text: '<i class="fas fa-print"></i> PRINT',
                //                                     title: 'Report: List Of Uploaded KYC Profiles',
                //                                     extend: 'print',
                //                                     footer: true,
                //                                     exportOptions: {
                //                                         columns: ':visible'
                //                                     },
                //                                     customize: function ( doc ) {
                //                                         $(doc.document.body).find('h1').css('font-size', '15pt');
                //                                         $(doc.document.body)
                //                                             .prepend(
                //                                                 '<img src="{{url('assets/img/logo/DA-Logo.png')}}" width="10%" height="5%" style="display: inline-block" class="mt-3 mb-3"/>'
                //                                         );
                //                                         $(doc.document.body).find('table tbody td').css('background-color', '#cccccc');
                //                                     },
                //                                 }, 
                //                                 {
                //                                     text: '<i class="far fa-file-excel"></i> EXCEL',
                //                                     title: 'List Of Uploaded KYC Profiles',
                //                                     extend: 'excelHtml5',
                //                                     footer: true,
                //                                     exportOptions: {
                //                                         columns: ':visible'
                //                                     }
                //                                 }, 
                //                                 {
                //                                     text: '<i class="far fa-file-excel"></i> CSV',
                //                                     title: 'List Of Uploaded KYC Profiles',
                //                                     extend: 'csvHtml5',
                //                                     footer: true,
                //                                     fieldSeparator: ';',
                //                                     exportOptions: {
                //                                         columns: ':visible'
                //                                     }
                //                                 }, 
                //                                 {
                //                                     text: '<i class="far fa-file-pdf"></i> PDF',
                //                                     title: 'List Of Uploaded KYC Profiles',
                //                                     extend: 'pdfHtml5',
                //                                     footer: true,
                //                                     message: '',
                //                                     exportOptions: {
                //                                         columns: ':visible'
                //                                     },
                //                                 }, 
                //                             ]
                //                             }
                //                     ],
                //                 columns:[
                //                         {data:'rsbsa_no',title:'RSBSA Number'},
                //                         {   data:'fintech_provider',
                //                             title:'Provider',
                //                             orderable:false,
                //                             render:function(data,type,row){
                //                                 return data == 'UMSI' ? 'USSC' : data;
                //                             }                                    
                //                         },
                //                         {data:'full_name',title:'Name',orderable:false},
                //                         {data:'address',title:'Address',orderable:false},
                //                         {data:'account_number',title:'DBP Account Number',orderable:false},
                //                         {data:'date_uploaded',title:'Date Uploaded'},
                //                         {data:'region',title:'Region',visible:false}
                                        
                //                 ],
                //                 order: [[ 5, "desc" ]], 
                                

          })


    </script>

    <script>
        
         $(".upload-btn").click(function(){


            if(myDropzone.files.length == 0){               
                            
                            swal("Please upload atleast 1 file", {
                                icon: "error",
                            });
                                                            
                            return false; //if an error happened stop here kaya return false hnd na dapat mag tuloy sa baba.
            }else{


                // upload file here
                swal({
                title: "Do you really want to upload these files?",                
                icon: "warning",
                buttons: true,
                dangerMode: false,
                })
                .then((confirm) => {
                
                    if(confirm){
                        $(".upload-btn").hide();
                        $(".progress").show();
                        
                        var myDropzone_status   = [];            
                        if(myDropzone.files.length == 0){               
                            
                            swal("Please upload atleast 1 file", {
                                icon: "error",
                            });
                                                            
                            return false; //if an error happened stop here kaya return false hnd na dapat mag tuloy sa baba.
                        } else {

                            
                            myDropzone.processQueue();
                        }

                        $.each(myDropzone.files, function(k,v){
                            myDropzone_status.push(v.status);
                        });

                        console.log(myDropzone.files);

                    }else{
                        


                    }
                });


            }
   

        
            
            
            

        })

        Dropzone.autoDiscover = false;
        
        Dropzone.options.uploadingDropzone = {
            autoDiscover: false,
            maxFilesize: 30,
            maxFiles: 30,
            addRemoveLinks: true,
            timeout: 500000,
            dictRemoveFile: 'x',
            uploadMultiple: true,                 
            dictDefaultMessage: "<span>Drop files here or click to upload</span>",            
            paramName: "dbp_returned_file",
            autoProcessQueue: false,
            acceptedFiles: '.txt',  
            error: function(file){

            console.warn(file)
            if (!file.accepted){
                swal("Something went wrong!", {
                    icon: "error",
                });
                file.previewElement.remove();
            }

        },        
            sending: function(file, xhr, formData){       
                console.warn('processing')                
                formData.append('token', token);
            
            },     
            success: function(file, response){
                swal("Files Successfully uploaded", {
                    icon: "success",
                });
            }, 
            complete:function(file){
                swal("Files Successfully uploaded", {
                    icon: "success",
                }); 
                myDropzone.removeFile(file);
            
            } 
        }   
      
        var base_url = "{{url('/')}}";
        var myDropzone = new Dropzone("div#uploadingDropzone", { url: base_url + "/returned-disbursement/upload-file" ,headers: {
            'x-csrf-token': "{{csrf_token()}}",
        },});

        

    </script>
@endsection






@section('content')

    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header"> File uploading </h1>
    <!-- end page-header -->


    <!-- begin row -->
        <div class="row">
            <!-- begin col-8 -->
            <div class="col-lg-4">
                <!-- begin panel -->
                <div class="panel w-100 panel-inverse">					
                    <div class="panel-body ">

                        <div id="uploadingDropzone" class="dropzone">
                            
                            <div class="dz-message needsclick">
                                Drop files <b>here</b> or <b>click</b> to upload.<br />                                       
                            </div>
                            
                        </div>
                        <br>
                        <button  class='btn btn-lime upload-btn  w-100 ' >
                            <i class='fa fa-cloud-download-alt'></i> Upload and Ingest
                        </button>     

                        <br>
                        <div class="progress rounded-corner  active " style="height:50px;width:100%;display:none">
                            <div class="progress-bar bg-green progress-bar-striped progress-bar-animated  progress-load"  >
                
                            </div>
                        </div>
                    </div>						
                </div>
                <!-- end panel -->
            </div>
            <!-- end col-8 -->		


            {{-- table start here --}}
            <!-- begin col-8 -->
            <div class="col-lg-8">
                <!-- begin panel -->
                <div class="panel w-90 panel-inverse">					
                    <div class="panel-body ">

                                              
                    </div>						
                </div>
                <!-- end panel -->
            </div>
            <!-- end col-8 -->		
        </div>
        <!-- end row -->




        

@endsection
