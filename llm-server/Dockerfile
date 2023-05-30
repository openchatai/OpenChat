# Use an official Node.js runtime as the base image
FROM node:18

# Set the working directory
WORKDIR /usr/src/app

# Copy package.json and yarn.lock
COPY package*.json yarn.lock ./

COPY .env .env

# Install dependencies
RUN yarn install

# Copy the project files to the container
COPY . .

# Expose the container's port (if required by the app)

# Start the app
CMD [ "npm", "run", "dev" ]





