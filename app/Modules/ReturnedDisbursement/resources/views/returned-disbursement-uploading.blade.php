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


        
        #dbp-returned-files-show-more-datatable > thead > tr > th  ,#load-datatable > thead > tr > th {
            color:white;
            background-color: #008a8a;
            font-size: 20px;
            font-family: calibri
        }

 

        #load-datatable> thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            padding: 5px !important;
        }           

              
        #list-of-ingested-files-datatable > thead > tr > th  ,#load-datatable > thead > tr > th {
            color:white;
            background-color: #008a8a;
            font-size: 20px;
            font-family: calibri
        }


        #dbp-returned-files-show-more-datatable > tbody > tr > td , #load-datatable > tbody > tr > td{
            background-color: white;
        }

        #list-of-ingested-files-datatable > tbody > tr > td , #load-datatable > tbody > tr > td{
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
    {{-- <script src="{{url('assets/js/socket.js')}}"></script> --}}
    <script src="assets/plugins/dropzone/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    
    <script>
      
        var token = '{{Str::random(20)}}';
        // socket().on("connect", function() {                    
        //         //  get progress of uploading               
        //         socket().emit('join-room',token);           
        //     });

            
        // socket().on('progress',function(data){
                    
        //         if(data.room == token){
        //             $(".progress-load").css('width',data.percentage+'%')
        //             $(".progress-load").html(data.percentage+'%')
        //             $(".filename-label").html(data.filename+' is now uploading');
        //             // if(data.percentage == '100'){
                        
        //             //     // $(".progress-load").css('width','0%')
        //             //     // $(".progress-load").html('0')
        //             //     $(".progress-load").css('width',data.percentage+'%')
        //             //     $(".progress-load").html(data.percentage+'%')
        //             // }else{
        //             //     $(".progress-load").css('width',data.percentage+'%')
        //             //     $(".progress-load").html(data.percentage+'%')
        //             // }
        //         }
        
            
                
        //     })
     
                    
        $(document).ready(function(){

    
    
            $is_uploading = false;
            // load datatable list of ingested files
            $("#list-of-ingested-files-datatable").DataTable({
                                pageLength : 5,
                                destroy:true,
                                serverSide:true,
                                responsive:true,
                                ajax: {"url":"{{route('list-of-ingested-files-datatable')}}","type":'get'},
                                dom: 'lBfrtip',
                                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                "buttons": [
                                        {
                                            extend: 'collection',
                                            text: 'Export',
                                            buttons: [
                                                {
                                                    text: '<i class="fas fa-print"></i> PRINT',
                                                    title: 'Report: List of IMC Files',
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
                                        {data:'file_name',title:'File'},                                             
                                        {data:'shortname',title:'Program'},   
                                        {data:'agency_shortname',title:'Agency'},   
                                        {data:'total_inserted',title:'Total Records Saved',render: $.fn.dataTable.render.number(',').display,orderable:false},
                                        {data:'total_rows',title:'Total Records',render: $.fn.dataTable.render.number(','),orderable:false},                              
                                        {data:'date_uploaded',title:'Date Uploaded'},
                                        {data:'return_file_id',title:'Action',
                                        render:function(row,type,data){
                                                
                                        return ( 

                                                

                                                (   
                                                (data['total_inserted'] != data['total_rows'] ) && data['total_inserted'] != 0 ?
                                                "<button type='button' class='btn view-modal-btn btn-outline-primary'   uploaded_by='"+data['created_by'] +"' return_file_id='"+row+"' data-toggle='modal' data-target='#ViewModal'>"+
                                                    "<i class='fa fa-eye'></i> Show More"+
                                                "</button> \t"+

                                                "<button type='button' class='btn error-modal-btn btn-outline-warning'   uploaded_by='"+data['created_by'] +"' file_name='"+data['file_name']+"' data-toggle='modal' data-target='#ErrorDataModal'>"+
                                                    "<i class='fa fa-eye'></i> Show Error Logs"+
                                                "</button>"   
                                                :           
                                                "<button type='button' class='btn view-modal-btn btn-outline-primary'   uploaded_by='"+data['created_by'] +"' return_file_id='"+row+"' data-toggle='modal' data-target='#ViewModal'>"+
                                                    "<i class='fa fa-eye'></i> Show More"+
                                                "</button> \t"                                   
                                                )
                                                )
                                        }}
                            
                                        
                                ],                     
                                order: [[ 5, "desc" ]]                                                  
                            });

         // show more details button
                $("#list-of-ingested-files-datatable").on('click','.view-modal-btn',function(){
                    return_file_id = $(this).attr('return_file_id');
                    created_by = $(this).attr('uploaded_by');
                    $("#uploaded_by").text(created_by);
                    $("#dbp-returned-files-show-more-datatable").DataTable({
                                pageLength : 5,
                                destroy:true,                                
                                responsive:true,
                                ajax: {"url":"{{route('dbp-returned-files-show-more',['return_file_id'=>':id'])}}".replace(':id',return_file_id),"type":'get'},
                                // dom: 'lBfrtip',
                                // "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                                // "buttons": [
                                //         {
                                //             extend: 'collection',
                                //             text: 'Export',
                                //             buttons: [
                                //                 {
                                //                     text: '<i class="fas fa-print"></i> PRINT',
                                //                     title: 'Report: List Of Returned Disbursement Profiles',
                                //                     extend: 'print',
                                //                     footer: true,
                                //                     exportOptions: {
                                //                         columns: ':visible'
                                //                     },
                                //                     customize: function ( doc ) {
                                //                         $(doc.document.body).find('h1').css('font-size', '15pt');
                                //                         $(doc.document.body)
                                //                             .prepend(
                                //                                 '<img src="{{url('assets/img/logo/DA-Logo.png')}}" width="10%" height="5%" style="display: inline-block" class="mt-3 mb-3"/>'
                                //                         );
                                //                         $(doc.document.body).find('table tbody td').css('background-color', '#cccccc');
                                //                     },
                                //                 }, 
                                //                 {
                                //                     text: '<i class="far fa-file-excel"></i> EXCEL',
                                //                     title: 'List Of Returned Disbursement Profiles',
                                //                     extend: 'excelHtml5',
                                //                     footer: true,
                                //                     exportOptions: {
                                //                         columns: ':visible'
                                //                     }
                                //                 }, 
                                //                 {
                                //                     text: '<i class="far fa-file-excel"></i> CSV',
                                //                     title: 'List Of Returned Disbursement Profiles',
                                //                     extend: 'csvHtml5',
                                //                     footer: true,
                                //                     fieldSeparator: ';',
                                //                     exportOptions: {
                                //                         columns: ':visible'
                                //                     }
                                //                 }, 
                                //                 {
                                //                     text: '<i class="far fa-file-pdf"></i> PDF',
                                //                     title: 'List Of Returned Disbursement Profiles',
                                //                     extend: 'pdfHtml5',
                                //                     footer: true,
                                //                     message: '',
                                //                     exportOptions: {
                                //                         columns: ':visible'
                                //                     },
                                //                 }, 
                                //             ]
                                //             }
                                //     ],
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
                                        {data:'dbp_status',title:'Status'},
                                        {data:'region',title:'Region',visible:false}
                                        
                                ],
                                order: [[ 5, "desc" ]], 
                                

                            })

                        })


            // show error logs button
            $("#list-of-ingested-files-datatable").on('click','.error-modal-btn',function(){
                

                filename = $(this).attr('file_name');
                clean_filename = filename.split(".")[0];                
                created_by = $(this).attr('uploaded_by');
                $("#uploaded_by").text(created_by);
                

                $("#error-datatable").DataTable({
                                destroy:true,
                                ajax: {"url":"{{route('dbp-return-error-logs',['filename'=>':id'])}}".replace(':id',clean_filename),"type":'get'},                                
                                columns:[
                                    {data:'rsbsa_no',title:'RSBSA Number'},                                                                                                        
                                    {data:'fintech_provider',title:'Provider',orderable:false},
                                    {title:'Name',orderable:false,render:function(data,type,row){
                                        return row.first_name + ' ' + row.last_name;
                                    }},                                                    
                                    {data:'barangay',title:'Barangay',orderable:false},
                                    {data:'city_municipality',title:'Municipality',orderable:false},
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
            })
                                

          })


    </script>

    <script>
        
         $(".upload-btn").click(function(){

            var myDropzone_status   = [];      
            
            
            if(myDropzone.files.length == 0){               
                $(".upload-btn").html("<i class='fa fa-cloud-download-alt'></i> Upload and Ingest");
                $(".upload-btn").prop('disabled',false);
                            
                Swal.fire(
                        'Message',
                        'Please upload at least 1 file.',
                        'error'
                        )  ;
                                                            
                return false; //if an error happened stop here kaya return false hnd na dapat mag tuloy sa baba.
            }else{

                error_file_count = 0;
                myDropzone.files.forEach((item)=>{
                    if(!item.upload.filename.includes('USSC') && !item.upload.filename.includes('DISRET')){
                                               
                  
                        error_file_count++;
                    }
                })


               
                if(error_file_count == 0){


                    Swal.fire({
                title: 'Select Agency',
                input: 'select',
                inputOptions: {
                    @foreach ($get_agency as $agency_value )
                    {{ $agency_value->agency_id }}:'{{ $agency_value->agency_name }}',
                    @endforeach                                        
                },
                inputPlaceholder: '-- Select Agency --',
                showCancelButton: true,
                inputValidator: (value) => {
                    
                    return new Promise((resolve) => {
                    if (value) {    

                        resolve();
                        
                        
                            
                    } else {
                        resolve('You need to select agency')
                    }
                    })
                }
                }).then((res)=>{


                        if(res.isConfirmed){
                            // upload file here
                            Swal.fire({
                                title: 'Are you sure you want to ingest these files?',
                                text: "You won't be able to revert this!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, Ingest it!'
                            })
                            .then((result) => {
                            
                                if(result.isConfirmed){                                                                                                        
                                
                                // $(".upload-btn").hide();
                                $(".upload-btn").html('<i class="fas fa-spinner fa-spin"></i> Ingesting...');
                                $(".upload-btn").prop('disabled',true);

                                // $(".progress").show();
                                // $(".filename-label").show();
                                myDropzone.on("sending", function(file, xhr, formData) { 

                                // Will sendthe filesize along with the file as POST data.

                                formData.append("agency_id", res.value);  

                                });

                                myDropzone.processQueue();

                                
                                

                                $.each(myDropzone.files, function(k,v){
                                    myDropzone_status.push(v.status);
                                });
                                                        

                                }
                                else{
                                    Swal.fire(
                                        'Message',
                                        'Operation cancelled',
                                        'error'
                                        )
                                }
                            })
                        }
                    });
                   
                }else{
                    $(".upload-btn").html("<i class='fa fa-cloud-download-alt'></i> Upload and Ingest");
                    $(".upload-btn").prop('disabled',false);
                    Swal.fire(
                    'Message',
                    'Invalid files will be automatically remove.',
                    'error'
                    ).then(()=>{
                        
                        myDropzone.files.forEach((item)=>{
                        if(!item.upload.filename.includes('USSC') && !item.upload.filename.includes('DISRET')){                                                                           
                            myDropzone.removeFile(item)
                        }
                        })                        

                    });
                }     
            }
   

        
            
            
            

        })

        Dropzone.autoDiscover = false;
        
        Dropzone.options.uploadingDropzone = {
            
            maxFilesize: 100,
            maxFiles: 30,
            addRemoveLinks: true,
            timeout: 600000000,
            dictRemoveFile: 'x',                        
            dictDefaultMessage: "<span>Drop files here or click to upload</span>",            
            paramName: "dbp_returned_file",
            autoProcessQueue: false,
            acceptedFiles: '.txt',  
            uploadMultiple:true,
            parallelUploads: 5,
            error: function(file){

 
            if (!file.accepted){
                $(".upload-btn").html("<i class='fa fa-cloud-download-alt'></i> Upload and Ingest");
                $(".upload-btn").prop('disabled',false);
                Swal.fire(
                        'Message',
                        'Something went wrong!',
                        'error'
                        )
                file.previewElement.remove();
            }

            },                   
            processing:function(){
                check_upload = false;
            },
            success: function(file, response){

                check_upload = true;
                $(".upload-btn").html("<i class='fa fa-cloud-download-alt'></i> Upload and Ingest");
                $(".upload-btn").prop('disabled',false);
                if(check_upload != false){
                    Swal.fire(
                        'Message',
                        'Successfully uploaded and ingested.',
                        'success'
                    ).then(()=>{


                        $(".filename-label").html('');
                        $(".filename-label").hide();
                        parses_result = JSON.parse(response);
                        error_data = []                        
                        
                        // SEND NOTIFICATION
                        parseNotification = JSON.parse(parses_result[0]['notification']);
                        console.warn(parseNotification)
                        
                        if(parseNotification.length != 0 && parses_result[0]['total_saved_records'] != 0){

                            parseNotification.map((item_notif)=>{

                                socket().emit('message',{
                                    room:{                                                                                                                                
                                        roles:item_notif.role,
                                        region:parses_result['region'],
                                        from:'{{session("uuid")}}',                                
                                        senderName:item_notif.senderName,
                                        to:item_notif.to                                
                                    },
                                    message:item_notif.message,
                                    status:"unread" 
                                }); 
                            })
                            
                        }

                        parses_result.map((item)=>{
                            item['error_array'].map((error_item)=>{
                                console.warn(error_item);
                                error_data.push(error_item);
                            })
                            
                        }); 

                        
                    
                            if(error_data.length != 0){

                        
                            // show error logs of file upload
                            $("#ErrorDataModal").modal('show');
                            $("#error-datatable").DataTable({
                                    destroy:true,
                                    data: error_data ,
                                    columns:[
                                        {data:'rsbsa_no',title:'RSBSA Number'},                                                                                                        
                                        {data:'fintech_provider',title:'Provider',orderable:false},
                                        {title:'Name',orderable:false,render:function(data,type,row){
                                            return row.first_name + ' ' + row.last_name;
                                        }},                                                    
                                        {data:'barangay',title:'Barangay',orderable:false},
                                        {data:'city_municipality',title:'Municipality',orderable:false},
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

                    
                        
                        
                        $(".upload-btn").show();
                        // $(".progress").hide();
                        // $(".filename").hide();
                        $("#list-of-ingested-files-datatable").DataTable().ajax.reload();
                        Dropzone.forElement('#uploadingDropzone').removeAllFiles(true);
                        $(".progress-load").css('width','0%')
                        $(".progress-load").html('0')
                    });
                }
                
            }, 
            sending: function(file, xhr, formData){       
                formData.append('_token', '{{csrf_token()}}');
                formData.append('token', token);
            
            }            
           
        }   

    
      
        var base_url = "{{url('/')}}";
        var myDropzone = new Dropzone("div#uploadingDropzone", { url: base_url + "/returned-disbursement/upload-file" ,});

        

    </script>
@endsection






@section('content')

    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header"> File uploading </h1>
    <!-- end page-header -->

    <!-- with left icon -->
    <div class="note note-warning note-with-left-icon">
    <div class="note-icon"><i class="fa fa-lightbulb"></i></div>
    <div class="note-content text-left">
        <h4><b>Reminder!</b></h4>
        <p> Please do not refresh the page while uploading. If you accidentally refresh the page please reupload the file. </p>
    </div>
    </div>
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
                        <div class="alert alert-warning filename-label " style="top:15px;display:none"></div>
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
                        {{-- List of ingested files --}}

                        <table id="list-of-ingested-files-datatable" class="table table-hover" width="100%">            
                            <thead>                                    
                            </thead>
                            <tbody>                
                            </tbody>
                        </table>
                                              
                    </div>						
                </div>
                <!-- end panel -->
            </div>
            <!-- end col-8 -->		
        </div>
        <!-- end row -->




        <!-- #modal-view -->
      <div class="modal fade" id="ViewModal">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content" style="width: 150%;right:25%">
                <div class="modal-header" style="background-color: #007BFF">
                    <h4 class="modal-title" style="color: white">View Records</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                
                    @csrf
                    
                    <div class="modal-body">
                        {{--modal body start--}}
                        <h2 id="ViewCategName" align="center"></h2>

                        
                        <div class="note note-success">
                            <div class="note-icon"><i class="fas fa-user"></i></div>
                            <div class="note-content">
                                <label style="display: block; text-align: center; font-weight:bold;  font-size:24px" id="uploaded_by">John Edcel Zenarosa</label>
                                <label style="display: block; text-align: center; font-weight:bold;  font-size:20px" id="name">Uploaded By</label>
                            </div>
                        </div>

                        <table id="dbp-returned-files-show-more-datatable" class="table table-hover table-bordered reports" style="width:100%">            
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



     <!-- #modal-list of not inserted data to database from text file -->
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






        

@endsection
