## Bootstrapping local clusters with kubeadm

### Set up DNS
Create DNS A type record to your host public IP
```shell
glib@mbp ~ % dig +short laravel.tableride.app
178.158.198.10
```

### Set up virtual machines
Create virtual machines
```shell
multipass launch --cpus 2 --disk 9G --memory 2G --name node01 \
  --network name=en0,mode=auto,mac="52:54:00:8a:77:89" && \
multipass launch --cpus 2 --disk 9G --memory 2G --name node02 \
  --network name=en0,mode=auto,mac="52:54:00:c6:b9:38" && \
multipass launch --cpus 2 --disk 9G --memory 2G --name node03 \
  --network name=en0,mode=auto,mac="52:54:00:d5:31:7a"
```
Change `route-metric` to 50 for `extra0` network interface on each VM:
```shell
multipass shell node01
sudo vim /etc/netplan/50-cloud-init.yaml
multipass restart --all
```
Verify routing
```shell
multipass exec node01 -- ip route | grep default
multipass exec node02 -- ip route | grep default
multipass exec node03 -- ip route | grep default
```
Create directory for local volumes
```shell
multipass exec node02 -- sudo mkdir --parents --verbose /mnt/kubernetes/pv-1
```

### Set up a cluster
- On each VM [install container runtime](https://kubernetes.io/docs/setup/production-environment/container-runtimes/)
  ```shell
  multipass shell node01
  ```
- On each VM [install kubeadm, kubelet and kubectl](https://kubernetes.io/docs/setup/production-environment/tools/kubeadm/install-kubeadm/#installing-kubeadm-kubelet-and-kubectl)
- Init a cluster [with kubeadm](https://kubernetes.io/docs/setup/production-environment/tools/kubeadm/create-cluster-kubeadm/) on node01
  ```shell
  sudo kubeadm init --pod-network-cidr 10.244.0.0/16
  ```
- Configure `.kube/config` to access the cluster with kubectl
- Install [a Pod network add-on](https://kubernetes.io/docs/concepts/cluster-administration/addons/#networking-and-network-policy)
- Join other nodes to the cluster
- Install [Ingress controller](https://kubernetes.github.io/ingress-nginx/deploy/#bare-metal-clusters)
  ```shell
  kubectl apply -f https://raw.githubusercontent.com/kubernetes/ingress-nginx/controller-v1.11.2/deploy/static/provider/baremetal/deploy.yaml
  ```
- Configure NAT port forwarding on your router
  ```shell
  kubectl --namespace ingress-nginx describe svc ingress-nginx-controller | grep NodePort
  ```
- Install [cert-manager](https://kubernetes.github.io/ingress-nginx/user-guide/tls/#automated-certificate-management-with-cert-manager)

## Deploy the Laravel application

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
