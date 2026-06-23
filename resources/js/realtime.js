import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster:       'pusher',
    key:               process.env.MIX_PUSHER_APP_KEY,
    cluster:           process.env.MIX_PUSHER_APP_CLUSTER,
    wsHost:            process.env.MIX_PUSHER_APP_HOST || window.location.hostname,
    wsPort:            parseInt(process.env.MIX_PUSHER_APP_PORT || '6001'),
    wssPort:           parseInt(process.env.MIX_PUSHER_APP_PORT || '6001'),
    forceTLS:          false,
    disableStats:      true,
    enabledTransports: ['ws'],
});
