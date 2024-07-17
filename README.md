## How to use
1. Make sure to have Docker installed in your system.
2. Run `symfony check:req` to ensure that your system meets all requirements needed by Symfony. If you do not have `symfony-cli` installed, be sure to [install it](https://symfony.com/download).
3. Copy `.env` to `.env.local` and modify the environment variables to suite your requirements.
4. Run `composer install` to install all dependencies.
5. Run `docker compose up -d` to setup docker
6. Create the database using `symfony console doctrine:schema:create` command
7. Load sample data using  `symfony console doctrine:fixtures:load` command
8. You may run tests using `bin/phpunit`
9. Run the application using `symfony serve`

## API Endpoints

- `POST /api/api/login_check` - Login
- `GET /api/products` - List all products.
- `POST /api/products` - Create a new product.
- `GET /api/products/{id}` - Get details of a single product.
- `PUT /api/products/{id}` - Update an existing product.
- `DELETE /api/products/{id}` - Delete a product.

All requests must be sent with `application/json` Content-Type.

## Examples
### Login
```bash
### Request

curl -X POST -k -H 'Content-Type: application/json' -i 'https://[::1]:8000/api/login_check' --data '{"username": "user@example.com", "password": "secret"}'

### Response
{"token":"eyJ0eXAiOiJKV1Qi....lQIXg"}
```
### Create a product
```bash
### Valid Request
curl -X POST -k -H 'Content-Type: application/json' -H 'Authorization: Bearer eyJ0eXAiOiJKV1Q....SOCPM0GL6Q' -i 'https://[::1]:8000/api/products' --data '{"name": "Sample", "price": 10.2, "sku": "123-ABC"}'

### Success
{"success":true,"id":22}

### Invalid Request

curl -X POST -k -H 'Content-Type: application/json' -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJh....lQIXg' -i 'https://[::1]:8000/api/products' --data '{"name": "", "price": null, "sku": ""}'

### Response
{"success":false,"errors":{"name":["This value should not be blank."],"price":["This value should not be blank."],"sku":["This value should not be blank."]}}
```

### Show Product
```bash
### Valid Request
curl -X GET -k -H 'Content-Type: application/json' -H 'Authorization: Bearer eyJ0eXAiOiJKV1Q....SOCPM0GL6Q' -i 'https://[::1]:8000/api/products/2' --data '{"name": "Sample", "price": 10.2, "sku": "123-ABC"}'

### Response
{
  "id": 2,
  "name": "product 1",
  "price": "90.00",
  "sku": "PT-1",
  "created_at": "2024-06-27T14:32:05+00:00",
  "updated_at": null
}

### Invalid Request
curl -X GET -k -H 'Content-Type: application/json' -H 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJ....XphSRdSOCPM0GL6Q' -i 'https://[::1]:8000/api/products/21' --data '{"name": "Sample", "price": 10.2, "sku": "123-ABC"}'

### Response
{
  "error": "Product not found"
}
```
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

## Further Improvements
1. Increase the test coverage
2. Optimize the docker file(s)
3. Paginate the `/api/products` endpoint