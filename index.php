<?php

// story: e01s01

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Footer;

echo Footer::render(__DIR__ . '/VERSION');
