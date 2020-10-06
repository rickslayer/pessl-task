#!/bin/bash
echo "stoping containers"
sudo docker-compose down

echo "build if is necessary"
sudo docker-compose build

echo "up docker"
sudo docker-compose up -d

echo "See ips from containers"
sudo docker inspect -f '{{.Name}} - {{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' $(sudo docker ps -aq) 

echo "up php server local"
php -S localhost:8001 -t public 

