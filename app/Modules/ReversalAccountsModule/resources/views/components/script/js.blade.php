<script>
   $(document).ready(function() {

        App.init();

        ReversalAccountsModuleList();
        ReversalAccountsModuleDetails();
        GeneratedReversalAccountsModule();

        function ReversalAccountsModuleList(){
            SpinnerShow('nobutton','noicon');
            var TotalAmount = 0;
            var table = $('#ReversalAccountsModuleList-datatable').DataTable({ 
                destroy: true, processing: true, serverside: true, paging: false, scrollY: "200px",
                fixedHeader: {
                    header: true,
                    headerOffset: 45,
                    },
                scrollX: true,
                ajax: "{{ route('get.ReversalAccountsModuleList') }}",
                columns: [ 
                    {data: 'kyc_file_id', name: 'kyc_file_id',  title:'PROVINCE',
                        render: function(data, type, row) {
                            return '<a href="javascript:;" data-selectedfileid="'+row.kyc_file_id+'" class="ReversalAccountsModuleList_link" data-toggle="tooltip" data-placement="top" title="View Transaciton"><i class="fas fa-spinner fa-spin '+row.kyc_file_id+' pull-left m-r-10" style="display: none;"></i>'+row.province+'</a>';
                        }
                    },
                    {data: 'date_uploaded', name: 'date_uploaded', title:'DATE UPLOADED'},
                    {data: 'total_count', name: 'total_count', render: $.fn.dataTable.render.number( ',', '.', 0, ''  ).display, title:'TOTAL COUNT'},
                    {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title:'TOTAL AMOUNT'},

                ],
                "columnDefs": [
                        { className: "dt-center", "targets": [2] },
                        { className: "dt-right", "targets": [3] },  
                ],
                "language": {
                            "emptyTable": '<img class="result-image" src="assets/img/product/no_records_1.png" height="auto" width="10%"/>',
                            "zeroRecords": '<img class="result-image" src="assets/img/product/no_records_1.png" height="auto" width="10%"/>',
                            "infoEmpty": ''
                        },
                footerCallback: function (row, data, start, end, display) {                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['total_amount'];
                        TotalAmount += Number(dataval);
                    }
                    $('.ReversalAccountsModuleList_total').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(TotalAmount));
                    $(document).ajaxComplete(function () {
                        SpinnerHide('nobutton','noicon');
                    });
                } 
            });
        }

        $(document).on('click','.ReversalAccountsModuleList_link',function(){
            var file_id = $(this).data('selectedfileid');
            ReversalAccountsModuleDetails(file_id);

        });     
        
        function ReversalAccountsModuleDetails(file_id){
            SpinnerShow('nobutton','noicon');
            var TotalAmount = 0;
            var table = $('#ReversalAccountsModuleDetails-datatable').DataTable({ 
                destroy: true, processing: true, serverside: true, paging: false, scrollY: "200px",
                fixedHeader: {
                    header: true,
                    headerOffset: 45,
                    },
                scrollX: true,
                ajax: {
                        url:"{{ route('get.ReversalAccountsModuleDetails') }}", 
                        data:{file_id:file_id},
                    },
                columns: [ 
                    {data: 'kyc_id', name: 'kyc_id',
                        render: function(data, type, row) {
                            return '<input type="checkbox" data-selectedkycfileid="'+ row.kyc_id +'" id="myCheck-'+ row.kyc_id +'" class="selectedname" name="selectedbatch">';
                        }
                    },
                    {data: 'rsbsa_no', name: 'rsbsa_no'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'middle_name', name: 'middle_name'},
                    {data: 'province', name: 'province'},
                    {data: 'municipality', name: 'municipality'},
                    {data: 'street_purok', name: 'street_purok'},
                    {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display},

                ],
                "columnDefs": [
                        { className: "dt-center", "targets": [0] },
                ],
                "language": {
                            "emptyTable": '<img class="result-image" src="assets/img/product/no_records_1.png" height="auto" width="10%"/>',
                            "zeroRecords": '<img class="result-image" src="assets/img/product/no_records_1.png" height="auto" width="10%"/>',
                            "infoEmpty": ''
                        },
                footerCallback: function (row, data, start, end, display) {                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['amount'];
                        TotalAmount += Number(dataval);
                    }
                    $('.ReversalAccountsModuleDetails_total').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(TotalAmount));
                    $(document).ajaxComplete(function () {
                        SpinnerHide('nobutton','noicon');
                    });
                } 
            });
        }  

        $(document).on('click','.selectall_kyc_file_id',function(){
            $('.selectedname').prop('checked', this.checked);
        });

        function GeneratedReversalAccountsModule(){
            SpinnerShow('nobutton','noicon');
            var TotalAmount = 0;
            var table = $('#GeneratedReversalAccountsModule-datatable').DataTable({ 
                destroy: true, processing: true, serverside: true, paging: false, scrollY: "200px",
                fixedHeader: {
                    header: true,
                    headerOffset: 45,
                    },
                scrollX: true,
                ajax: "{{ route('get.GeneratedReversalAccountsModule') }}",
                columns: [ 
                        {data: 'revert_file_name', name: 'revert_file_name',  title:'FILE NAME',
                            render: function(data, type, row) {
                                return '<a href="javascript:;" data-selectedrfofilename="'+row.revert_file_name+'" class="GeneratedFileDetails_link" data-toggle="tooltip" data-placement="top" title="View Transaciton"><i class="fas fa-spinner fa-spin '+row.revert_file_name+' pull-left m-r-10" style="display: none;"></i>'+row.revert_file_name+'</a>';
                            }
                        },
                        {data: 'province', name: 'province', title:'PROVINCE'},
                        {data: 'revert_date_created', name: 'revert_date_created', title:'DATE CREATED'},                       
                        {data: 'total_count', name: 'total_count', render: $.fn.dataTable.render.number( ',', '.', 0, ''  ).display, title:'NO. OF BENEFICIARIES'},
                        {data: 'total_amount', name: 'total_amount', render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title:'TOTAL AMOUNT'},
                        {data: 'kyc_id', name: 'kyc_id',  title:'ACTION',
                        render: function(data, type, row) {
                            if(row.revert_isdownloaded == 1){
                                return  '<a href="{{route("download.ReversalAccountsModuleTexfile")}}?file_name='+row.revert_file_name+'" target="_blank" class="btn btn-xs btn-outline-danger"><span class="fa fa-download"></span><i class="fas fa-spinner fa-spin '+row.kyc_id+' pull-left m-r-10" style="display: none;"></i> Re-Download</a> '+
                                        '<a href="javascript:;" data-selectedrfofilenameid="'+row.revert_file_name+'" class="btn btn-xs btn-outline-warning ReturnGeneratedTextfile_button text-inverse"><span class="fa fa-share"></span><i class="fas fa-spinner fa-spin '+row.kyc_id+' pull-left m-r-10" style="display: none;"></i> Return</a>';
                            }else{
                                return  '<a href="{{route("download.ReversalAccountsModuleTexfile")}}?file_name='+row.revert_file_name+'" target="_blank" class="btn btn-xs btn-outline-primary"><span class="fa fa-download"></span><i class="fas fa-spinner fa-spin '+row.kyc_id+' pull-left m-r-10" style="display: none;"></i> Download</a> '+
                                        '<a href="javascript:;" data-selectedrfofilenameid="'+row.revert_file_name+'" class="btn btn-xs btn-outline-warning ReturnGeneratedTextfile_button text-inverse"><span class="fa fa-share"></span><i class="fas fa-spinner fa-spin '+row.kyc_id+' pull-left m-r-10" style="display: none;"></i> Return</a>';
                            }                            
                        }
                    },

                ],
                "order": [[ 2, "desc" ]],
                "columnDefs": [
                        { className: "dt-right", "targets": [4] },
                        { className: "dt-center", "targets": [3,5] },
                ],
                "language": {
                            "emptyTable": '<img class="result-image" src="assets/img/product/no_records_1.png" height="auto" width="5%"/>',
                            "zeroRecords": '<img class="result-image" src="assets/img/product/no_records_1.png" height="auto" width="5%"/>',
                            "infoEmpty": ''
                        },
                footerCallback: function (row, data, start, end, display) {                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['total_amount'];
                        TotalAmount += Number(dataval);
                    }
                    $(document).ajaxComplete(function () {
                        SpinnerHide('nobutton','noicon');
                    });
                } 
            });
        }  

        $(document).on('click','.GeneratedFileDetails_link',function(){
            $('#GeneratedFileDetailsModal').modal('toggle');
            var revert_file_name = $(this).data('selectedrfofilename');
            GeneratedFileDetails(revert_file_name);
        });  

        function GeneratedFileDetails(revert_file_name){
            SpinnerShow('nobutton','noicon');
            var TotalAmount = 0;
            var table = $('#GeneratedFileDetails-datatable').DataTable({ 
                destroy: true, processing: true, serverside: true, paging: false, scrollY: "200px",
                fixedHeader: {
                    header: true,
                    headerOffset: 45,
                    },
                scrollX: true,
                ajax: {
                        url:"{{ route('get.GeneratedFileDetails') }}", 
                        data:{revert_file_name:revert_file_name},
                    },
                columns: [
                    {data: 'rsbsa_no', name: 'rsbsa_no', title:'RSBSA NO.'},
                    {data: 'last_name', name: 'last_name', title:'LAST NAME'},
                    {data: 'first_name', name: 'first_name', title:'FIRST NAME'},
                    {data: 'middle_name', name: 'middle_name', title:'MIDDLE NAME'},
                    {data: 'province', name: 'province', title:'PROVINCE'},
                    {data: 'municipality', name: 'municipality', title:'MUNICIPALITY'},
                    {data: 'street_purok', name: 'street_purok', title:'STREET/PUROK'},
                    {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title:'AMOUNT'},

                ],
                "columnDefs": [
                        { className: "dt-center", "targets": [0] },
                ],
                "language": {
                            "emptyTable": '<img class="result-image" src="assets/img/product/no_records_1.png" height="auto" width="10%"/>',
                            "zeroRecords": '<img class="result-image" src="assets/img/product/no_records_1.png" height="auto" width="10%"/>',
                            "infoEmpty": ''
                        },
                footerCallback: function (row, data, start, end, display) {                                       
                    for (var i = 0; i < data.length; i++) {
                        var dataval = data[i]['amount'];
                        TotalAmount += Number(dataval);
                    }
                    $('.GeneratedFileDetails_total').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(TotalAmount));
                    $(document).ajaxComplete(function () {
                        SpinnerHide('nobutton','noicon');
                    });
                } 
            });
        }  

        $(document).on('click','.ReversalAccountsModuleDetails_btn',function(){

            var selectedkycfileid = $('.selectedname:checked').map(function(){
                return $(this).data('selectedkycfileid');}).get().join(",");      

                if(!selectedkycfileid){
                        Swal.fire({
                            allowOutsideClick: false,
                            title:'Error!',
                            text:'Please Select Beneficiary for Reversal Accounts!',
                            icon:'error'
                        }); 
                        return false;
                    }
                    SpinnerShow('nobutton','noicon');
                var TotalAmount = 0;
                var table = $('#previewSeletedBeneficiaries-datatable').DataTable({ 
                    destroy: true, processing: true, serverside: true, paging: false, scrollY: "200px",
                    fixedHeader: {
                        header: true,
                        headerOffset: 45,
                        },
                    scrollX: true,
                    ajax:{
                        url: "{{ route('get.previewSeletedBeneficiaries') }}",
                        type: "post",
                        data:{selectedkycfileid:selectedkycfileid},
                    }, 
                    columns: [ 
                        {data: 'rsbsa_no', name: 'rsbsa_no', title:'RSBSA NO.'},
                        {data: 'last_name', name: 'last_name', title:'LAST NAME'},
                        {data: 'first_name', name: 'first_name', title:'FIRST NAME'},
                        {data: 'middle_name', name: 'middle_name', title:'MIDDLE NAME'},
                        {data: 'province', name: 'province', title:'PROVINCE'},
                        {data: 'municipality', name: 'municipality', title:'MUNICIPALITY'},
                        {data: 'street_purok', name: 'street_purok', title:'STREET/PUROK'},
                        {data: 'amount', name: 'amount', render: $.fn.dataTable.render.number( ',', '.', 2, ''  ).display, title:'AMOUNT'},

                    ],
                    "columnDefs": [
                            { className: "dt-center", "targets": [0] },
                    ],
                    "language": {
                                "emptyTable": '<img class="result-image" src="assets/img/product/no_records_1.png" height="auto" width="10%"/>',
                                "zeroRecords": '<img class="result-image" src="assets/img/product/no_records_1.png" height="auto" width="10%"/>',
                                "infoEmpty": ''
                            },
                    footerCallback: function (row, data, start, end, display) {                                       
                        for (var i = 0; i < data.length; i++) {
                            var dataval = data[i]['amount'];
                            TotalAmount += Number(dataval);
                        }
                        $('.previewSeletedBeneficiaries_Total').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display(TotalAmount));
                        $(document).ajaxComplete(function () {
                            SpinnerHide('nobutton','noicon');
                        });
                    } 
                });
                $('#previewSeletedBeneficiariesModal').modal('toggle');            

        });

        $(document).on('click','.GenerateReversalAccountsModuleDetails_btn',function(){

            var selectedkycfileid = $('.selectedname:checked').map(function(){
                return $(this).data('selectedkycfileid');}).get().join(',');

            var _token = $("input[name=token]").val();
                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Submit all selected farmers for Reversal Accounts?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, Submit it!',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        SpinnerShow('nobutton','noicon');
                        $.ajax({
                            type:'post',
                            url:"{{ route('submit.ReversalAccountsModuleDetails') }}",
                            data:{selectedkycfileid:selectedkycfileid,_token:_token},
                            success:function(data){                         
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Submitted!',
                                    text:'Your Request successfully Submitted!',
                                    icon:'success'
                                });
                                $('.selectall_kyc_file_id').prop('checked',false);
                                ReversalAccountsModuleList();
                                ReversalAccountsModuleDetails();
                                GeneratedReversalAccountsModule();
                                $('#previewSeletedBeneficiariesModal').modal('hide');
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('nobutton','noicon');
                            }
                        });        
                    }
                });
        }); 

        $(document).on('click','.ReturnGeneratedTextfile_button',function(){
            
            var selectedrfofilenameid = $(this).data('selectedrfofilenameid');
            var _token = $("input[name=token]").val();

                Swal.fire({
                    title: 'Are you sure',
                    text: "You want to Return this generated text file?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, Return it!',
                    allowOutsideClick: false
                    }).then((result) => {
                    if (result.isConfirmed) {
                        SpinnerShow('nobutton','noicon');
                        $.ajax({
                            type:'post',
                            url:"{{ route('Return.GeneratedTextfile') }}",
                            data:{selectedrfofilenameid:selectedrfofilenameid,_token:_token},
                            success:function(data){                         
                                Swal.fire({
                                    allowOutsideClick: false,
                                    title:'Submitted!',
                                    text:'Your Request successfully Submitted!',
                                    icon:'success'
                                });
                                ReversalAccountsModuleList();
                                ReversalAccountsModuleDetails();
                                GeneratedReversalAccountsModule();
                                $('#previewSeletedBeneficiariesModal').modal('hide');
                        },
                        error: function (textStatus, errorThrown) {
                                console.log('Err');
                                SpinnerHide('nobutton','noicon');
                            }
                        });        
                    }
                });
        });

   });
</script>

