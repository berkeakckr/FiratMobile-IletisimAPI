var express = require('express');
var app = express();
var http = require('http').Server(app);
const socketio = require('socket.io');
//var socketio = require('./socket');
var io = socketio(http);

// Express sunucusunu 8000 portunda çalıştırma
var server = http.listen(8000, function() {
    console.log('Express sunucusu 8000 portunda çalışıyor');
});

// Socket.IO sunucusunu 3000 portunda çalıştırma

var socketIOServer = io.listen(3000);
socketIOServer.attach(server);

io.on('connection', function(socket) {
    console.log("Socket connected:", socket.id);

    socket.on('message', function(data) {
        console.log('Gönderilen Mesaj:', data[0].text);
        io.emit('message', data); // Tüm soketlere mesajı yayınla
    });
});
