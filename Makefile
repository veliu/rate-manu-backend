ON_CONTAINER := docker compose exec php

.PHONY: deptrac
deptrac:
	XDEBUG_MODE=off $(ON_CONTAINER) vendor/bin/deptrac

.PHONY: cs-fix
cs-fix:
	XDEBUG_MODE=off PHP_CS_FIXER_FUTURE_MODE=1 PHP_CS_FIXER_IGNORE_ENV=1 $(ON_CONTAINER) vendor/bin/php-cs-fixer fix --allow-risky=yes --verbose

.PHONY: phpstan
phpstan:
	XDEBUG_MODE=off $(ON_CONTAINER) vendor/bin/phpstan analyse

.PHONY: tests
tests:
	XDEBUG_MODE=off $(ON_CONTAINER) vendor/bin/phpunit -d --testdox

.PHONY: qa
qa: cs-fix deptrac phpstan

