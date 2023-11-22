Here are the bullet points for the Docker Compose instructions:

- When building with Docker Compose, you need separate Compose files for Mac and Linux due to the different mysql images for each architecture.

- Create `docker-compose.linux.yml` for Linux:

  ```yaml
  mysql:
    restart: unless-stopped 
    platform: linux/amd64
    image: "8.0.34-debian"
    ports:
     - "3307:3306"
  ```

- Build on Mac:

  ```
  docker-compose -f docker-compose.yaml build
  ```

- Build on Linux:

  ```
  docker-compose -f docker-compose.linux.yaml build
  ```

- This uses separate Docker Compose files to build the PostgreSQL service with the appropriate image for each architecture, while sharing the application service. The `-f` flag is used to specify the alternate Compose file on Linux.
