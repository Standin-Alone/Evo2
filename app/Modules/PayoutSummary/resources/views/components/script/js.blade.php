<script type="text/javascript">
    $(document).ready(function (){
        
        PayoutSummaryList();

        function PayoutSummaryList(){
            $('#PayoutSummaryList-datatable').unbind('click');
                var table = $('#PayoutSummaryList-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.PayoutSummaryList') }}",
                    columns: [ 
                        {data: 'program', name: 'program', title: 'PROGRAM'},
                        {data: 'transac_date', name: 'transac_date', title: 'TRANSACIONT DATE'},
                        {data: 'application_number', name: 'application_number', title: 'APPLICATION NO.'},
                        {data: 'description', name: 'description', title: 'DESCRIPTION'},
                        {data: 'amount', name: 'amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'}, 
                    ],
                    columnDefs: [
                                { "visible": false, "targets": 0,}
                            ],
                    order: [[0, 'asc']],
                    rowGroup: {
                        dataSrc: function (data) {
                                return '<span>'+data.program+'</span>';
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
                        },
                    footerCallback: function (row, data, start, end, display) { 
                        var TotalAmount = 0;                                        
                            for (var i = 0; i < data.length; i++) {
                                var dataval = data[i]['grandtotalamount'];
                                TotalAmount = parseInt(dataval);
                            }
                            $('.totalpayoutsamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount ));                    
                    }
            }).ajax.reload();
        }

        $(document).on('click','.PayoutSummaryDetails_btn_view',function(){
            var  batch_id = $(this).data('selectedbatchid');                    
                $('#PayoutSummaryDetails-datatable').unbind('click');
                var table = $('#PayoutSummaryDetails-datatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: "{{ route('get.PayoutSummaryDetails') }}" + '?batch_id=' + batch_id,
                    columns: [                       
                        {data: 'transac_date', name: 'transac_date', title: 'TRANSACTION DATE'},
                        {data: 'reference_no', name: 'reference_no', title: 'REFERENCE NO.'},
                        {data: 'item_name', name: 'item_name', title: 'ITEM NAME'},
                        {data: 'quantity', name: 'quantity', title: 'QUANTITY'},
                        {data: 'total_amount', name: 'total_amount',render: $.fn.dataTable.render.number( ',', '.', 2, '&#8369;'  ).display, title: 'TOTAL AMOUNT'},
                        {data: 'action', name: 'action', orderable: false, searchable: false, title: 'ACTION'}, 
                    ],
                    columnDefs: [
                            { "visible": false, "targets": 0,}
                        ],
                order: [[0, 'asc']],
                rowGroup: {
                    dataSrc: function (data) {
                            return '<span>'+data.program+'</span>';
                        },
                    starRender:null,
                    endRender: function(rows){
                            var total_amount_claim = rows
                            .data()
                            .pluck('total_amount')
                            .reduce( function (a, b) {
                                        return (a)*1 + (b)*1;
                            }, 0 );
                            return '<span>Page Total: '+$.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( total_amount_claim )+'</span>';
                        },                    
                    },
                footerCallback: function (row, data, start, end, display) { 
                    var TotalAmount = 0;                                        
                        for (var i = 0; i < data.length; i++) {
                            var dataval = data[i]['grandtotalamount'];
                            TotalAmount = parseInt(dataval);
                        }
                        $('.payoutsummarytotalamt').html($.fn.dataTable.render.number(',', '.', 2, '&#8369;').display( TotalAmount ));                    
                    }            
                }).ajax.reload();
            });

        $(document).on('click','.PayoutSummaryAttachments',function(){            
            var _token = $("input[name=token]").val(),
                voucher_id = $(this).data('selectvoucherid');
            $.ajax({
                type:'get',
                url:"{{ route('get.PayoutSummaryAttachments') }}",
                data:{voucher_id:voucher_id,_token:_token},
                success:function(data){
                    $('.payoutsummaryattachmentsimgcontent').html(data);
                },
                error: function (textStatus, errorThrown) {
                        console.log('Err');
                    }
            });        
        }); 
        
    });
    
</script>