@extends('global.base')
@section('title', "Batch Management")

{{--  import in this section your css files--}}
@section('page-css')
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/rowgroup/1.1.3/css/rowGroup.dataTables.min.css" rel="stylesheet">
    <link href="assets/pgv/backend-style.css" rel="stylesheet">
@endsection

{{--  import in this section your javascript files  --}}
@section('page-js')    
    <script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="assets/plugins/bootstrap-sweetalert/sweetalert.min.js"></script>
	<script src="assets/js/demo/ui-modal-notification.demo.min.js"></script>
    <script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
	<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
	<script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
	<script src="assets/js/demo/table-manage-default.demo.min.js"></script>    
    <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/pgv/backend-script.js"></script>

    <script type="text/javascript">
       $(document).ready(function() {  

            DisbursementList();

            @if (session('role_id') == 8)
                $('.btnApproveDisburseBudget').css('display','inline-block');
                $('.btnApproveDisbursement, .btnApproveDisburseSupervisor').css('display','none');
                $('.disbursementdetails_title').html(' Approve Generated Details');     
                $('.disbursementlist_title').html(' Generated Beneficiaries');   
                        
            @elseif (session('role_id') == 10)
                $('.btnApproveDisburseSupervisor').css('display','inline-block');
                $('.btnApproveDisbursement, .btnApproveDisburseBudget').css('display','none');
                $('.disbursementdetails_title').html(' Approve Generated Details'); 
                $('.disbursementlist_title').html(' Generated Beneficiaries');   
            @elseif (session('role_id') == 4)
                $('.btnApproveDisburseSupervisor, .btnApproveDisburseBudget').css('display','none');
                $('.btnApproveDisbursement').css('display','inline-block');
                $('.disbursementdetails_title').html(' Endorse Beneficiaries');  
                $('.disbursementlist_title').html(' Beneficiaries'); 
            @endif

            function DisbursementList(){
                var table = $('#ApprovedDisbursementList-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    scrollY: "200px",
                    "order": [[ 2, "desc" ]],
                    "lengthMenu": [[10, 25, 50, 100, 500, 1000, 10000, -1], [10, 25, 50, 100, 500, 1000, 10000, "All"]],
                    ajax: "{{ route('get.ApprovedDisbursementList') }}",
                    columns: [ 
                        {data: 'date_uploaded', name: 'date_uploaded', title:'TRANSACTION DATE'},
                        {data: 'approved_date', name: 'approved_date', title:'APPROVED DATE'},
                        {data: 'approved_batch_seq', name: 'approved_batch_seq', title:'DISBURSEMENT BATCH NO.'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title:'TOTAL AMOUNT'},
                        {data: 'approval_status', name: 'approval_status', title:'APPROVAL STATUS'},
                        {data: 'approved_batch_seq', name: 'approved_batch_seq',  title:'ACTION',
                            render: function(data, type, row) {
                                return '<a href="javascript:;" data-selectedbatchid="'+row.approved_batch_seq+'" class="btn btn-xs btn-outline-info btnViewApprovedBatchDisbursement"><span class="fa fa-eye"></span><i class="fas fa-spinner fa-spin '+row.approved_batch_seq+' pull-left m-r-10" style="display: none;"></i> View Details</a>';
                            }
                        }
                    ]
                    });
                    getNewlyUploadedBeneficiaries();
                    getBeneficiariesforApproval();
                    getNoofApprovedBeneficiaries();
            }
           
            $(document).on('click','.btnApprovalDisbursement',function(){                
                SpinnerShow('btnApprovalDisbursement','btnloadingIcon');                
                getProvinceList();
                getFundSource();
                DisbursementApprovalList("",0);
                $('#ApprovalDisbursementModal').modal('toggle');
                SpinnerHide('btnApprovalDisbursement','btnloadingIcon');
            });

            function DisbursementApprovalList(selected_prv_code,filterby){
                SpinnerShow('btnApproveDisbursement','btnloadingIcon3');
                SpinnerShow('btnApproveDisburseSupervisor','btnloadingIcon2');
                SpinnerShow('btnExportDisbursement','btnloadingIcon5');
                SpinnerShow('btnApproveDisburseBudget','btnloadingIcon2');
                $('#DisbursementList-datatable').unbind('click');
                var table = $('#DisbursementList-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    scrollY: "200px",
                    "lengthMenu": [[10, 25, 50, 100, 500, 1000, 10000, -1], [10, 25, 50, 100, 500, 1000, 10000, "All"]],
                    ajax: ({type:'get',
                            url:"{{ route('get.DisbursementList') }}",
                            data:{selected_prv_code:selected_prv_code,filterby:filterby},
                        }),
                    columns: [
                        {data: 'date_uploaded', name: 'date_uploaded', title:'TRANSACTION DATE'},
                        {data: 'rsbsa_no', name: 'rsbsa_no', title:'RSBSA NO.'},
                        {data: 'fullname', name: 'fullname', title:'FARMER NAME'}, 
                        {data: 'account_number', name: 'account_number', title:'DBP ACCOUNT NO.'}, 
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title:'AMOUNT'},    
                        {data: 'kyc_id', name: 'kyc_id',  title:'ACTION',
                            render: function(data, type, row) {
                                if(row.isremove == 1){
                                    return '<a href="javascript:;" data-selectedkycid="'+row.kyc_id+'" class="btn btn-xs btn-outline-info btnDisbursementkycDetails" data-toggle="tooltip" data-placement="top" title="View Details"><i class="fas fa-spinner fa-spin '+row.kyc_id+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-eye"></span></a>|'+
                                '<a href="javascript:;" data-selectedkycid="'+row.kyc_id+'" data-selectedfundid="'+row.fund_id+'" class="btn btn-xs btn-outline-danger btnDisbursementkycActivate" data-toggle="tooltip" data-placement="top" title="Activate"><i class="fas fa-spinner fa-spin '+row.kyc_id+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-check-circle"></span></a>';
                                }else{
                                    return '<a href="javascript:;" data-selectedkycid="'+row.kyc_id+'" class="btn btn-xs btn-outline-info btnDisbursementkycDetails" data-toggle="tooltip" data-placement="top" title="View Details"><i class="fas fa-spinner fa-spin '+row.kyc_id+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-eye"></span></a>|'+
                                    '<a href="javascript:;" data-selectedkycid="'+row.kyc_id+'" data-selectedfundid="'+row.fund_id+'" class="btn btn-xs btn-outline-danger btnDisbursementkycRemove" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fas fa-spinner fa-spin '+row.kyc_id+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-trash"></span></a>';
                                }
                            }
                        }
                    ],
                    footerCallback: function (row, data, start, end, display) { 
                        var TotalAmount = 0;                                     
                            for (var i = 0; i < data.length; i++) {
                                var dataval = data[i]['amount'];
                                TotalAmount += parseInt(dataval);
                            }
                            $('.Disbursement_totalselectedamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount ));
                            if(TotalAmount > 0 ){
                                if(filterby == 0){
                                    SpinnerHide('btnApproveDisbursement','btnloadingIcon3');
                                    SpinnerHide('btnExportDisbursement','btnloadingIcon5');                                
                                    SpinnerHide('btnApproveDisburseSupervisor','btnloadingIcon2'); 
                                    SpinnerHide('btnApproveDisburseBudget','btnloadingIcon2');
                                }else{
                                    OverlayPanel_out();
                                    $('[data-dismiss=modal]').prop('disabled',false);
                                    $('[data-dismiss=modal]').css('cursor','pointer'); 
                                }
                                $('#Disbursement_selectedamt').val(TotalAmount);                                
                            }
                            OverlayPanel_out();
                            $('[data-dismiss=modal]').prop('disabled',false);
                            $('[data-dismiss=modal]').css('cursor','pointer');
                    }
        
                });                 
            }

            $(document).on('click','.btnExportDisbursement',function(){
                SpinnerShow('btnExportDisbursement','btnloadingIcon5');
                    // var url = $('.DisbursementModule_downloadfile').attr('href');
                    //     window.open(url,'_blank');  
                        // route('download.BeneficiariesExcel'
                // window.location.href = "{{ route('DisbursementExcel.index') }}";
                var selected_prov_code = $('.selectProvince').val();
                $.ajax({
                    type:'get',
                    url:"{{ route('download.BeneficiariesExcel') }}",
                    data:{selected_prov_code:selected_prov_code},
                    success:function(data){
                        var url = $('.DisbursementModule_downloadfile').attr('href');
                        window.open(url,'_blank');  
                        SpinnerHide('btnExportDisbursement','btnloadingIcon5');        
                },
                error: function (textStatus, errorThrown) {
                        console.log('Err');
                    }
                }); 
                
            });

            function getProvinceList(){
                $.ajax({
                    type:'get',
                    url:"{{ route('get.DisbursementApprovalProvinceList') }}",
                    success:function(data){                    
                        $('.option_prov_code').remove();
                        $('.selectProvince').append($('<option class="option_prov_code" value="" selected>Select Province</option>'));
                        for(var i=0;i<data.length;i++){                   
                                $('.selectProvince').append($('<option>', {class:'option_prov_code',value:data[i].prov_code, text:data[i].prov_name}));
                            }           
                },
                error: function (textStatus, errorThrown) {
                        console.log('Err');
                    }
                });  
            }

            function getFundSource(){
                $.ajax({
                    type:'get',
                    url:"{{ route('get.DisbursementFundSource') }}",
                    success:function(data){                    
                        $('.option_fund_id').remove();
                        for(var i=0;i<data.length;i++){             
                            $('.selectFundsource').append($('<option class="option_fund_id" value="">Select Fund Source</option>'));      
                                $('.selectFundsource').append($('<option class="option_fund_id" data-selectedfundamt='+data[i].amount+' value='+data[i].fund_id+'>'+data[i].remaining+'</option>'));
                            }           
                },
                error: function (textStatus, errorThrown) {
                        console.log('Err');
                    }
                });  
            }
            
            function getDisbursementTotalReadyforBatch(){
                var rowData = "";
                $.ajax({
                    type:'get',
                    url:"{{ route('get.DisbursementTotalReadyforBatch') }}",
                    success:function(data){               
                        for(var i=0;i<data.length;i++){                   
                            rowData += '<a href="javascript:;" class="list-group-item rounded-0 list-group-item-action list-group-item-default d-flex justify-content-between align-items-center text-ellipsis">'+
                            data[i].province+
                            '<span class="badge bg-gray bg-gray fs-10px">'+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( data[i].amount )+'</span>'+
                            '</a>';
                            }
                        return $('.total_DisbursementReadyforBatch').html(rowData);           
                },
                error: function (textStatus, errorThrown) {
                        console.log('Err');
                    }
                });  
            }

            $(document).on('change','.selectProvince',function(){
                var selected_prv_code = $(this).val();
                var table = $('#DisbursementList-datatable').DataTable();
                table.clear().draw();  
                $('.selectedbatchall').prop('checked',false); 
                $('.Disbursement_totalselectedamt').val(0);
                $('#Disbursement_selectedamt').val(0);  
                $('#selectedFundSource').val('');
                $('#selectedFundAmount').val(0);
                $('.Disbursement_totalselectedamt').html("0.00");   
                var filterby = "",
                    filter1 = $('#customRadio1').is(':checked'),
                    filter2 = $('#customRadio2').is(':checked');
                if(filter1 === true) {filterby = 0;}
                if(filter2 === true) {filterby = 1;}
                DisbursementApprovalList(selected_prv_code,filterby);
                getFundSource();
            });

            $(document).on('change','.selectFundsource',function(event){
                var selectFundsource = $(this).val(),
                    selectFundamount = $('.selectFundsource').data('selectedfundamt');
                    $('#selectedFundSource').val(selectFundsource);
                    amount = event.target.options[event.target.selectedIndex].dataset.selectedfundamt;
                    $('#selectedFundAmount').val(amount);
            });
            
            $(document).on('click','.btnApproveDisbursement, .btnApproveDisburseSupervisor',function(){
                SpinnerShow('btnApproveDisbursement','btnloadingIcon3');
                SpinnerShow('btnApproveDisburseSupervisor','btnloadingIcon2');
                SpinnerShow('btnExportDisbursement','btnloadingIcon5');
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Approve the Beneficiaries?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Approve',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        var _token = $("input[name=token]").val(),
                        selected_prv_code = $('.selectProvince').val();
                        selected_fund_source = $('#selectedFundSource').val();
                        if(selected_prv_code != ""){
                            $.ajax({
                                type:'post',
                                url:"{{ route('approve.Disbursement') }}",
                                data:{selected_prv_code:selected_prv_code,selected_fund_source:selected_fund_source,_token:_token},
                                success:function(data){ 
                                    Swal.fire({
                                        allowOutsideClick: false,
                                        title:'Approved!',
                                        text:'Your Disbursement List successfully Approved!',
                                        icon:'success'
                                    });                                  
                                    $('.errormsg_Disbursement').css('display','none');
                                    $('.Disbursement_totalselectedamt').val(0);
                                    $('#Disbursement_selectedamt').val(0);  
                                    $('#selectedFundSource').val('');
                                    $('#selectedFundAmount').val(0);
                                    $('.Disbursement_totalselectedamt').html("0.00"); 
                                    $('.selectedbatchall').prop('checked',false);  
                                    var table = $('#DisbursementList-datatable').DataTable();
                                    table.clear().draw();                                    
                                    var selected_prv_code = $('.selectProvince').val();
                                    var filterby = "",
                                        filter1 = $('#customRadio1').is(':checked'),
                                        filter2 = $('#customRadio2').is(':checked');
                                    if(filter1 === true) {filterby = 0;}
                                    if(filter2 === true) {filterby = 1;}
                                    DisbursementApprovalList(selected_prv_code,filterby);
                                    $('#ApprovedDisbursementList-datatable').DataTable().ajax.reload(); 
                                    DisbursementList();
                            },
                            error: function (textStatus, errorThrown) {
                                    console.log('Err');
                                    SpinnerHide('btnApproveDisbursement','btnloadingIcon3');
                                    SpinnerHide('btnApproveDisburseSupervisor','btnloadingIcon2');
                                }
                            });                            
                        }else{
                            $('.errormsg_Disbursement').css('display','block');
                            $(".errormsg_Disbursement").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Please Select Province as Request! Please try again.");
                            SpinnerHide('btnApproveDisbursement','btnloadingIcon3');
                            SpinnerHide('btnApproveDisburseSupervisor','btnloadingIcon2');
                            AlertHide('errormsg_Disbursement');
                        }
                        
                    }else{
                        SpinnerHide('btnApproveDisbursement','btnloadingIcon3');
                        SpinnerHide('btnApproveDisburseSupervisor','btnloadingIcon2');                        
                    }
                });
            }); 

            $(document).on('click','.btnApproveDisburseBudget',function(){
                SpinnerShow('btnApproveDisburseBudget','btnloadingIcon2');
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Approve the Beneficiaries?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Approve',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        var _token = $("input[name=token]").val(),
                        selected_prv_code = $('.selectProvince').val();
                        selected_fund_source = $('#selectedFundSource').val();
                        selected_fund_amount = $('#selectedFundAmount').val();
                        total_amount = $('#Disbursement_selectedamt').val();
                        if(selected_prv_code != "" && selected_fund_source !=""){
                            if(parseInt(selected_fund_amount) >= parseInt(total_amount)){
                                $.ajax({
                                    type:'post',
                                    url:"{{ route('approve.Disbursement') }}",
                                    data:{selected_prv_code:selected_prv_code,selected_fund_source:selected_fund_source,total_amount:total_amount,_token:_token},
                                    success:function(data){ 
                                        Swal.fire({
                                            allowOutsideClick: false,
                                            title:'Approved!',
                                            text:'Your Disbursement List successfully Approved!',
                                            icon:'success'
                                        });                                  
                                        $('.errormsg_Disbursement').css('display','none');
                                        $('.Disbursement_totalselectedamt').val(0);
                                        $('#Disbursement_selectedamt').val(0);  
                                        $('#selectedFundSource').val('');
                                        $('#selectedFundAmount').val(0);
                                        $('.Disbursement_totalselectedamt').html("0.00"); 
                                        $('.selectedbatchall').prop('checked',false);  
                                        var table = $('#DisbursementList-datatable').DataTable();
                                        table.clear().draw();                                    
                                        var selected_prv_code = $('.selectProvince').val();
                                        var filterby = "",
                                            filter1 = $('#customRadio1').is(':checked'),
                                            filter2 = $('#customRadio2').is(':checked');
                                        if(filter1 === true) {filterby = 0;}
                                        if(filter2 === true) {filterby = 1;}
                                        DisbursementApprovalList(selected_prv_code,filterby);
                                        DisbursementList();
                                        getFundSource();
                                        SpinnerHide('btnApproveDisbursement','btnloadingIcon3');
                                        SpinnerHide('btnApproveDisburseBudget','btnloadingIcon2');
                                },
                                error: function (textStatus, errorThrown) {
                                        console.log('Err');
                                        SpinnerHide('btnApproveDisburseBudget','btnloadingIcon2');                                
                                    }
                                });  
                            }else{
                                $('.errormsg_Disbursement').css('display','block');
                                $(".errormsg_Disbursement").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> The Total Request Amount is greater than on remaining balance of funds! Please try again.");
                                SpinnerHide('btnApproveDisburseBudget','btnloadingIcon2');
                                AlertHide('errormsg_Disbursement');
                            }
                                                      
                        }else{
                            $('.errormsg_Disbursement').css('display','block');
                            $(".errormsg_Disbursement").html("<b><h4><i class='fa fa-exclamation-triangle'></i> Error!</h4></b><hr> Please Select Province and Fund Source as Request! Please try again.");
                            SpinnerHide('btnApproveDisburseBudget','btnloadingIcon2');
                            AlertHide('errormsg_Disbursement');
                        }
                        
                    }else{
                        SpinnerHide('btnApproveDisburseBudget','btnloadingIcon2');                        
                    }
                });
            }); 

            $(document).on('click','.btnDisbursementkycRemove',function(){
                var kyc_id = $(this).data('selectedkycid'),
                    fund_id = $(this).data('selectedfundid');                   
                SpinnerShow('btnDisbursementkycRemove',kyc_id);
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Remove the selected Beneficiaries?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Remove',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $('#selectedkycid').val(kyc_id);
                        $('#selectedfundid').val(fund_id);
                        $('#AddRemarksModal').modal('toggle'); 
                    }else{                        
                        SpinnerHide('btnDisbursementkycRemove',kyc_id);                        
                    }
                });
            }); 
            $(document).on('click','.submitholdremarks',function(){
                var kyc_id = $('#selectedkycid').val(),
                    fund_id = $('#selectedfundid').val(),
                    remarks = $('.disbursement_holdremarks').val();
                    _token = $("input[name=token]").val();
                    if(remarks != ""){
                        $.ajax({
                            type:'post',
                            url:"{{ route('remove.DisbursementBeneficiaries') }}",
                            data:{kyc_id:kyc_id,fund_id:fund_id,remarks,_token:_token},
                            success:function(data){                         
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Removed!',
                                    text:'Your selected Beneficiaries successfully Removed!',
                                    icon:'success'
                                });                                  
                                $('.errormsg_Disbursement').css('display','none');
                                $('.Disbursement_totalselectedamt').val(0);
                                $('#Disbursement_selectedamt').val(0);  
                                $('#selectedFundSource').val('');
                                $('#selectedFundAmount').val(0);
                                $('.Disbursement_totalselectedamt').html("0.00"); 
                                $('.selectedbatchall').prop('checked',false);  
                                $('.disbursement_holdremarks').val('');
                                $('#AddRemarksModal').modal('hide'); 
                                var table = $('#DisbursementList-datatable').DataTable();
                                table.clear().draw();                                    
                                var selected_prv_code = $('.selectProvince').val();
                                var filterby = "",
                                    filter1 = $('#customRadio1').is(':checked'),
                                    filter2 = $('#customRadio2').is(':checked');
                                if(filter1 === true) {filterby = 0;}
                                if(filter2 === true) {filterby = 1;}
                                DisbursementApprovalList(selected_prv_code,filterby);
                                DisbursementList();
                                getFundSource();
                                SpinnerHide('btnDisbursementkycRemove',kyc_id);
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('btnDisbursementkycRemove',kyc_id);
                            }
                        }); 
                    }else{
                        $('.errormsg_appdescription').css('display','block');
                        $(".errormsg_appdescription").html("Please enter value in required field!");
                        SpinnerHide('btnDisbursementkycRemove',kyc_id);
                    }   
            });
            
            $(document).on('click','.descriptionclose',function(){
                var kyc_id = $('#selectedkycid').val();
                SpinnerHide('btnDisbursementkycRemove',kyc_id);
            });
            

            $(document).on('click','.btnDisbursementkycActivate',function(){
                var kyc_id = $(this).data('selectedkycid'),
                    fund_id = $(this).data('selectedfundid'),
                    _token = $("input[name=token]").val();
                SpinnerShow('btnDisbursementkycActivate',kyc_id);
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Activate the selected Beneficiaries?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Activate',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type:'post',
                            url:"{{ route('activate.DisbursementBeneficiaries') }}",
                            data:{kyc_id:kyc_id,fund_id:fund_id,_token:_token},
                            success:function(data){ 
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Activated!',
                                    text:'Your selected Beneficiaries successfully Activated!',
                                    icon:'success'
                                });                                  
                                $('.errormsg_Disbursement').css('display','none');
                                $('.Disbursement_totalselectedamt').val(0);
                                $('#Disbursement_selectedamt').val(0);  
                                $('#selectedFundSource').val('');
                                $('#selectedFundAmount').val(0);
                                $('.Disbursement_totalselectedamt').html("0.00"); 
                                $('.selectedbatchall').prop('checked',false);  
                                var table = $('#DisbursementList-datatable').DataTable();
                                table.clear().draw();                                    
                                var selected_prv_code = $('.selectProvince').val();
                                var filterby = "",
                                    filter1 = $('#customRadio1').is(':checked'),
                                    filter2 = $('#customRadio2').is(':checked');
                                if(filter1 === true) {filterby = 0;}
                                if(filter2 === true) {filterby = 1;}
                                DisbursementApprovalList(selected_prv_code,filterby);
                                DisbursementList();
                                getFundSource();
                                SpinnerHide('btnDisbursementkycActivate',kyc_id);
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('btnDisbursementkycActivate',kyc_id);
                            }
                        }); 
                        
                    }else{
                        SpinnerHide('btnDisbursementkycActivate',kyc_id);                        
                    }
                });
            }); 
            
            $(document).on('click','.btnViewApprovedBatchDisbursement',function(){
                var batch_id = $(this).data('selectedbatchid');
                SpinnerShow('btnViewApprovedBatchDisbursement',batch_id);
                $('#ApprovedBatchDisbursement-datatable').unbind('click');
                var table = $('#ApprovedBatchDisbursement-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    scrollY: "200px",
                    "lengthMenu": [[10, 25, 50, 100, 500, 1000, 10000, -1], [10, 25, 50, 100, 500, 1000, 10000, "All"]],
                    ordering:false,
                    ajax: "{{ route('get.ApprovedBatchDisbursement') }}" + '?batch_id=' + batch_id,
                    columns: [
                        {data: 'date_uploaded', name: 'date_uploaded', title:'TRANSACTION DATE'},
                        {data: 'rsbsa_no', name: 'rsbsa_no', title:'RSBSA NO.'},
                        {data: 'fullname', name: 'fullname', title:'FARMER NAME'},
                        {data: 'account_number', name: 'account_number', title:'DBP ACCOUNT NO.'}, 
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title:'TOTAL AMOUNT'},    
                        {data: 'kyc_id', name: 'kyc_id',  title:'ACTION',
                            render: function(data, type, row) {
                                return '<a href="javascript:;" data-selectedkycid="'+row.kyc_id+'" class="btn btn-xs btn-outline-info btnDisbursementkycDetails"><i class="fas fa-spinner fa-spin '+row.kyc_id+' pull-left m-r-10" style="display: none;"></i><span class="fa fa-eye"></span> View Details</a>';
                            }
                        }
                    ],
                    rowGroup: {
                        dataSrc: function (data) {
                                return "<span>{{ session('Default_Program_Desc') }}</span>";
                            },
                        starRender:null,
                        endRender: function(rows){
                                var total_amount_claim = rows
                                .data()
                                .pluck('amount')
                                .reduce( function (a, b) {
                                            return (a)*1 + (b)*1;
                                }, 0 );
                                return '<span>Page Total: '+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( total_amount_claim )+'</span>';
                            },                    
                        } 
                });
                $('#ApprovedBatchDisbursementModal').modal('toggle');
                SpinnerHide('btnViewApprovedBatchDisbursement',batch_id);
            });

            $(document).on('click','.btnDisbursementkycDetails',function(){
                var kyc_id = $(this).data('selectedkycid');
                SpinnerShow('btnDisbursementkycDetails',kyc_id);
                var table = $('#DisbursementkycDetails-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    scrollY: "200px",
                    ajax: "{{ route('get.DisbursementkycDetails') }}" + '?kyc_id=' + kyc_id,
                    columns: [
                        {data: 'date_uploaded', name: 'date_uploaded', title:'TRANSACTION DATE'},
                        {data: 'rsbsa_no', name: 'rsbsa_no', title:'RSBSA NO.'},
                        {data: 'fullname', name: 'fullname', title:'FARMER NAME'},
                        {data: 'region', name: 'region', title:'REGION'},
                        {data: 'province', name: 'province', title:'PROVINCE'},
                        {data: 'municipality', name: 'municipality', title:'MUNICIPALITY'},
                        {data: 'barangay', name: 'barangay', title:'BARANGAY'},
                        {data: 'birthdate', name: 'birthdate', title:'BIRTHDATE'},
                        {data: 'mobile_no', name: 'mobile_no', title:'MOBILE NO.'},
                        {data: 'sex', name: 'sex', title:'SEX'},                        
                    ]
                });
                $('#DisbursementkycDetailsModal').modal('toggle');
                SpinnerHide('btnDisbursementkycDetails',kyc_id);
            });

            $(document).on('click','.btnApprovedDisbursementHistory',function(){
                SpinnerShow('btnApprovedDisbursementHistory','btnloadingIcon4');
                var table = $('#ApprovedDisbursementHistory-datatable').DataTable({ 
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.ApproveddDisbursementHistory') }}",
                    columns: [ 
                            {data: 'created_at', name: 'created_at', title:'DATE APPROVED'},
                            {data: 'folder_file_name', name: 'folder_file_name', title: 'GROUP NAME'},
                            {data: 'name', name: 'name', title: 'FILE NAME'},
                            {data: 'approved_batch_seq', name: 'approved_batch_seq', title: 'BATCH NAME'},
                            {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                            {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title: 'TOTAL RECORDS'},
                        ]
                    });
                $('#ApprovedDisbursementHistoryModal').modal('toggle');
                SpinnerHide('btnApprovedDisbursementHistory','btnloadingIcon4');
           });

           $(document).on('click','#customRadio1,#customRadio2',function(){
                getProvinceList();
                getFundSource();
                DisbursementApprovalList("",0);                
           });

           function getNewlyUploadedBeneficiaries(){
            var table = $('#NewlyUploaded-datatable').DataTable({ 
                destroy: true,
                processing: true,
                paging: false,
                responsive: true,
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                ajax: "{{ route('get.NewlyUploaded') }}",
                columns: [                         
                        {data: "province",name: "province",
                        render: function(data,type,row) {
                            @if (session('role_id') == 4)
                                return '<a href="javascript:void(0);" data-selectedprovince="' + row.prov_code + '" class="selectedprovincelink"><i class="fas fa-spinner fa-spin '+ data +' pull-left m-r-10" style="display: none;"></i>' + data + '</a>';
                            @elseif (session('role_id') != 4)
                                return data;
                            @endif                            
                        }, title: 'PROVINCE'},
                        {data: 'date_uploaded', name: 'date_uploaded', title:'LATEST DATE UPLOADED'},
                        {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 0,  ).display, title: 'NO. OF UPLOADED'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                    ],
                    footerCallback: function (row, data, start, end, display) { 
                        var TotalAmount = 0,   
                            TotalCount = 0;                                       
                        for (var i = 0; i < data.length; i++) {
                            var dataval = data[i]['amount'],
                                dataval2 = data[i]['total_records'];
                                TotalAmount += parseInt(dataval);
                                TotalCount += parseInt(dataval2);
                        }
                        $('.total_amt_newly_uploaded').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount ));       
                        $('.total_cnt_newly_uploaded').html($.fn.dataTable.render.number(',', '.', 2, '').display( TotalCount ));                               
                    
                    }
                });
           }
           
           function getBeneficiariesforApproval(){
            var table = $('#BeneficiariesforApproval-datatable').DataTable({ 
                destroy: true,
                processing: true,
                paging: false,
                responsive: true,
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                ajax: "{{ route('get.BeneficiariesforApproval') }}",
                columns: [ 
                        {data: "province",name: "province",
                        render: function(data, type, row) {
                            @if (session('role_id') != 4)
                                return '<a href="javascript:void(0);" data-selectedprovince="' + row.prov_code + '" class="selectedprovincelink"><i class="fas fa-spinner fa-spin '+ row.prov_code +' pull-left m-r-10" style="display: none;"></i>' + data + '</a>';
                            @elseif (session('role_id') == 4)
                                return data;
                            @endif  
                        }, title: 'PROVINCE'},
                        {data: 'approved_batch_seq', name: 'approved_batch_seq', title:'BATCH NUMBER'},
                        {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 0,  ).display, title: 'TOTAL RECORDS'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        {data: 'isapproved', name: 'isapproved',  title:'ENDORSER',
                            render: function(data, type, row) {
                                if(row.isapproved == 1){
                                    return '<span class="fa fa-2x fa-check-circle text-primary"></span>';
                                }
                                return '<span class="fa fa-2x fa-circle"></span>';
                            }
                        },
                        {data: 'approved_by_b', name: 'approved_by_b',  title:'REVIEWER',
                            render: function(data, type, row) {
                                if(row.approved_by_b == 1){
                                    return '<span class="fa fa-2x fa-check-circle text-primary"></span>';
                                }
                                return '<span class="fa fa-2x fa-circle"></span>';
                            }
                        },
                        {data: 'approved_by_d', name: 'approved_by_d',  title:'APPROVER',
                            render: function(data, type, row) {
                                if(row.approved_by_d == 1){
                                    return '<span class="fa fa-2x fa-check-circle text-primary"></span>';
                                }
                                    return '<span class="fa fa-2x fa-circle"></span>';
                            }
                        }
                    ],
                    footerCallback: function (row, data, start, end, display) { 
                        var TotalAmount = 0,   
                            TotalCount = 0;                                       
                        for (var i = 0; i < data.length; i++) {
                            var dataval = data[i]['amount'],
                                dataval2 = data[i]['total_records'];
                                TotalAmount += parseInt(dataval);
                                TotalCount += parseInt(dataval2);
                        }
                        $('.total_amt_bene_approval').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount ));       
                        $('.total_cnt_bene_approval').html($.fn.dataTable.render.number(',', '.', 2, '').display( TotalCount ));                               
                    
                    }
                });
           }
           
           function getNoofApprovedBeneficiaries(){
            var table = $('#ApprovedBeneficiaries-datatable').DataTable({ 
                destroy: true,
                processing: true,
                paging: false,
                responsive: true,
                "bPaginate": false,
                "bFilter": false,
                "bInfo": false,
                ajax: "{{ route('get.DisbursementTotalApproved') }}",
                columns: [ 
                    {data: 'province', name: 'province', title:'PROVINCE'},
                        {data: 'approved_batch_seq', name: 'approved_batch_seq', title:'BATCH NUMBER'},
                        {data: 'folder_file_name', name: 'folder_file_name', title:'DBP BATCH NAME'},
                        {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 0,  ).display, title: 'TOTAL RECORDS'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                    ],
                    footerCallback: function (row, data, start, end, display) { 
                        var TotalAmount = 0,   
                            TotalCount = 0;                                       
                        for (var i = 0; i < data.length; i++) {
                            var dataval = data[i]['amount'],
                                dataval2 = data[i]['total_records'];
                                TotalAmount += parseInt(dataval);
                                TotalCount += parseInt(dataval2);
                        }
                        $('.total_amt_approved_bene').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount ));       
                        $('.total_cnt_approved_bene').html($.fn.dataTable.render.number(',', '.', 2, '').display( TotalCount ));                               
                    
                    }
                });
           }

           $(document).on('click','.selectedprovincelink',function(){
                var selected_prv_code = $(this).data('selectedprovince');
                SpinnerShow('selectProvince',selected_prv_code);
                $('.selectedbatchall').prop('checked',false); 
                $('.Disbursement_totalselectedamt').val(0);
                $('#Disbursement_selectedamt').val(0);  
                $('#selectedFundSource').val('');
                $('#selectedFundAmount').val(0);
                getFilteredProvinceList(selected_prv_code);
                DisbursementApprovalList(selected_prv_code,0);
                getFundSource();
                $('#ApprovalDisbursementModal').modal('toggle');
                SpinnerHide('selectProvince',selected_prv_code);
            });

            function getFilteredProvinceList(selected_prv_code){
                $.ajax({
                    type:'get',
                    url:"{{ route('get.FilteredProvinceList') }}" + '?selected_prv_code=' + selected_prv_code,
                    success:function(data){                    
                        $('.option_prov_code').remove();
                        $('.selectProvince').append($('<option class="option_prov_code" value="" selected>Select Province</option>'));
                        for(var i=0;i<data.length;i++){                   
                                $('.selectProvince').append($('<option>', {class:'option_prov_code',value:data[i].prov_code, text:data[i].prov_name}));
                            }  
                        $('.selectProvince').val(selected_prv_code);       
                },
                error: function (textStatus, errorThrown) {
                        console.log('Err');
                    }
                });  
            }
    });        
