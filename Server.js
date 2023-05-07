
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

io.on('connection', (socket) => {
    console.log('a user connected');

    socket.on('message', (message) => {
        console.log('message: ' + message);
        io.emit('message', message);
    });

    socket.on('disconnect', () => {
        console.log('user disconnected');
    });
});

http.listen(3000, () => {
    console.log('listening on *:3000');
});
