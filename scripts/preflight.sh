#!/usr/bin/env bash
set -euo pipefail
if [ ! -d vendor ]; then
  composer install --no-progress
fi
composer lint
composer test
