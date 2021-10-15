<script>
    function template ( d ) {
        return '<table class="table table-bordered">'+
                 // image interventions
                 '<tr>'+
                    '<td>Interventions:</td>'+
                    '<td>'+
                        '<div class="container__img-holder">'+
                            '<img src="https://images.unsplash.com/photo-1609342122563-a43ac8917a3a?ixlib=rb-1.2.1&ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&auto=format&fit=crop&w=240&q=80">'+
                        '</div>'+
                        '<div class="container__img-holder">'+
                            '<img src="https://images.unsplash.com/photo-1609342122563-a43ac8917a3a?ixlib=rb-1.2.1&ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&auto=format&fit=crop&w=240&q=80">'+
                        '</div>'+
                        '<div class="container__img-holder">'+
                            '<img src="https://images.unsplash.com/photo-1609342122563-a43ac8917a3a?ixlib=rb-1.2.1&ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&auto=format&fit=crop&w=240&q=80">'+
                        '</div>'+
                    '</td>'+
                 '</tr>'+ 
                 // Map Location
                 '<tr>'+
                    '<td>Location:</td>'+
                    '<td>'+
                        '<div class="row">'+
                            '<div class="col-md-12 modal_body_map">'+
                                '<iframe id="map_canvas" width="600" height="450" style="border:0" loading="lazy" allowfullscreen src="https://www.google.com/maps/embed/v1/place?key=AIzaSyC6JVpfd5wzUy4nYmymW1OTpuhSMbTkBe8&q='+d.latitude+'+'+d.longitude+'"></iframe>'+
                            '</div>'+
                        '</div>'+
                    '</td>'+
                 '</tr>'+   
            '</table>';
    }       
</script>
<script>
    // image intervention clicking function: 
    $(document).on('click', '.container__img-holder', function(){
        var img_src = $(this).children('img').attr('src');
        imgWindow = window.open(img_src, 'imgWindow');
    });
</script>