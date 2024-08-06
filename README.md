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

### Using Kubernetes
Build a docker image:
```shell
docker buildx build \
  --platform=linux/amd64,linux/arm64 \
  --file=.docker/fpm/Dockerfile \
  --tag=glebsvitelskiy/laravel:fpm-3 \
  --push \
  .
```
Apply manifests:
```shell
kubectl apply -f .kubernetes --recursive
```
Run DB migrations (wait for the database service to start):
```shell
kubectl exec \
  $(kubectl get po --selector=app=laravel --output=jsonpath='{.items[0].metadata.name}') \
  --container=fpm \
  -- php artisan migrate --force
```

Now Laravel application will be accessible in your web browser at https://laravel.tableride.app

Delete all resources:
```shell
kubectl delete -R -f .kubernetes
```
Diff
```shell
kubectl diff -R -f .kubernetes
```
