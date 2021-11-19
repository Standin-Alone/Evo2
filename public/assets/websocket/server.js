var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);



io.on('connection', function(socket){



    
    socket.on('message',function(data){
     
      io.emit('progress',data);
   
      
    })
    
  });



http.listen(7980, '127.0.0.1', function(data) {

  console.log('Listening on Port 7980');
});






