var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var redis= require('redis');
var redis_adapter = require('@socket.io/redis-adapter');





var sredis = require('socket.io-redis');
io.adapter(sredis({ host: '127.0.0.1', port: 6379 }));


 pubClient =  redis.createClient(6379,'127.0.0.1');

 pubClient.on('connect', function (err) {
   
   console.warn('connection');
   pubClient.psubscribe('my_channel:*');  
 
    pubClient.on("message", function (pattern,channel, my_channel) {
      console.warn(pattern)
      // pubClient.subscribe('message:'+my_channel)
      // pubClient.on("message:"+my_channel, function (channel, data) {
 
      //   io.emit('progress:'+my_channel,data);
      // });
   
    });
 }).on('error', function (error) {
  console.log(error);
});
;


// io.on('connection', function(socket) {
//   var pubClient = redis.createClient({host:'127.0.0.1,',port:'6379'});
//   pubClient.subscribe('messages');  
//   pubClient.on('messages',function(channel, message){
//     console.log("mew message in queue "+ message + "channel");
//     console.warn(message);
//   })

// });

http.listen(3000, '127.0.0.1', function(data) {

  console.log('Listening on Port 3000');
});




