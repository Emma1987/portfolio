<?php

use App\Kernel;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return function (array $context) {
    if ($context['APP_MAINTENANCE'] ?? false) {
        $allowedIps = explode(',', $context['MAINTENANCE_ALLOWED_IPS'] ?? '');

        if (!in_array($_SERVER['REMOTE_ADDR'], $allowedIps)) {
            include __DIR__ . '/maintenance.html';
            exit;
        }
    }

    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
