//prepare libraries
//var mysql 		= require('mysql');
var fs 			= require('fs');

var express 	= require('express');
var app 		= express();
var http 		= require('http');
var https 		= require('https');

//var privateKey  = fs.readFileSync('../../apache/conf/ssl.key/server.key', 'utf8'); 
//var privateKey  = fs.readFileSync('/etc/letsencrypt/live/devsysadd.da.gov.ph/privkey.pem', 'utf8');
//var certificate = fs.readFileSync('../../apache/conf/ssl.crt/server.crt', 'utf8');
//var certificate = fs.readFileSync('/etc/letsencrypt/live/devsysadd.da.gov.ph/cert.pem', 'utf8');
//var credentials = {key: privateKey, cert: certificate,rejectUnauthorized: false};

//var server 		= http.createServer(app);
var server = https.createServer({ 
                key: fs.readFileSync('/etc/letsencrypt/live/devsysadd.da.gov.ph/privkey.pem'),
                cert: fs.readFileSync('/etc/letsencrypt/live/devsysadd.da.gov.ph/cert.pem'),
                ca: fs.readFileSync('/etc/letsencrypt/live/devsysadd.da.gov.ph/chain.pem'),
                requestCert: false         
             },app);
//var httpsServer = https.createServer(credentials, app);
var io 			= require('socket.io').listen(server,{
						handlePreflightRequest: (req, res) => {
					        const headers = {
					            "Access-Control-Allow-Headers": "Content-Type, Authorization",
					            "Access-Control-Allow-Origin": req.headers.origin, //or the specific origin you want to give access to,
					            "Access-Control-Allow-Credentials": true
					        };
					        res.writeHead(200, headers);
					        res.end();
				    	},
              transports: ['polling','websocket'], 
				  });




//console.log(mysqlEventWatcher);



//console.log(watcher_notif);

connections = [];
users 		= [];
//console.log(httpsServer);
var serverPort = process.env.PORT || 8443;
server.listen(serverPort, function() {
  console.log('server up and running at %s port', serverPort);
});
// var serverPort = process.env.PORT || 8443;
// httpsServer.listen(serverPort, function() {
//   console.log('server up and running at %s port', serverPort);
// });

console.log('Server running...');

// app.get('/', function(req, res){
// 	res.sendFile(__dirname + '/index.php');
// });


io.sockets.on('connection', function(socket){
	connections.push(socket); //everytime a client is connected push it to the connections array to count the number of connected clients
	console.log('Connected: %s sockets connected', connections.length);

	// // Disconnect
	// socket.on('disconnect', function(data) {
	// 	users.splice(users.indexOf(socket.users), 1);
	// 	connections.splice(connections.indexOf(socket), 1);
	// 	//console.log('Disconnected: %s sockets connected', connections.length);
	// 	online_users();
	// });

	// //when the client request for userdata, throw users data to the client
	// socket.on('userdata', function(data){	
	// 	socket.users = data;
	// 	users.push(socket.users);
	// });

	// //when the user trigger the send chat button, throw the other end reply thru websocket
	// socket.on('send message', function(data){
	// 	io.sockets.emit('new message', data);
	// });

	// online_users();

	// function online_users(){
	// 	io.sockets.emit('online users', connections.length);
	// }	
});