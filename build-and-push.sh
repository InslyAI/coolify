#!/bin/bash

# Build and push Coolify/Insly Docker image to ghcr.io/kivilaid/coolify

VERSION="4.0.0-beta.419"
IMAGE_NAME="ghcr.io/kivilaid/coolify"

echo "Building Docker image..."
docker build -f docker/production/Dockerfile \
  -t ${IMAGE_NAME}:${VERSION} \
  -t ${IMAGE_NAME}:latest \
  .

echo "Logging in to GitHub Container Registry..."
echo "Please make sure you have a GitHub Personal Access Token with 'write:packages' permission"
echo "You can create one at: https://github.com/settings/tokens"
docker login ghcr.io -u kivilaid

echo "Pushing images..."
docker push ${IMAGE_NAME}:${VERSION}
docker push ${IMAGE_NAME}:latest

echo "Done! Images pushed:"
echo "- ${IMAGE_NAME}:${VERSION}"
echo "- ${IMAGE_NAME}:latest"