</script>

@endsection

@section('content')
<!-- FADE SCREEN UPON ACTION -->
<div id="overlay"></div>
<!-- <img src='{{url('assets/img/images/image-1.png')}}' alt='' /> -->
<!-- STORE DATA OBJECT -->

<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">
<input type="hidden" id="selectedkycid" value="0">
<input type="hidden" id="selectedfundid" value="0">
<input type="hidden" id="Disbursement_selectedamt" value="0">
<input type="hidden" id="selectedProgramId" value="">
<input type="hidden" id="selectedFundSource" value="">
<input type="hidden" id="selectedFundAmount" value="0">
<a href="{{route('generate.BeneficiariesExcel')}}" class="DisbursementModule_downloadfile" style="display:none;"></a>
<div class="row">
    <div class="col-md-8">
        <div class="input-group">
            <h1 class="page-header">Batch Management</h1>                       
        </div>
    </div>
    <div class="col-md-4">
        <ol class="breadcrumb pull-right">
            <li class="breadcrumb-item"><a href="{{ route('main.home') }}">Home Page</a></li>
            <li class="breadcrumb-item active">Batch Management</li>
        </ol>   
    </div>
</div>
<div class="row">
    <div class="col-xl-12 ui-sortable">
        <div class="row">
            <div class="col-xl-12">       
                <div class="pull-right">                              
                    <a href="javascript:;" class="btn btn-lg btn-primary btnApprovalDisbursement">  
                        <i class="fa-2x fas fa-spinner fa-spin btnloadingIcon pull-left m-r-10" style="display: none;"></i>                                  
                        <i class="fa fa-thumbs-up fa-2x pull-left m-r-10 text-black"></i>
                        <b class="disbursementdetails_title"> Generate Beneficiaries </b><br />
                        <small>Click Here</small>
                    </a>
                </div>
            </div>  
            <div class="col-xl-12 ui-sortable m-t-10">                
                <h4 class="panel-title" style="color: #000;">
                    <span class="fa fa-info-circle"></span> Newly Uploaded Beneficiaries
                </h4>
                <table id="NewlyUploaded-datatable" class="table table-striped table-solo" style="width: 100%;">
                    <thead style="background-color: #008a8a"></thead>
                </table>
                <h4 class="panel-title" style="color: #000;">
                    <span class="text-inverse pull-right">Total Amount: <span class="total_amt_newly_uploaded">₱0.00</span></span>
                    <span class="text-inverse pull-right">|</span>                    
                    <span class="text-inverse pull-right">Total Beneficiaries: <span class="total_cnt_newly_uploaded">0</span></span>
                </h4>
            </div>
            <div class="col-xl-12 ui-sortable" style="margin-top:10px;">
                <h4 class="panel-title" style="color: #000;">
                    <span class="fa fa-info-circle"></span> Beneficiaries for Approval
                </h4>
                <table id="BeneficiariesforApproval-datatable" class="table table-striped table-solo" style="width: 100%;">
                    <thead style="background-color: #008a8a"></thead>
                </table>
                <h4 class="panel-title" style="color: #000;">
                    <span class="text-inverse pull-right">Total Amount: <span class="total_amt_bene_approval">₱0.00</span></span>
                    <span class="text-inverse pull-right">|</span>                    
                    <span class="text-inverse pull-right">Total Beneficiaries: <span class="total_cnt_bene_approval">0</span></span>
                </h4>
            </div>
            <div class="col-xl-12 ui-sortable" style="margin-top:10px;">
                <h4 class="panel-title" style="color: #000;">
                    <span class="fa fa-info-circle"></span> No. of Approved Beneficiaries
                </h4>
                <table id="ApprovedBeneficiaries-datatable" class="table table-striped table-solo" style="width: 100%;">
                    <thead style="background-color: #008a8a"></thead>
                </table>
                <h4 class="panel-title" style="color: #000;">
                    <span class="text-inverse pull-right">Total Amount: <span class="total_amt_approved_bene">₱0.00</span></span>
                    <span class="text-inverse pull-right">|</span>                    
                    <span class="text-inverse pull-right">Total Beneficiaries: <span class="total_cnt_approved_bene">0</span></span>
                </h4>
            </div>
        </div>        
    </div>    
