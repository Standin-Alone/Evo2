<script>
    $( document ).ready(function() {
        var data = {!! json_encode($action) !!};

        var program = $(':input[name="detected_program"]').val();
        
        $.each(data, function (key, perm_id) {
            if(program == key){
                if(perm_id  == 1){
                    console.log('nasa create content')
                    $(':input[name="add_role_btn"]').show();
                }
                else if(perm_id == 2){
                    console.log('nasa view content')
                    $(':input[name="add_role_btn"]').hide();
                }
            }
        }); 
    });
</script>