#!/usr/bin/env bash

# build the apache-php image using the Dockerfile in the current directory
IMAGE_NAME="derlin/apache-php"
echo "building $IMAGE_NAME..."
docker build -t "$IMAGE_NAME" .