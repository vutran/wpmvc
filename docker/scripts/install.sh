#!/bin/bash

# switch to theme directory
cd /var/www/html/wp-content/themes/wpmvc/

# validate composer.json
composer validate

# install packages
composer install
