rwildcard=$(wildcard $1$2) $(foreach d,$(wildcard $1*),$(call rwildcard,$d/,$2))

COMPOSER		:= `which composer`
PHP			:= `which php`

PHPDIR			:= "src"
PHPFILES		:= $(call rwildcard,$(PHPDIR)/,*.php)
PHP_TARGETS		:= $(patsubst %,check-php-syntax_%,$(PHPFILES))

.PHONY: check
check:	setup-toolchain check-php-syntax
	bin/behat --stop-on-failure

.PHONY: check-php-syntax
check-php-syntax: $(PHP_TARGETS)

check-php-syntax_./%:
	$(PHP) --syntax-check $(*)

.PHONY: setup-toolchain
setup-toolchain:
	$(COMPOSER) install --no-interaction

.PHONY: run
run:
	$(PHP) $(PHPDIR)/challenge.php

.PHONY: clean
clean:
