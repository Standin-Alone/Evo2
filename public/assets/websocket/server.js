

// const credentials = {
//   appId: '1:113008859700:android:fd68f3484fc08828b2c861',
//   apiKey: 'AAAAGk_aWjQ:APA91bH2Mq4SOK3WZj0niueKEPVqxH153OnM0muJcGIawnlqSRMc0FebbrhkBv2ekdy3S5ZdnA_yraWzKw86BAzHWNePZoFW0bCTRSMPsI7rn7ryZDeUlSn9N4k6OrtrlywJU5hK31aE',
//   projectId: 'imp-mobile-fab69',
//   messagingSenderId: '113008859700',
//   clientId: '113008859700-5js6b2v6u3aoqdoocsio14htb4ntsgdh.apps.googleusercontent.com',
//   databaseURL:'https://imp-mobile-fab69-default-rtdb.asia-southeast1.firebasedatabase.app/'
// };


// var admin = require("firebase-admin");

// var serviceAccount = require("./imp-mobile-fab69-firebase-adminsdk-ygc4q-c4ede6d465.json");

// admin.initializeApp({
//   credential: admin.credential.cert(serviceAccount),
//   databaseURL: "https://imp-mobile-fab69-default-rtdb.asia-southeast1.firebasedatabase.app/"
// });

            
// admin.messaging().send({      
//     "data": {
//       "channel": data.channel,
//       "message": data.message              
//     }, 
//     "notification": {
//       "title": 'Intervention Management Platform',
//       "body": data.message
//     },
    
//     "topic": data.channel 
// }).then((result)=>console.warn('done',result)).catch((err)=>console.warn(err));
// firebase.messaging().sendMessage({data:{token:data.channel,message:data.message}});




var port = 8080;
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);


const connections = new Set();

io.sockets.on('connection', function(socket){
  console.warn('connected')

    
  
  socket.setMaxListeners(0);

  socket.on('room', async function(data){
     
	    io.emit('room',data); 
  
 	
  })

  
// file uploading socket


socket.on('join-room',  function(data){  
    console.warn(`Room ${JSON.stringify(data?.room)} joined.`);
    socket.join(data.room?.from);
})





socket.on('message',  function(data){

    io.emit('notification',data);     

})


socket.on('disconnect',  function(data){
  console.warn('disconnected');
   
})

 
});



http.listen(port, function(data) {

  console.log(`Listening on Port ${port}`);

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
    








