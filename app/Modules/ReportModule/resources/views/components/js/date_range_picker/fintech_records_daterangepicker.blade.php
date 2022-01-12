<script type="text/javascript">
    $(function() {
        var fin_start_date = moment().startOf('month');
        var fin_end_date = moment().endOf('month');

        function cb(fin_start_date, fin_end_date) {
            $('#fintech_record_daterangepicker span').html(fin_start_date.format('MMMM D, YYYY') + ' - ' + fin_end_date.format('MMMM D, YYYY'));
            // console.log(fin_start.format('MMMM D, YYYY'));
            // console.log(fin_end_date.format('MMMM D, YYYY'));
        }

        $('#fintech_record_daterangepicker').daterangepicker({
                showDropdowns: true,
                startDate: fin_start_date,
                endDate: fin_end_date,
                ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                },
                buttonClasses: ['btn btn-default fin_btn_apply'],
        }, cb);
        cb(fin_start_date, fin_end_date);
    });
</script>