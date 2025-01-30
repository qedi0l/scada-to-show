#!/bin/sh
composer install

chmod 0444 ./.rr.yaml

if [ ! -f /usr/local/bin/protoc-gen-php-grpc ]; then
    ./vendor/bin/rr download-protoc-binary -l /usr/local/bin
fi

export PATH="$PATH:$HOME/.local/bin"

php artisan protobuf:generate:client
php artisan protobuf:generate:server
chmod 777 -R ./Protobuf/
php artisan key:generate
php artisan migrate
php artisan octane:start --host 0.0.0.0 --rpc-port=${CONTAINER_RPC_PORT:-6001} --port=${CONTAINER_APP_PORT:-80}

while true
 do
     php artisan schedule:run
     sleep 3600
done
