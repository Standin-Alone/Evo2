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
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />    
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
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        
        @if (session('role_id') == 8)
            $('.btnApproveDisburseBudget').css('display','inline-block');
            $('.btnApproveDisbursement, .btnApproveDisburseSupervisor, .kyc_filter_mun').css('display','none');
            $('.disbursementdetails_title').html(' Approve Generated Details');     
            $('.disbursementlist_title').html(' Generated Beneficiaries');                           
        @elseif (session('role_id') == 10)
            $('.btnApproveDisburseSupervisor').css('display','inline-block');
            $('.btnApproveDisbursement, .btnApproveDisburseBudge, .kyc_filter_mun').css('display','none');
            $('.disbursementdetails_title').html(' Approve Generated Details'); 
            $('.disbursementlist_title').html(' Generated Beneficiaries');   
        @elseif (session('role_id') == 4)
            $('.btnApproveDisburseSupervisor, .btnApproveDisburseBudget').css('display','none');
            $('.btnApproveDisbursement, .kyc_filter_mun').css('display','inline-block');
            $('.disbursementdetails_title').html(' Endorse Beneficiaries');  
            $('.disbursementlist_title').html(' Beneficiaries'); 
        @endif

        getNewlyUploadedBeneficiaries();
        getBeneficiariesforApproval();
        getNoofApprovedBeneficiaries();

        function getNewlyUploadedBeneficiaries(){
            var table = $('#NewlyUploaded-datatable').DataTable({ 
                destroy: true, processing: true, paging: false, responsive: true, "bPaginate": false, "bFilter": false, "bInfo": false,
                ajax: "{{ route('get.NewlyUploaded') }}",
                columns: [                         
                        {data: "province",name: "province",
                        render: function(data,type,row) {
                            @if (session('role_id') == 4)
                                return '<a href="javascript:void(0);" data-kyc_file_id="' + row.kyc_file_id + '" data-kyc_file_name="' + row.file_name + '" data-kyc_prov_code="' + row.prov_code + '" data-kyc_province="' + row.province + '" class="kyc_file_idlink"><i class="fas fa-spinner fa-spin '+ row.kyc_file_id +' pull-left m-r-10" style="display: none;"></i>' + data + '</a>';
                            @elseif (session('role_id') != 4)
                                return data;
                            @endif                            
                        }, title: 'PROVINCE'},
                        {data: 'file_name', name: 'file_name', title:'UPLOADED FILE NAME'},
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
                    $('.total_cnt_newly_uploaded').html($.fn.dataTable.render.number(',', '.', 0, '').display( TotalCount ));
                }
            });
        }
        
        function getBeneficiariesforApproval(){
            var table = $('#BeneficiariesforApproval-datatable').DataTable({ 
                destroy: true, processing: true, paging: false, responsive: true, "bPaginate": false, "bFilter": false, "bInfo": false,
                ajax: "{{ route('get.BeneficiariesforApproval') }}",
                columns: [ 
                        {data: "province",name: "province",
                        render: function(data, type, row) {
                            @if (session('role_id') == 8)
                                if(row.isapproved == 1){
                                    if(row.approved_by_b == 0){
                                        return '<a href="javascript:void(0);" data-kyc_file_id="' + row.kyc_file_id + '" data-kyc_file_name="' + row.file_name + '" data-kyc_prov_code="' + row.prov_code + '" data-kyc_province="' + row.province + '" data-batch_id="' + row.approved_batch_seq + '" class="kyc_file_idlink"><i class="fas fa-spinner fa-spin '+ row.kyc_file_id +' pull-left m-r-10" style="display: none;"></i>' + data + '</a>';
                                    }else{
                                        return data;
                                    }
                                }else{
                                    return data;
                                }                                
                            @elseif (session('role_id') == 10)
                                if(row.approved_by_b == 1){
                                    if(row.approved_by_d == 0){
                                        return '<a href="javascript:void(0);" data-kyc_file_id="' + row.kyc_file_id + '" data-kyc_file_name="' + row.file_name + '" data-kyc_prov_code="' + row.prov_code + '" data-kyc_province="' + row.province + '" data-batch_id="' + row.approved_batch_seq + '" class="kyc_file_idlink"><i class="fas fa-spinner fa-spin '+ row.kyc_file_id +' pull-left m-r-10" style="display: none;"></i>' + data + '</a>';
                                    }else{
                                        return data;
                                    }
                                }else{
                                    return data;
                                }
                            @elseif (session('role_id') == 4)
                                return data;
                            @endif  
                        }, title: 'PROVINCE'},
                        {data: "municipality",name: "municipality",
                        render: function(data, type, row) {
                            return '<a href="javascript:void(0);" data-kyc_file_id="' + row.kyc_file_id + '" data-kyc_file_name="' + row.file_name + '" data-kyc_mun_code="' + row.mun_code + '" data-batch_id="' + row.approved_batch_seq + '" class="kyc_mun_codelink"><i class="fas fa-spinner fa-spin '+ row.mun_code +' pull-left m-r-10" style="display: none;"></i><span class="fa fa-eye"></span> View Details</a>';
                        }, title: 'ACTION'},
                        {data: 'file_name', name: 'file_name', title:'UPLOADED FILE NAME'},
                        {data: 'approved_batch_seq', name: 'approved_batch_seq', title:'BATCH NUMBER'},
                        {data: 'total_records', name: 'total_records',render: $.fn.dataTable.render.number( ',', '.', 0,  ).display, title: 'TOTAL RECORDS'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        {data: 'isapproved', name: 'isapproved',  title:'ENDORSED',
                            render: function(data, type, row) {
                                if(row.isapproved == 1){
                                    return '<span class="fa fa-2x fa-check-circle text-primary"></span>';
                                }
                                return '<span class="fa fa-2x fa-circle"></span>';
                            }
                        },
                        {data: 'approved_by_b', name: 'approved_by_b',  title:'REVIEWED',
                            render: function(data, type, row) {
                                if(row.approved_by_b == 1){
                                    return '<span class="fa fa-2x fa-check-circle text-primary"></span>';
                                }
                                return '<span class="fa fa-2x fa-circle"></span>';
                            }
                        },
                        {data: 'approved_by_d', name: 'approved_by_d',  title:'APPROVED',
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
                    $('.total_cnt_bene_approval').html($.fn.dataTable.render.number(',', '.', 0, '').display( TotalCount ));
                }
            });
        }
        
        function getNoofApprovedBeneficiaries(){
            var table = $('#ApprovedBeneficiaries-datatable').DataTable({ 
                destroy: true, processing: true, paging: false, responsive: true, "bPaginate": false, "bFilter": false, "bInfo": false,
                ajax: "{{ route('get.DisbursementTotalApproved') }}",
                columns: [ 
                        {data: 'province', name: 'province', title:'PROVINCE'},
                        {data: 'file_name', name: 'file_name', title:'UPLOADED FILE NAME'},
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
                    $('.total_cnt_approved_bene').html($.fn.dataTable.render.number(',', '.', 0, '').display( TotalCount ));
                }
            });
        }

        $(document).on('click','.kyc_file_idlink',function(){            
            var kyc_file_id = $(this).data('kyc_file_id'),
                kyc_file_name = $(this).data('kyc_file_name'),
                kyc_batch_id = $(this).data('batch_id'),
                kyc_prov_code = $(this).data('kyc_prov_code');
                kyc_province = $(this).data('kyc_province');
            SpinnerShow('kyc_file_idlink',kyc_file_id);
            $('.kyc_file_id').val(kyc_file_id);
            $('.kyc_file_name').val(kyc_file_name);
            $('.kyc_prov_code').val(kyc_prov_code); 
            $('.kyc_province').val(kyc_province);
            $('.kyc_batch_id').val(kyc_batch_id); 
            $('.Disbursement_totalselectedamt, #Disbursement_selectedamt, #selectedFundAmount').val(0);
            $('#selectedFundSource, .kyc_filter_mun').val(''); 
            DisbursementApprovalList(kyc_file_id,0);     
            $('.kyc_mun_code').val('');       
            $('#ApprovalDisbursementModal').modal('toggle');
            SpinnerHide('kyc_file_idlink',kyc_file_id);
        });
        
        $(document).on('click','.kyc_mun_codelink',function(){            
            var kyc_batch_id = $(this).data('batch_id'),
            kyc_mun_code = $(this).data('kyc_mun_code');           
            SpinnerShow('kyc_mun_codelink',kyc_mun_code);
            $('.Disbursement_totalselectedamt, #Disbursement_selectedamt, #selectedFundAmount').val(0);
            $('#selectedFundSource').val(''); 
            $('#EndorseMunList-datatable').unbind('click');
            var table = $('#EndorseMunList-datatable').DataTable({ 
                destroy: true,
                processing: true,
                scrollY: "200px",
                "lengthMenu": [[10, 25, 50, 100, 500, 1000, 10000, -1], [10, 25, 50, 100, 500, 1000, 10000, "All"]],
                ajax: ({type:'get',
                        url:"{{ route('get.EndorseMunList') }}",
                        data:{kyc_batch_id:kyc_batch_id},
                    }),
                columns: [
                    {data: 'date_uploaded', name: 'date_uploaded', title:'TRANSACTION DATE'},
                    {data: 'rsbsa_no', name: 'rsbsa_no', title:'RSBSA NO.'},
                    {data: 'fullname', name: 'fullname', title:'FARMER NAME'}, 
                    {data: 'account_number', name: 'account_number', title:'DBP ACCOUNT NO.'}, 
                    {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title:'AMOUNT'},   
                ],
                footerCallback: function (row, data, start, end, display) { 
                    var TotalAmount = 0;                                     
                        for (var i = 0; i < data.length; i++) {
                            var dataval = data[i]['amount'];
                            TotalAmount += parseInt(dataval);
                        }
                    $('.Disbursement_totalselectedamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount ));
                }
    
            });             
            $('#EndorseMunModal').modal('toggle');
            SpinnerHide('kyc_mun_codelink',kyc_mun_code);
        });

        $(document).on('click','#customRadio1,#customRadio2',function(){
            SpinnerShow('customRadio1','');
            var filterby = "",
                kyc_file_id = $('.kyc_file_id').val(),
                filter1 = $('#customRadio1').is(':checked'),
                filter2 = $('#customRadio2').is(':checked');
            if(filter1 === true) {filterby = 0;}
            if(filter2 === true) {filterby = 1;}
            DisbursementApprovalList(kyc_file_id,filterby);              
        });

        function DisbursementApprovalList(kyc_file_id,filterby){                
            $('#DisbursementList-datatable').unbind('click');
            var kyc_batch_id = $('.kyc_batch_id').val(),
                kyc_mun_code = $('.kyc_filter_mun').val();
            var table = $('#DisbursementList-datatable').DataTable({ 
                destroy: true,
                processing: true,
                scrollY: "200px",
                "lengthMenu": [[10, 25, 50, 100, 500, 1000, 10000, -1], [10, 25, 50, 100, 500, 1000, 10000, "All"]],
                ajax: ({type:'get',
                        url:"{{ route('get.DisbursementList') }}",
                        data:{kyc_file_id:kyc_file_id,kyc_batch_id:kyc_batch_id,kyc_mun_code:kyc_mun_code,filterby:filterby},
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
                            OverlayPanel_out();
                        }else{
                            $('.btnApproveDisbursement, .btnExportDisbursement, .btnApproveDisburseSupervisor, .btnApproveDisburseBudget').prop('disabled',true);
                            $('.btnApproveDisbursement, .btnExportDisbursement, .btnApproveDisburseSupervisor, .btnApproveDisburseBudget').css('cursor','not-allowed');
                            $('[data-dismiss=modal]').prop('disabled',false);
                            $('[data-dismiss=modal]').css('cursor','pointer');
                        }
                        $('#Disbursement_selectedamt').val(TotalAmount);   
                        OverlayPanel_out();
                        $('[data-dismiss=modal]').prop('disabled',false);
                        $('[data-dismiss=modal]').css('cursor','pointer'); 
                        @if (session('role_id') == 4)
                            getFilteredMunList(kyc_file_id);
                        @endif
                        getFundSource();                          
                    }else{
                        $('.btnApproveDisbursement, .btnExportDisbursement, .btnApproveDisburseSupervisor, .btnApproveDisburseBudget').prop('disabled',true);
                        $('.btnApproveDisbursement, .btnExportDisbursement, .btnApproveDisburseSupervisor, .btnApproveDisburseBudget').css('cursor','not-allowed');
                        $('[data-dismiss=modal]').prop('disabled',false);
                        $('[data-dismiss=modal]').css('cursor','pointer');
                        OverlayPanel_out();
                    }  
                }
    
            });                 
        }

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

        function getFilteredMunList(kyc_file_id){
            $.ajax({
                type:'get',
                url:"{{ route('get.DisbursementFilteredMunList') }}",
                data:{kyc_file_id:kyc_file_id},
                success:function(data){                    
                    $('.option_mun_code').remove();
                    for(var i=0;i<data.length;i++){                   
                            $('.kyc_filter_mun').append($('<option class="option_mun_code" value="'+data[i].mun_code+'">'+data[i].mun_name+'</option>'));
                        }
                    $(".default-select2").select2();
                    $(".kyc_filter_mun").select2({ placeholder: "Select Municipality" });  
                        
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
                    kyc_file_id = $('.kyc_file_id').val(),
                    kyc_file_name = $('.kyc_file_name').val(),
                    kyc_prov_code = $('.kyc_prov_code').val(),
                    selected_fund_source = $('#selectedFundSource').val(),
                    filtered_mun = $('.kyc_filter_mun').val();
                    kyc_batch_id = $('.kyc_batch_id').val();
                    OverlayPanel_in();
                    if(kyc_file_id != ""){
                        $.ajax({
                            type:'post',
                            url:"{{ route('approve.Disbursement') }}",
                            data:{kyc_file_id:kyc_file_id,kyc_batch_id:kyc_batch_id,filtered_mun:filtered_mun,kyc_file_name:kyc_file_name,kyc_prov_code:kyc_prov_code,selected_fund_source:selected_fund_source,_token:_token},
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
                                $('#selectedFundSource, .kyc_filter_mun').val('');
                                $('#selectedFundAmount').val(0);
                                $('.Disbursement_totalselectedamt').html("0.00"); 
                                $('.selectedbatchall').prop('checked',false);  
                                var table = $('#DisbursementList-datatable').DataTable();
                                table.clear().draw();                                    
                                var kyc_file_id = $('.kyc_file_id').val();
                                var filterby = "",
                                    filter1 = $('#customRadio1').is(':checked'),
                                    filter2 = $('#customRadio2').is(':checked');
                                if(filter1 === true) {filterby = 0;}
                                if(filter2 === true) {filterby = 1;}
                                getNewlyUploadedBeneficiaries();
                                getBeneficiariesforApproval();
                                getNoofApprovedBeneficiaries();
                                DisbursementApprovalList(kyc_file_id,filterby);
                                $('#ApprovalDisbursementModal').modal('hide');
                                $('#ApprovedDisbursementList-datatable').DataTable().ajax.reload(); 
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
                    kyc_file_id = $('.kyc_file_id').val();
                    selected_fund_source = $('#selectedFundSource').val();
                    selected_fund_amount = $('#selectedFundAmount').val();
                    total_amount = $('#Disbursement_selectedamt').val();
                    kyc_batch_id = $('.kyc_batch_id').val();
                    OverlayPanel_in();
                    if(kyc_file_id != "" && selected_fund_source !=""){
                        if(parseInt(selected_fund_amount) >= parseInt(total_amount)){
                            $.ajax({
                                type:'post',
                                url:"{{ route('approve.Disbursement') }}",
                                data:{kyc_file_id:kyc_file_id,kyc_batch_id:kyc_batch_id,selected_fund_source:selected_fund_source,total_amount:total_amount,_token:_token},
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
                                    var kyc_file_id = $('.kyc_file_id').val();
                                    var filterby = "",
                                        filter1 = $('#customRadio1').is(':checked'),
                                        filter2 = $('#customRadio2').is(':checked');
                                    if(filter1 === true) {filterby = 0;}
                                    if(filter2 === true) {filterby = 1;}
                                    getNewlyUploadedBeneficiaries();
                                    getBeneficiariesforApproval();
                                    getNoofApprovedBeneficiaries();
                                    DisbursementApprovalList(kyc_file_id,filterby);
                                    $('#ApprovalDisbursementModal').modal('hide');
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
                            var kyc_file_id = $('.kyc_file_id').val();
                            var filterby = "",
                                filter1 = $('#customRadio1').is(':checked'),
                                filter2 = $('#customRadio2').is(':checked');
                            if(filter1 === true) {filterby = 0;}
                            if(filter2 === true) {filterby = 1;}
                            DisbursementApprovalList(kyc_file_id,filterby);
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
                            var kyc_file_id = $('.kyc_file_id').val();
                            var filterby = "",
                                filter1 = $('#customRadio1').is(':checked'),
                                filter2 = $('#customRadio2').is(':checked');
                            if(filter1 === true) {filterby = 0;}
                            if(filter2 === true) {filterby = 1;}
                            DisbursementApprovalList(kyc_file_id,filterby);
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

        $(document).on('click','.btnExportDisbursement',function(){
            SpinnerShow('btnExportDisbursement','btnloadingIcon5');
            var kyc_file_id = $('.kyc_file_id').val(),
                kyc_province = $('.kyc_province').val(),
                kyc_batch_id = $('.kyc_batch_id').val();

            $.ajax({
                type:'get',
                url:"{{ route('download.BeneficiariesExcel') }}",
                data:{kyc_file_id:kyc_file_id,kyc_province:kyc_province,kyc_batch_id:kyc_batch_id},
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

    });
</script>
@endsection 

@section('content')
<input type="hidden" id="selectedProgramDesc" value="{{session('Default_Program_Desc')}}">
<input type="hidden" id="selectedProgramId" value="{{session('Default_Program_Id')}}">
<a href="{{route('generate.BeneficiariesExcel')}}" class="DisbursementModule_downloadfile" style="display:none;"></a>
<input type="hidden" id="selectedkycid" value="0">
<input type="hidden" id="selectedfundid" value="0">
<input type="hidden" id="Disbursement_selectedamt" value="0">
<input type="hidden" id="selectedProgramId" value="">
<input type="hidden" id="selectedFundSource" value="">
<input type="hidden" id="selectedFundAmount" value="0">
<input type="hidden" class="kyc_file_id" value="0">
<input type="hidden" class="kyc_file_name" value="">
<input type="hidden" class="kyc_prov_code" value="">
<input type="hidden" class="kyc_province" value="">
<input type="hidden" class="kyc_batch_id" value="">

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
                    <span class="text-inverse pull-right">No. of Beneficiaries: <span class="total_cnt_newly_uploaded">0</span></span>
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
                    <span class="text-inverse pull-right">No. of Beneficiaries: <span class="total_cnt_bene_approval">0</span></span>
                </h4>
            </div>
            <div class="col-xl-12 ui-sortable" style="margin-top:10px;">
                <h4 class="panel-title" style="color: #000;">
                    <span class="fa fa-info-circle"></span> Approved Beneficiaries
                </h4>
                <table id="ApprovedBeneficiaries-datatable" class="table table-striped table-solo" style="width: 100%;">
                    <thead style="background-color: #008a8a"></thead>
                </table>
                <h4 class="panel-title" style="color: #000;">
                    <span class="text-inverse pull-right">Total Amount: <span class="total_amt_approved_bene">₱0.00</span></span>
                    <span class="text-inverse pull-right">|</span>                    
                    <span class="text-inverse pull-right">No. of Beneficiaries: <span class="total_cnt_approved_bene">0</span></span>
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
                             <select id="default_DisbursementMun" class="form-control kyc_filter_mun" name="DisbursementMun" data-size="10" data-style="btn-white" value="{{ old('DisbursementMun') }}" multiple style="width:30% !mportant;">
                            </select>
                            <div class="form-check form-check-inline m-l-10">
                                <input class="form-check-input" type="radio" id="customRadio1" name="inlineRadio" checked>
                                <label class="form-check-label" for="customRadio1">All Beneficiaries</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="customRadio2" name="inlineRadio">
                                <label class="form-check-label" for="customRadio2">All Removed</label>
                            </div>
                            @if (session('role_id') == 8)
                            <select id="default_DisbursementFundSource" class="form-control selectFundsource" name="DisbursementFundSource" data-size="10" data-style="btn-white" value="{{ old('DisbursementFundSource') }}" style="margin-left:5px;">
                            </select>
                            <a href="{{ route('fund_encoding') }}" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Add Fund Source"><i class="fa fa-plus-circle"></i></a>
                            @endif
                            
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

<div class="modal fade bd-example-modal-lg" id="EndorseMunModal" data-backdrop="static" data-keyboard="false">
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
                    <div style="margin-top:10px;">
                        <table id="EndorseMunList-datatable" class="table table-striped display nowrap" style="width: 100%;margin-top:10px;">
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
                        </div>
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