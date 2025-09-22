#!/bin/bash

wait-for-it.sh mysql:3306 -t 300
./vendor/bin/phpunit --debug
