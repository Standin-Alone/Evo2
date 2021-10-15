<script>
$('select#select_program').on('change', function (e) {
    var optionSelected = $("option:selected", this);
    var program_selected = this.value;
    var data = {!! json_encode($action) !!};

    $.each(data, function (key, perm_id) {
        if(program_selected == key){
            if(perm_id  == 1){
                $(':input[name="create_fund_btn"]').prop('disabled', false).removeClass('btn-danger').addClass("btn-outline-success");
                $('span.alert_note').html("");
            }
            else if(perm_id == 2){
                $(':input[name="create_fund_btn"]').prop('disabled', true).removeClass('btn-outline-success').addClass("btn-danger");
                $('span.alert_note').html("*Note:Sorry, you don't have a permission to create fund.");
            }
        }
    }); 
});
</script>