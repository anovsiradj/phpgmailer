<?php

require __DIR__ . '/../php_packages/autoload.php';

(new Symfony\Component\Dotenv\Dotenv)->usePutenv(true)->load(__DIR__ . '/../.env');
