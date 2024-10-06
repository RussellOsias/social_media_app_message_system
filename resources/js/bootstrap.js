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
    key: 'd006c53948c8e840c514', // Replace with your Pusher key
    cluster: 'ap1', // Replace with your Pusher cluster
    forceTLS: true,
    encrypted: true,
});
