import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Import Pusher and Laravel Echo
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Configure Echo with your Pusher credentials
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '728869e18dca551f2b50', // Your Pusher key
    cluster: 'ap1', // Your Pusher cluster
    forceTLS: true,
    encrypted: true,
});
