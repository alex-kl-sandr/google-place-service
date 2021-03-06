# Google Place Service

**Backend:** PHP 7, Redis и MySQL/PostgresSQL, Laravel 5.7

**Frontend:** Bootstrap 4, Vue 2.5

### Установка
- Скачать данный проект и установить на сервер
- Настроить конфигурацию web сервера для корректной работы по http(s)
- Настроить все необходимые подключения к базам данных(Redis и MySQL/PostgresSQL) и указать GOOGLE_PLACE_API_KEY в файле **.env**
- В консоле выполнить команду: **php artisan migrate --force**

### Краткое описание работы срвиса
<p>При наборе 3-х и более символов в текстовом поле "Location" происходит AJAX запрос на backend для получения местоположений соответствующих введенному набору символов, где в качестве основного источника данных(Locations) используется Google Places Service.</p> 
<p>После каждого успешного запроса через API от Google Places Service происходит логирование данного запроса(query и response from api) в noSQL Redis с целью последующего использования при аналогичных запросах от Frontend'a.</p> 
<p>После заполнения текстового поле "Location" и нажатии кнопки "Submit" происходит AJAX запрос на запись введенных данных в SQL db (MySQL/PostgreSQL).</p> 
<p>О результатах основных действий пользователя или системы сообщается через соответствующие сообщения.</p>

Frontend собирался с помощью Webpack Mix от Laravel.

### Коментарии по данному сервису
Так как этот сервис реализован на Laravel, у него есть возможность быстро переключаться в разные режимы разработки и выкатываться в продакшен.

<p>Что касается масштабирования сервиса, то тут уже реализовано разделение его работы на разные базы дынных(подсказки используют Redis а запись locations происходит в MySQL/PostgreSQL) и это значительно разгрузит общую нагрузку на сервис.</p>
<p>Еще можно попробовать реализовать master-slave репликацию базы или шардинг таблицы с подсказками, но это нужно решать по каждому случаю отдельно.</p> 
<p>Провести все необходимые тесты и после принимать решение, каким из вариантов горизонтального масштабирования воспользоваться, чтобы получить наибольший эффект.</p>

<a href="http://alex.idartstyle.ru/">Пример реализации</a>

В данном примере используются ограниченные условия использования API от Google Place, поэтому проводить какие-то тесты по нагрузкам данного сервиса нет смысла.
Данный сервис реализовывался с демонстрационной целью.
Для реального случая можно было бы проанализировать работу и лимиты от других похожих сервисов, либо выбирать платный план, который бы удовлетворял всем запросам.