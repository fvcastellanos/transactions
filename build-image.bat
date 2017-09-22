docker rmi -f apps.cavitos.net/transactions:1.0
composer install
php bin/console assets:install web
docker build -t apps.cavitos.net/transactions:1.0 .
docker push apps.cavitos.net/transactions:1.0
