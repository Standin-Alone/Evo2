var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);



io.on('connection', function(socket){

  socket.setMaxListeners(0);
  console.warn('connected server side');
  socket.on('message',function(data){
     
	io.emit('progress',data);
 	
  })
  
 
});



http.listen(7980, '172.17.150.112', function(data) {

  console.log('Listening on Port 7980');
});





// FOR HTTPS 


// var app = require('express')();
// var http = require('http').Server(app);

// var ip = require('ip');
// var fs = require('fs');
// var privateKey  = fs.readFileSync('/etc/letsencrypt/live/devsysadd.da.gov.ph/privkey.pem', 'utf8');
// var certificate = fs.readFileSync('/etc/letsencrypt/live/devsysadd.da.gov.ph/cert.pem', 'utf8');
// var chain = fs.readFileSync('/etc/letsencrypt/live/devsysadd.da.gov.ph/chain.pem', 'utf8');
// var credentials = {key: privateKey, cert: certificate};



// var https= require('https').createServer(credentials,app);
// var io = require('socket.io')(https);
// https.listen(7980,function(data) {

//   console.log('Listening on Port 7980');
// });

// io.set('transports', ['websocket','polling']);

// io.on('connection', function(socket){


//   console.log('connected');
    
//     socket.on('message',function(data){
     
//       io.emit('progress',data);
   
      
//     })
    


//   })
    








