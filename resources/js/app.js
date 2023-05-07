require('./bootstrap');

import io from 'socket.io-client';
const socket = io('http://localhost:3000');

// Wait for the DOM to load
document.addEventListener('DOMContentLoaded', () => {
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message');

    chatForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const message = messageInput.value;

        // Emit the message to the server
        socket.emit('message', message);

        // Clear input field
        messageInput.value = '';
    });
});
