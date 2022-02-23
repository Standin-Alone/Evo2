
function socket(){
    return  io.connect('http://'+window.location.hostname+':8080',{ transports: ['websocket','polling'],allowEIO3:true,rejectUnauthorized: true});
}





