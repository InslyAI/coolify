#!/bin/bash

# Script to push Docker images to GitHub Container Registry

echo "This script will push the Coolify/Insly Docker images to ghcr.io/kivilaid/coolify"
echo ""
echo "Prerequisites:"
echo "1. You need a GitHub Personal Access Token with 'write:packages' permission"
echo "2. Create one at: https://github.com/settings/tokens"
echo ""

read -p "Enter your GitHub username (default: kivilaid): " GITHUB_USER
GITHUB_USER=${GITHUB_USER:-kivilaid}

echo "Please enter your GitHub Personal Access Token:"
read -s GITHUB_TOKEN
echo ""

echo "Logging in to GitHub Container Registry..."
echo $GITHUB_TOKEN | docker login ghcr.io -u $GITHUB_USER --password-stdin

if [ $? -eq 0 ]; then
    echo "Successfully logged in!"
    
    echo "Pushing images..."
    docker push ghcr.io/kivilaid/coolify:4.0.0-beta.419
    docker push ghcr.io/kivilaid/coolify:latest
    
    echo ""
    echo "✅ Done! Images have been pushed:"
    echo "  - ghcr.io/kivilaid/coolify:4.0.0-beta.419"
    echo "  - ghcr.io/kivilaid/coolify:latest"
else
    echo "❌ Failed to login to GitHub Container Registry"
    exit 1
fi