</div>

<div class="modal fade bd-example-modal-lg" id="ApprovalDisbursementModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i><span class="disbursementlist_title"> Beneficiaries </span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    <div class="alert alert-danger errormsg_Disbursement" role="alert" style="display: none;"></div>                   
                    <div class="col-md-12 pt-2">
                        <div class="input-group">
                            <select id="default_DisbursementProvince" class="form-control selectProvince" name="DisbursementProvince" data-size="10" data-style="btn-white" value="{{ old('DisbursementProvince') }}">
                            </select>
                            @if (session('role_id') == 8)
                            <select id="default_DisbursementFundSource" class="form-control selectFundsource" name="DisbursementFundSource" data-size="10" data-style="btn-white" value="{{ old('DisbursementFundSource') }}" style="margin-left:5px;">
                                <!-- <option value="" selected>Select Fund Source</option> -->
                            </select>
                            <a href="{{ route('fund_encoding') }}" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Add Fund Source"><i class="fa fa-plus-circle"></i></a>
                            @endif
                            <div class="form-check form-check-inline m-l-10">
                                <input class="form-check-input" type="radio" id="customRadio1" name="inlineRadio" checked>
                                <label class="form-check-label" for="customRadio1">All Beneficiaries</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="customRadio2" name="inlineRadio">
                                <label class="form-check-label" for="customRadio2">All Removed</label>
                            </div>
                        </div>                                                        
                    </div> 
                    <div style="margin-top:10px;">
                        <table id="DisbursementList-datatable" class="table table-striped display nowrap" style="width: 100%;margin-top:10px;">
                            <thead style="background-color: #008a8a"></table> 
                    </div>                         
                    <label class="pull-right mt-3">
                        <div class="input-group" style="margin-bottom:-15px !important;">
                            <div class="input-group-prepend">
                            <span style="margin-top: 10px;">Total Amount:&nbsp;&nbsp;</span>
                            </div>
                            <h3 class="alert alert-primary Disbursement_totalselectedamt" role="alert">0.00</h3>
                        </div>
                    </label>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                <a href="javascript:;" class="btn btn-inverse btnExportDisbursement"><i class="fa fa-cloud-download-alt"></i><i class="fas fa-spinner fa-spin btnloadingIcon5 pull-left m-r-10" style="display: none;"></i> Export Data</a>
                <a href="javascript:;" class="btn btn-info btnApproveDisbursement"><i class="fa fa-thumbs-up"></i><i class="fas fa-spinner fa-spin btnloadingIcon3 pull-left m-r-10" style="display: none;"></i> Generate Batch</a>
                <a href="javascript:;" class="btn btn-info btnApproveDisburseBudget" style="display: none;"><i class="fa fa-thumbs-up"></i><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i> Approve Batch</a> 
                <a href="javascript:;" class="btn btn-info btnApproveDisburseSupervisor" style="display: none;"><i class="fa fa-thumbs-up"></i><i class="fas fa-spinner fa-spin btnloadingIcon2 pull-left m-r-10" style="display: none;"></i> Approve Batch</a>            
                <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ApprovedBatchDisbursementModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Approved Disbursement Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    <table id="ApprovedBatchDisbursement-datatable" class="table table-striped display nowrap" style="width: 100%;">
                        <thead style="background-color: #008a8a"></thead>
                    </table>
                </div>
                <div class="modal-footer">
                <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="DisbursementkycDetailsModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Farmer's Information:</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    <table id="DisbursementkycDetails-datatable" class="table table-striped display nowrap" style="width: 100%;">
                        <thead style="background-color: #008a8a"></thead>
                    </table>
                </div>
                <div class="modal-footer">
                <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ApprovedDisbursementHistoryModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Approved DBP History</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    <table id="ApprovedDisbursementHistory-datatable" class="table table-striped display nowrap" style="width: 100%;">
                        <thead style="background-color: #008a8a"></thead>
                    </table>
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                <a href="javascript:;" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="AddRemarksModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <form id="AddForm" method="POST" >
            @csrf
            <div class="modal-content" style="background-color: #008a8a">
                <div class="modal-header" style="background-color: #008a8a">
                    <h4 class="modal-title" style="color: white"><i class="fa fa-info-circle"></i> Note/Remarks</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">×</button>
                </div>
                <div class="modal-body" style="background-color: #fff">
                    {{--modal body start--}}
                    
                    <!-- begin right-content -->
                        <div class="right-content">
                            <div class="register-content">
                                <form action="index.html" method="GET" class="margin-bottom-0">
                                    @csrf
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <textarea class="form-control disbursement_holdremarks " rows="3" placeholder="Enter Description" required></textarea>
                                            <span class="text-danger errormsg_appdescription"></span>
                                        </div>
                                    </div>                                      
                                </form>
                            </div>
                            <!-- end register-content -->
                        </div>
                        <!-- end right-content -->
                    {{--modal body end--}}
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-success submitholdremarks"><i class="fa fa-check-circle"></i><i class="fas fa-spinner fa-spin btnloadingIcon1 pull-left m-r-10" style="display: none;"></i> Submit</a>
                    <a href="javascript:;" class="btn btn-danger descriptionclose" data-dismiss="modal"><i class="fa fa-window-close"></i> Close</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection