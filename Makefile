
###########
# Install #
###########

install:
	composer update

########
# Lint #
########

lint: lint-php-cs-fixer lint-twig lint-composer

fix-php-cs-fixer:
	vendor/bin/php-cs-fixer fix

lint-php-cs-fixer:
	vendor/bin/php-cs-fixer fix --dry-run --diff

lint-composer:
	composer validate --strict

lint-twig:
	php bin/lint-twig.php Resources/views
