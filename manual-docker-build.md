# Manual Docker Build and Push Instructions

## Prerequisites
- Docker must be installed and running
- You need a GitHub Personal Access Token with `write:packages` permission
  - Create one at: https://github.com/settings/tokens

## Build Commands

```bash
# 1. Build the Docker image
docker build -f docker/production/Dockerfile \
  -t ghcr.io/kivilaid/coolify:4.0.0-beta.419 \
  -t ghcr.io/kivilaid/coolify:latest \
  .

# 2. Login to GitHub Container Registry
# Use your GitHub username and Personal Access Token as password
docker login ghcr.io -u kivilaid

# 3. Push the images
docker push ghcr.io/kivilaid/coolify:4.0.0-beta.419
docker push ghcr.io/kivilaid/coolify:latest
```

## Alternative: Build for multiple architectures (AMD64 and ARM64)

```bash
# Create and use a buildx builder
docker buildx create --name multiarch --use

# Build and push for both architectures
docker buildx build \
  --platform linux/amd64,linux/arm64 \
  -f docker/production/Dockerfile \
  -t ghcr.io/kivilaid/coolify:4.0.0-beta.419 \
  -t ghcr.io/kivilaid/coolify:latest \
  --push \
  .
```

## Verify the push
After pushing, you can verify the images at:
- https://github.com/kivilaid/coolify/pkgs/container/coolify

The images will be available as:
- `ghcr.io/kivilaid/coolify:4.0.0-beta.419`
- `ghcr.io/kivilaid/coolify:latest`