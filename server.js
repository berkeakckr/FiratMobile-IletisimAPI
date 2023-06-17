const express = require('express');
const app = express();
const http = require('http').createServer(app);
const cors = require('cors'); // cors paketini import edin
app.use(cors()); // CORS hatasını çözmek için eklenen satır
const io = require('socket.io')(http, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});

// Express sunucusunu 8000 portunda çalıştırma
var server = http.listen(8000, function() {
    console.log('Express sunucusu 8000 portunda çalışıyor');
});

// Socket.IO sunucusunu 3000 portunda çalıştırma

var socketIOServer = io.listen(3000);
socketIOServer.attach(server);

http.listen(3000, () => {
    console.log('listening on *:3000');
// Sunucuyu dinleyin
    server.listen(port, () => {
        console.log(`Sunucu çalışıyor: http://localhost:${port}`);

io.on('connection', function(socket) {
    console.log("Socket connected:", socket.id);

    socket.on('message', function(data) {
        console.log('Gönderilen Mesaj:', data[0].text);
        io.emit('message', data); // Tüm soketlere mesajı yayınla
    });
});
