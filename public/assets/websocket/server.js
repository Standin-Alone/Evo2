var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var redis= require('redis');
var redis_adapter = require('@socket.io/redis-adapter');





var sredis = require('socket.io-redis');
const { Socket } = require('dgram');


io.on('connection', function(socket){



    
    socket.on('message',function(data){
     
      io.emit('progress',data);
   
      
    })
    
  });



http.listen(3000, '127.0.0.1', function(data) {

  console.log('Listening on Port 3000');
});




//  pubClient.on('connect', function (err) {
   
 
  

//   pubClient.subscribe('channel');
   
//    pubClient.on("message", function (channel, my_channel) {

    
        

 


//   pubClient.psubscribe('messages:'+my_channel,function(message_channel){
//     pubClient.on("pmessage", function (pattern,channel, message) {


       
//         //Send this event to everyone in the room.
//         if(message == '100%'){
          

            
    

//         }
        
//         io.emit('client_room', my_channel);
        
//         if(JSON.parse(message).channel == my_channel){
//           console.warn(my_channel);
//           io.emit('progress:'+my_channel, {channel : JSON.parse(message).channel , message: JSON.parse(message).percentage});
//         }
        
        
//      })

      
      
      
//     });
    
//   });


   
    
//  }).on('error', function (error) {
//   console.log(error);
// });
// ;


// io.on('connection', function(socket) {
//   var pubClient = redis.createClient({host:'127.0.0.1,',port:'6379'});
//   pubClient.subscribe('messages');  
//   pubClient.on('messages',function(channel, message){
//     console.log("mew message in queue "+ message + "channel");
//     console.warn(message);
//   })

// });




