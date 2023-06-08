const http = require('http');
const { Server } = require('socket.io');

// HTTP sunucusunu oluşturun
const server = http.createServer();
const port = 3000;

// Socket.IO sunucusunu oluşturun ve HTTP sunucusunu kullanarak başlatın
const socketIO = new Server(server);

// Connection olayını dinleyin
socketIO.on('connection', (socket) => {
    const socketID = socket.id; // Socket ID değerini alın
    console.log('Yeni bir bağlantı:', socketID);
    console.log('Yeni bir kullanıcı bağlandı');

    // test-event olayını dinleyin
    socket.on('message', function (data) {
        console.log(data);
    });

    // Disconnect olayını dinleyin
    socket.on('disconnect', () => {
        console.log('Bir kullanıcı ayrıldı');
    });
});

// Sunucuyu dinleyin
server.listen(port, () => {
    console.log(`Sunucu çalışıyor: http://localhost:${port}`);
});
