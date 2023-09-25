# ToDo List
> Improving tasks performance app

## Local Setup
```
docker compose up -d
```
```
docker compose exec php composer install
```
```
docker compose exec php bin/console doctrine:migrations:migrate
```
```
http://todolist.local:3000/
```
## Backend Test
```
docker compose exec php /var/www/ToDoList/Backend/vendor/bin/phpunit
```
## Frontend Test
```
docker compose exec frontend /bin/bash
```
```
npm test
```

## Status
> In progress

## Contact
* [GitHub](https://github.com/JakubSzczerba)
* [LinkedIn](https://www.linkedin.com/in/jakub-szczerba-3492751b4/)