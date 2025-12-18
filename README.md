
composer require pusher/pusher-php-server

php artisan make:event eventTurno

BROADCAST_DRIVER=pusher

añadir al evento implements ShouldBroadcast
