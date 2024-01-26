# Подключаем файл с переменными окружения
ifeq ($(wildcard .env),)
    $(shell cp .env.example .env)
endif
include .env

# Собираем образ
build-image:
	docker build -t $(IMAGE_NAME) ./build
	docker-compose up -d
	docker logs -f $(CONTAINER_NAME)

# Получает обновленный образ от регистри и запускает
pull-run:
	docker login $(DOCKERHUB_ADDR) -u $(DOCKERHUB_USER) -p $(DOCKERHUB_PASSWORD)
	docker-compose up -d
	docker logs -f $(CONTAINER_NAME)

# Запускает контейнер
start:
	docker-compose up -d
	docker logs -f $(CONTAINER_NAME)

show-log:
	docker logs -f $(CONTAINER_NAME)

restart-container:
	docker restart $(CONTAINER_NAME)

stop-container:
	docker stop $(CONTAINER_NAME)