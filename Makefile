COLOR_RED = "\033[31m"
COLOR_GREEN = "\033[32m"
COLOR_YELLOW = "\033[33m"
COLOR_NO = "\033[0m"
STRING_OK = $(COLOR_GREEN)[OK]$(COLOR_NO)

#DOCKER COMMANDS
start:
	@echo $(COLOR_GREEN)Building docker containers$(COLOR_NO)
	@docker-compose up -d
	@echo $(COLOR_GREEN)Wait 10 seconds to run docker containers$(COLOR_NO)
	@sleep 10
	@echo $(STRING_OK)
start_build:
	@echo $(COLOR_GREEN)Building docker containers$(COLOR_NO)
	@docker-compose up -d --build
	@echo $(COLOR_GREEN)Wait 10 seconds to run docker containers$(COLOR_NO)
	@sleep 10
	@echo $(STRING_OK)
stop:
	@echo $(COLOR_GREEN)Stop docker containers$(COLOR_NO)
	@docker-compose stop
	@echo $(STRING_OK)

config_init:
	@echo $(COLOR_GREEN)Init configuration$(COLOR_NO)
	@cp .env.docker .env
	@echo $(STRING_OK)
composer_up:
	@echo $(COLOR_GREEN)Composer install$(COLOR_NO)
	@docker-compose run composer composer up
	@echo $(STRING_OK)
migrate:
	@echo $(COLOR_GREEN)DB migration$(COLOR_NO)
	@docker-compose exec app php artisan migrate
	@echo $(STRING_OK)
all: config_init \
	start_build \
	composer_up \
	migrate

