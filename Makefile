# Подключаем файл с переменными окружения
ifeq ($(wildcard .env),)
    $(shell cp .env.example .env)
endif
include .env

IMAGE_NAME = "bedrock-hub"

# Собираем образ
build-image:
	docker build -t $(IMAGE_NAME) ./build
	docker run -it -p 19132:19132/udp -v $(CURDIR)/data:/data -v $(CURDIR)/plugins:/plugins $(IMAGE_NAME):latest

# Запускаем сервер
run:
	docker login artent.fun -u $(DOCKERHUB_USER) -p $(DOCKERHUB_PASSWORD)
	docker-compose up -d