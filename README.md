# Fruit and Vegetables service
service that seed some fruits and vegetables from json exist in file and then allow system users to list/filter/add/delete these data

## Requirement

1. [symfony](https://symfony.com/doc/current/index.html)
2. [PHP >= 8.0](http://php.net/downloads.php)
3. [Composer](https://getcomposer.org/)

## Important Environment variables (dev)

| Name | Type | Default | Description |
|------|------|---------|-------------|
| `DATABASE_URL` | `string` | `mysql://root:root@127.0.0.1:3306/fruits_and_vegetables` | DB url to allow connection to DB |

## Installation
1. Clone the repo via this url
  ```
    git clone https://github.com/abeer93/fruits-and-vegetables.git
  ```
2. Enter inside the folder
  ```
    cd fruits-and-vegetables
  ```
3. Install various packages and dependencies:
  ```
    composer install
  ```
4. Run migartions (ensure that uou updated important env variables before this step)
  ```
    php bin/console doctrine:migrations:migrate
  ```
5. Seed the database from request.json file
  ```
    php bin/console doctrine:fixtures:load
  ```
6. Run test cases
  ```
    php bin/phpunit
  ```
7. Run Servers
  ```
    symfony server:start
  ```

## Test The APP
Once the server is running, you can access the project at http://localhost:8000.


## API Endpoints

| HTTP Method | Endpoint      | Description                             |             CURL example                             |
|-------------|---------------|-----------------------------------------|------------------------------------------------------|
| `GET`       | `/api/fruits` | Fetch a list of fruits with some filters| curl --location 'http://127.0.0.1:8000/api/vegetables?name=rot&minQuantity=50000&maxQuantity=65000' \--header 'Accept: application/json'|
| `POST`      | `/api/fruits` | Create a new fruit                      | curl --location 'http://127.0.0.1:8000/api/fruits' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
    "name": "test_apple",
    "quantity": 300,
    "unit": "g"
}'|
| `DELETE`    | `/api/fruits/{id}` | Delete a fruit by ID | curl --location --request DELETE 'http://127.0.0.1:8000/api/fruits/13' \
--header 'Accept: application/json' \
--data ''|

| `GET`       | `/api/vegetables` | Fetch a list of vegetables with some filters| curl --location 'http://127.0.0.1:8000/api/vegetables?name=rot&minQuantity=50000&maxQuantity=65000' \--header 'Accept: application/json'|
| `POST`      | `/api/vegetables` | Create a new fruit                      | curl --location 'http://127.0.0.1:8000/api/vegetables' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
    "name": "test_apple",
    "quantity": 300,
    "unit": "g"
}'|
| `DELETE`    | `/api/vegetables/{id}` | Delete a fruit by ID | curl --location --request DELETE 'http://127.0.0.1:8000/api/vegetables/13' \
--header 'Accept: application/json' \
