ROOT_DIR:=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))
build:
	docker-compose build
style:
	./vendor/bin/phpinsights --no-interaction
up: build
	docker-compose up -d
	docker-compose exec -T backend sh -c "./init --env=Docker --overwrite=y"
	docker-compose exec -T backend sh -c "composer install --no-interaction --prefer-dist -o"
	docker-compose exec -T backend sh -c "cat /app/.env.template | envsubst > /app/.env"
	docker-compose exec -T backend sh -c "./yii migrate"
sh: up
	docker-compose exec queue sh -c "/bin/bash"
mysql: up
	docker-compose exec mysql mysql -u infotek -pinfotek infotek
down:
	docker-compose down --remove-orphans
ln:
	mkdir -p $(ROOT_DIR)/common/uploads
	chmod 777 -R $(ROOT_DIR)/common/uploads
	rm -f $(ROOT_DIR)/backend/web/uploads
	ln -fs $(ROOT_DIR)/common/uploads $(ROOT_DIR)/backend/web/uploads
	chmod 777 $(ROOT_DIR)/backend/web/uploads