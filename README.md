### Running Locally

`docker-compose up`

### Accessing the Bash Console

`docker exec -it php bash`

### Running Tests in the Bash Console

`vendor/bin/phpunit` 

To run a specific test:
`vendor/bin/phpunit --filter CryptoProcessingServiceTest`

### Running Linters

`vendor/bin/php-cs-fixer fix`

`vendor/bin/phpstan analyse app --memory-limit=1G`


