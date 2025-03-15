#!/bin/bash

cd $(dirname $0)
echo "Changing the current directory to $PWD"

if [[ ! -e phpunit.phar ]]; then
	wget -q https://phar.phpunit.de/phpunit-12.phar -O phpunit.phar
	chmod +x phpunit.phar
fi
