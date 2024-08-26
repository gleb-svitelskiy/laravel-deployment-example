## An example of deploying a Laravel application

### Running locally
Ensure that your local machine has PHP and Composer installed.
```shell
glib@mbp laravel-deployment-example % composer -V
Composer version 2.7.8 2024-08-22 15:28:36
PHP version 8.3.10 (/opt/homebrew/Cellar/php/8.3.10/bin/php)
Run the "diagnose" command to get more detailed diagnostics output.
```

Project initialization:
```shell
composer run-script post-root-package-install && \
composer install && \
composer run-script post-create-project-cmd
```

Start the Laravel development server:
```shell
php artisan serve
```

### 2. Running with Docker Compose
Docker Compose allows you to define and manage multi-container Docker applications. It is particularly useful when you need to run multiple services on a single host.

If you're running the application locally, you can use Docker Desktop to manage Docker containers on your machine.

For detailed setup instructions, refer to the [Docker Compose README](.docker/README.md).

### 3. Running on Kubernetes with kubeadm
This section describes how to deploy Laravel application on a Kubernetes cluster created using the `kubeadm` tool.

For detailed setup instructions and configuration, refer to the [kubeadm README](.kubernetes/kubeadm/README.md).

### 4. Running on Google Kubernetes Engine (GKE)
Google Kubernetes Engine (GKE) is a managed Kubernetes service that simplifies deploying, managing, and scaling containerized applications using Kubernetes.

For detailed setup and deployment instructions, refer to the [GKE README](.kubernetes/gke/README.md).
