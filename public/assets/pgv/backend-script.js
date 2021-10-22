// AUTO COMMA & DECIMAL REPLACE
function addCommas(x) {
    var y = parseFloat(x).toFixed(2);
        if(y == 'NaN') {
            y = '0.00';
        }
    var parts = y.toString().split(".");

    // REMOVE CURRENCY
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");            
}

// CSRF-TOKEN
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// REFRESH DATATABLE BASED ON SELECTED PROGRAM
$(document).on('click','.reload_panel',function(){
    location.reload(); 
});

// LOADING BUTTON ANIMATION
function LoadingAction(btnClass,btnCaption,classLoading,classCaption){    
    var btn = $(btnClass).button('loading');
        btn.prop('disabled',true);
        btn.addClass('disabled');
        btn.css('cursor','not-allowed');
        $(''+classLoading).css('display','block');
        $(''+classCaption).html('Loading...');
    setTimeout(function () {
        $(''+classCaption).html(btnCaption);
        btn.prop('disabled',false);
        btn.removeClass('disabled');
        btn.css('cursor','pointer');
        $(''+classLoading).css('display','none');
    }, 1000);
}

$('form input').keydown(function (e) {
    if (e.keyCode == 13) {
        var inputs = $(this).parents("form").eq(0).find(":input");
        if (inputs[inputs.index(this) + 1] != null) {                    
            inputs[inputs.index(this) + 1].focus();
        }
        e.preventDefault();
        return false;
    }
});

function AlertHide(errormsg){
    setTimeout(function () {
        $('.'+errormsg).css('display','none');
    }, 10000); 
}


function SpinnerShow(btnClass,classLoading){
    OverlayPanel_in();
    // $('#overlay').fadeIn(100);
    setTimeout(function () {
        $('.'+btnClass).prop('disabled',true);
        $('.'+btnClass).css('cursor','not-allowed');
        $('.'+classLoading).css('display','block');

        $('[data-dismiss=modal]').prop('disabled',true);
        $('[data-dismiss=modal]').css('cursor','not-allowed');
    }, 100); 
}

function SpinnerHide(btnClass,classLoading){
    setTimeout(function () {
        $('.'+btnClass).prop('disabled',false);
        $('.'+btnClass).css('cursor','pointer');
        $('.'+classLoading).css('display','none');
        $(btnClass).unbind('click');

        $('[data-dismiss=modal]').prop('disabled',false);
        $('[data-dismiss=modal]').css('cursor','pointer');
        OverlayPanel_out();
        // $('#overlay').fadeOut(1000);
    }, 1000); 
}

$(document).on('change','.dataTables_length',function(){
    // $('#overlay').fadeIn(100);
    // $('#overlay').fadeOut(2000);
    OverlayPanel_in();
    OverlayPanel_out();
    $('.selectedbatchall').prop('checked',false);  
});

$(document).on('click','[data-dismiss=modal]',function(){
    // $('#overlay').fadeOut(100);
    OverlayPanel_in();
    OverlayPanel_out();
});

// $('[data-dismiss=modal]').modal({
//     backdrop: 'static',
//     keyboard: false
// })

// $('#HeadApprovalList-datatable').on('click','span',function(){
//     alert("wew");
// });


function OverlayPanel_in(){
    Swal.fire({
        allowOutsideClick: false,
        // html:'<img src="../img/images/da-loading.gif" width="1000" height="1000" class="fa fa-span" />',   
        html:'<span class="loading-logo"></span>',
        timer: 1000,
        showCancelButton: false,
        showConfirmButton: false,
    });   
    $('.swal2-popup').css('background','transparent');
    $('.swal2-html-container').css('overflow','hidden');
}

function OverlayPanel_out(){    
    swal.close();
}





    

    