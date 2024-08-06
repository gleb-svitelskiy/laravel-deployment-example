## An example of deploying a Laravel application

### Using Docker compose
To run services:
```shell
docker compose --file .docker/compose.yaml up -d
```
To run DB migrations (wait for the database service to start):
```shell
docker compose --file .docker/compose.yaml exec fpm \
    php artisan migrate --force
```
Now Laravel application will be accessible in your web browser at http://localhost:8000

To delete services:
```shell
docker compose --file .docker/compose.yaml down
```
