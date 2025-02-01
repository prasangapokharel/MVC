<?php

spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../';

    // Replace namespace separator with directory separator
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
