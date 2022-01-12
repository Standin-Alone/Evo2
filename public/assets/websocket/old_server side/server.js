// var app = require('express')();
var http = require('http').Server(app);
var express = require('express');
var express_app = express();
var ip = require('ip');
var fs = require('fs');
var privateKey  = fs.readFileSync('/etc/letsencrypt/live/devsysadd.da.gov.ph/privkey.pem', 'utf8');
var certificate = fs.readFileSync('/etc/letsencrypt/live/devsysadd.da.gov.ph/cert.pem', 'utf8');
var chain = fs.readFileSync('/etc/letsencrypt/live/devsysadd.da.gov.ph/chain.pem', 'utf8');
var credentials = {key: privateKey, cert: certificate,ca:chain,rejectUnauthorized: false };



var https= require('https');

var app = https.createServer(credentials,express_app);
// var io = require('socket.io').listen(app);

app.listen(7980);


var io 			= require('socket.io').listen(app,{
  handlePreflightRequest: (req, res) => {
        const headers = {
            "Access-Control-Allow-Headers": "Content-Type, Authorization",
            "Access-Control-Allow-Origin": req.headers.origin, //or the specific origin you want to give access to,
            "Access-Control-Allow-Credentials": true
        };
        res.writeHead(200, headers);
        res.end();
    }
});

console.warn(app);
console.warn(io);



console.log('server running...');
// io.set('transports', ['websocket','polling']);

io.sockets.on('connection', function(socket){


  console.log('connected');
    
    socket.on('message',function(data){
     
      io.emit('progress',data);
   
      
    })
    


  })
  io.sockets.on("connect_failed", (err) => {
    console.log(`connect_error due to ${err.message}`);
  });
    
