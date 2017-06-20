#!/usr/bin/env bash

echo start Coding standard

vendor/bin/phpcs --standard=ruleset.xml --extensions=php --encoding=utf-8 --tab-width=4 -sp src