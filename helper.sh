#!/usr/bin/env bash

set -eu
#todo сделать доступ в sql
function reset_database {
    run_php bin/console doctrine:database:drop --force
    run_php bin/console doctrine:database:create
    run_php bin/console doctrine:migrations:migrate --no-interaction
    run_php bin/console doctrine:fixtures:load --no-interaction
}

function run_phpunit {
    run_php bin/phpunit $@
}

function run_php {
    docker exec book-service_app $@
}

function create_network {
    if [[ "$(docker network ls | grep books_network)" == "" ]] ; then
      docker network create books_network
    fi
}

function build_app {
    docker_compose up --build
}

function docker_compose {
    sudo -E docker-compose $@
}

command_full=$@
command_name="$1"
command_args=${command_full#"$command_name"}

case ${command_name} in
    build)
        create_network
        build_app
        reset_database
        ;;
    phpunit)
        run_phpunit ${command_args}
        ;;
    database:reset)
        reset_database
        ;;
    *)
        echo "${command_name} is not supported"
        exit -1
        ;;
esac