<?php

// story: e01s01

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Footer;

header('Content-Type: text/html; charset=UTF-8');
echo Footer::renderShowroom(__DIR__ . '/VERSION', __DIR__ . '/showroom.html');
