ON_CONTAINER := docker compose exec php

.PHONY: deptrac
deptrac:
	XDEBUG_MODE=off $(ON_CONTAINER) vendor/bin/deptrac

.PHONY: deptrac-baseline
deptrac-baseline:
	XDEBUG_MODE=off $(ON_CONTAINER) vendor/bin/deptrac --formatter=baseline

.PHONY: cs-fix
cs-fix:
	XDEBUG_MODE=off PHP_CS_FIXER_FUTURE_MODE=1 PHP_CS_FIXER_IGNORE_ENV=1 $(ON_CONTAINER) vendor/bin/php-cs-fixer fix --allow-risky=yes --verbose

.PHONY: phpstan
phpstan:
	XDEBUG_MODE=off $(ON_CONTAINER) vendor/bin/phpstan analyse

.PHONY: phpstan-baseline
phpstan-baseline:
	XDEBUG_MODE=off $(ON_CONTAINER) vendor/bin/phpstan analyse --generate-baseline

.PHONY: tests
tests:
	XDEBUG_MODE=off $(ON_CONTAINER) vendor/bin/phpunit -d --testdox

.PHONY: qa
qa: cs-fix deptrac phpstan

database-test-recreate:
	XDEBUG_MODE=off $(ON_CONTAINER) bin/console doctrine:database:drop -f --if-exists --env=test
	XDEBUG_MODE=off $(ON_CONTAINER) bin/console doctrine:database:create --env=test
	XDEBUG_MODE=off $(ON_CONTAINER) bin/console doctrine:schema:create --env=test

database-recreate:
	XDEBUG_MODE=off $(ON_CONTAINER) bin/console doctrine:database:drop -f --if-exists
	XDEBUG_MODE=off $(ON_CONTAINER) bin/console doctrine:database:create

database-diff-migrate:
	XDEBUG_MODE=off $(ON_CONTAINER) bin/console doctrine:migration:diff
	XDEBUG_MODE=off $(ON_CONTAINER) bin/console doctrine:migration:migrate -n

schema-rebuild: database-recreate database-diff-migrate
