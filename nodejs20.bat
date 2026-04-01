# Docker has specific installation instructions for each operating system.
# Please refer to the official documentation at https://docker.com/get-started/

# Pull the Node.js Docker image:
docker pull node:20-alpine

# Create a Node.js container and start a Shell session:
docker run -it --rm --entrypoint sh node:20-alpine

# Verify the Node.js version:
node -v # Should print "v20.20.2".

# Verify npm version:
npm -v # Should print "10.8.2".
