SHELL := /usr/bin/env bash
PROJECT_ROOT := $(dir $(lastword $(MAKEFILE_LIST)))

bash:
	export UID && docker-compose -f docker-compose-mssql.yml exec php7 bash

up:
	export UID && docker-compose -f docker-compose-mssql.yml up -d


down:
	docker-compose -f docker-compose-mssql.yml down -v

#docker_clean_dangling_images_and_volumes:
docker_clean:
	docker rmi $(docker images --filter "dangling=true" -q --no-trunc)
#docker volume rm $(docker volume ls -qf dangling=true)

