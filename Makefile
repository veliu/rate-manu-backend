ON_CONTAINER := docker compose exec php

.PHONY: deptrac
deptrac:
	XDEBUG_MODE=off $(ON_CONTAINER) vendor/bin/deptrac

.PHONY: cs-fix
cs-fix:
	XDEBUG_MODE=off PHP_CS_FIXER_FUTURE_MODE=1 PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --allow-risky=yes --verbose

.PHONY: analyse
psalm:
	XDEBUG_MODE=off vendor/bin/psalm