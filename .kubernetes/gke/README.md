## Overview
This guide provides instructions on deploying the Laravel application on Google Kubernetes Engine (GKE).

## Prerequisites
Before you begin, ensure you have the following:
- A [Google Cloud](https://console.cloud.google.com/) account
- `gcloud` CLI [installed](https://cloud.google.com/sdk/docs/install) and [authenticated](https://cloud.google.com/sdk/docs/initializing)
- A Kubernetes cluster created on GKE
 
GKE has the [Autopilot](https://cloud.google.com/kubernetes-engine/docs/how-to/creating-an-autopilot-cluster) and [Standard](https://cloud.google.com/kubernetes-engine/docs/how-to/creating-a-zonal-cluster) modes of operation, which offer you different levels of flexibility, responsibility, and control.

### Set up DNS
Reserve a static external IP address named `kubernetes-ingress-ip`:
```shell
gcloud compute addresses create kubernetes-ingress-ip --global
```

Create DNS A type record to `kubernetes-ingress-ip`:
```shell
gcloud compute addresses list
```
```shell
dig laravel.gke.any-key.dev +short
```

### Certificate Manager API
Make sure the Certificate Manager API is enabled for your project:
```shell
gcloud services list --enabled | grep certificatemanager
```

## Deploy the Laravel application
Build the Docker image for the Laravel application:
```shell
docker buildx build \
  --platform=linux/amd64,linux/arm64 \
  --file=.docker/fpm/Dockerfile \
  --tag=glebsvitelskiy/laravel:fpm-1 \
  --push \
  .
```

Apply manifests:
```shell
kubectl apply --kustomize=.kubernetes/gke
```

List all resources:
```shell
kubectl get all,cm,pvc,pv,ingress,managedcertificates.networking.gke.io
```

Run DB migrations (wait for the database service to start):
```shell
kubectl exec \
  $(kubectl get po --selector=app=laravel --output=jsonpath='{.items[0].metadata.name}') \
  --container=fpm \
  -- php artisan migrate --force
```

Now Laravel application will be accessible in your web browser at https://laravel.gke.any-key.dev

Delete all resources:
```shell
kubectl delete --kustomize=.kubernetes/gke
```

Diff
```shell
kubectl diff --kustomize=.kubernetes/gke
```
