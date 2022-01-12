{{-- datatable responsive --}}
<script src="//cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.js"></script>
<script src="//cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

{{-- datatable buttons --}}
{{-- <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script> --}}
<script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.colVis.min.js"></script>

{{-- datatable row group --}}
<script src="https://cdn.datatables.net/rowgroup/1.1.3/js/dataTables.rowGroup.min.js"></script>

{{-- <script>
    var solv = $('table#voucher-claimed-datatable').find('tfoot').find('th.grand_total_col').html('Grand Total:');
    console.log(solv);
</script> --}}

<script>
    // $(document).ready(function(){
    //     var mulipleCancelButton = new Choices('#filter_region', {
    //         removeItemButton: true,
    //         maxItemCount:0,
    //         searchResultLimit:5,
    //         renderChoiceLimit:0
    //     });

    //     $('#filter_region').on('change', function(){
    //         var select = {};
    //         var propName = $(this).val();

    //         select[propName] = [];

    //         $(this).children().each(function(){
    //             select[propName].push({
    //                 optionValue: $(this).append('<option value="'+propName+'"></option>')
    //             });
    //         });

    //         if(select[propName].length == 0){
    //             select[propName] = [""];
    //         }else{
    //             var types = $('select[name="filter_region_tbl_02"]').map(function() { 
    //                 return '^' + propName + '\$';
    //             }).get().join('|');
    //             $('#co-program-focal-datatable-by-region-province-municipality-and-barangay').DataTable().columns(0).search(propName.join('|'), true, false, true).draw();
    //         }
    //     });
    // });

    // $(document).ready(function(){
    //     var mulipleCancelButton = new Choices('#filter_province', {
    //         removeItemButton: true,
    //         maxItemCount:0,
    //         searchResultLimit:5,
    //         renderChoiceLimit:0
    //     });

    //     $('#filter_province').on('change', function(){
    //         var select = {};
    //         var propName = $(this).val();

    //         select[propName] = [];

    //         $(this).children().each(function(){
    //             select[propName].push({
    //                 optionValue: $(this).append('<option value="'+propName+'"></option>')
    //             });
    //         });

    //         if(select[propName].length == 0){
    //             select[propName] = [""];
    //         }else{
    //             var types = $('select[name="filter_province_tbl_02"]').map(function() { 
    //                 return '^' + propName + '\$';
    //             }).get().join('|');
    //             $('#co-program-focal-datatable-by-region-province-municipality-and-barangay').DataTable().columns(1).search(propName.join('|'), true, false, true).draw();
    //         }
    //     });
    // });
</script>