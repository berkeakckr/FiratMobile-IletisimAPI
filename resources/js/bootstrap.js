import server from 'socket.io';
import redisAdapter from 'socket.io-redis';
import * as messageController from './Controllers/messageController';

//window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
const io = new server(3000);
io.adapter(redisAdapter({ host: 'localhost', port: 6379 }));

io.on('connection', (socket) => {
    console.log('Bir kullanıcı bağlandı');

    socket.on('disconnect', () => {
        console.log('Bir kullanıcı ayrıldı');
    });
});

const redis = require('redis');
const redisClient = redis.createClient();
redisClient.subscribe('new_message');

redisClient.on('message', (channel, message) => {
    const parsedMessage = JSON.parse(message);
    // Mesajı messages tablosuna kaydet
    messageController.saveMessage(parsedMessage);

    // Socket.io üzerinden mesajı emit et
    io.emit('message', parsedMessage);
});
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
//window.io = require('socket.io-client');
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
