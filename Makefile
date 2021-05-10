# 
.PHONY: default env test build clean install

default: test

env:
	@echo === php version ===
	@php -v
	@echo
	@echo === phpunit version ===
	@phpunit --version

test: env
	phpunit --bootstrap vendor/autoload.php tests

build:
	@php scripts/build_phar.php
	@echo Build successfully.

install: build
	@sh scripts/install.sh
	@echo Install successfully.

clean:
	rm -rf *.phar
