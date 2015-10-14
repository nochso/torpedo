<?php 

/* load lib/tests for when testing via phpunit */
spl_autoload_register(function($class) {
    if (strpos($class, 'Torpedo\\') === 0) {
        $dir = strcasecmp(substr($class, -4), 'Test') ? 'lib/' : 'test/lib/';
        $name = substr($class, strlen('Torpedo'));
        require __DIR__ . '/../' . $dir . strtr($name, '\\', DIRECTORY_SEPARATOR) . '.php';
    }
});