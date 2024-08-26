## An example of deploying a Laravel application

### Local launch
Make sure that your local machine has PHP and Composer installed.

Project initialization
```shell
composer run-script post-root-package-install && \
composer install && \
composer run-script post-create-project-cmd
```
Start Laravel's local development server
```shell
php artisan serve
```

### [Using Docker compose](.docker/README.md)

### Using Kubernetes

- [kubeadm](.kubernetes/kubeadm/README.md)
