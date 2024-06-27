
## Use Docker

### 1. Build and Run Containers

Navigate to the root of your Symfony project where the Dockerfile and docker-compose.yml are located.

```bash
    docker-compose up --build
```

This command will build the Docker images and start the containers.
### 2. Access the Symfony Application

The Symfony application should be running on port 9000. Open your web browser and navigate to http://localhost:9000.
### 3. Running Commands Inside the Symfony Container

To execute commands inside the Symfony container (e.g., running Symfony Console commands), use the following command:

```bash
    docker-compose exec symfony bash
```

Once inside the container, you can run any necessary commands, like:

```bash
    php bin/console cache:clear
```
