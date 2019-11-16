<?php
// Require Composer Auto-loader.
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', true);
}

if (!defined('HOOKS_TEST_BASE_DIR')) {
    define('HOOKS_TEST_BASE_DIR', dirname(__DIR__));
}

if (!defined('HOOKS_PROJECT_BASE_DIR')) {
    define('HOOKS_PROJECT_BASE_DIR', dirname(__DIR__, 2));
}
