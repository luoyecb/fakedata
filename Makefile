# 
.PHONY: default env test build clean

default: test

env:
	@echo === php version ===
	@php -v
	@echo

test: env
	phpunit --bootstrap vendor/autoload.php tests

build: clean
	@php scripts/build_phar.php -dir "./" -index fakedata.php -execMode -output fakedata.phar
	@echo Build successfully.

clean:
	rm -rf *.phar
