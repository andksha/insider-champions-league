#!make

start:
	docker-compose up -d

#start web only
swo:
	docker-compose up -d insider_nginx insider_phpfpm

stop:
	docker-compose stop

queue_work:
	docker-compose exec $(d) insider_phpfpm php artisan queue:work

queue_restart:
	docker-compose exec $(d) insider_phpfpm php artisan queue:restart

init:
	docker-compose exec insider_phpfpm composer install
	docker-compose exec insider_phpfpm php artisan key:generate
	docker-compose exec insider_phpfpm php artisan migrate --seed
	docker-compose exec insider_phpfpm usermod -aG sudo www-data
	docker-compose exec insider_phpfpm chown -R www-data:www-data storage bootstrap/cache
	docker-compose exec insider_phpfpm chgrp -R www-data storage bootstrap/cache
	docker-compose exec insider_phpfpm chmod -R 755 storage/logs

permissions:
    docker-compose exec insider_phpfpm php artisan route:clear
    docker-compose exec insider_phpfpm php artisan config:clear
    docker-compose exec insider_phpfpm php artisan cache:clear
	docker-compose exec insider_phpfpm usermod -aG sudo www-data
	docker-compose exec insider_phpfpm chown -R www-data:www-data storage bootstrap/cache
	docker-compose exec insider_phpfpm chgrp -R www-data storage bootstrap/cache
	docker-compose exec insider_phpfpm chmod -R 755 storage/logs

refresh:
	docker-compose exec insider_phpfpm chmod -R 755 /application1/logs/

update:
	docker-compose exec insider_phpfpm chmod -R 755 /application1/logs/

supervisor_start:
	docker-compose exec insider_queue service supervisor stop
	docker-compose exec insider_queue service supervisor start
	docker-compose exec insider_queue supervisorctl reread
	docker-compose exec insider_queue supervisorctl update
	docker-compose exec insider_queue supervisorctl start laravel-worker:*