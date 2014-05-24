#!/bin/sh

composer install --no-dev --optimize-autoloader
rm -R app/cache/*
