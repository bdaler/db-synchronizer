# DBSynchronizer

### Описание
Консольная утилита, по команде умеет обновлять данные БД1 из БД2, удаляет дубликаты.

### Требования
* Docker
* docker-compose
* PHP 7.2+

### Установка
Для успешной установки:
* скачиваем данный репозиторий на сервер
* Если установка производится впервые то необходимо собрать контейнер 
```
docker-compose up --build
``` 
* установить зависимости

```bash
docker-compose exec fpm bash
composer install
```

Список команд:
* `service.php run:update --fromDB|fb=primary --toDB|tb=secondary`
 где `fromDB` база данных откуда берутся данные для обновления второй БД, принимает значения `primary` или `secondary`.
Соответственно `toDB` база данных которая обновится, так же принимает значения `primary` или `secondary`

* `service.php run:remove:existence`
Сравнивает данные в таблицах БД1 и БД2, удаляет найденные дубликаты в БД1.

### Тестирование
* Все тесты находятся в директории: `tests`
* Команда для запуска тестов: `php /var/www/vendor/phpunit/phpunit/phpunit --no-configuration /var/www/tests --teamcity`

### Логи
* Логи приложения хранятся в директории: `var/log/`
* Название лог файла: `db-synchronizer.Y-d-m.log`

