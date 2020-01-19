<?php
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', true);
}

if (!defined('TEST_BASE_DIR')) {
    define('TEST_BASE_DIR', dirname(__DIR__));
}

if (!defined('PROJECT_BASE_DIR')) {
    define('PROJECT_BASE_DIR', dirname(__DIR__, 2));
}
