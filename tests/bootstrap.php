<?php

require __DIR__ . '/../vendor/autoload.php';

// Create test database
$cmd = sprintf(
    'php "%s/../bin/adminconsole" doctrine:database:create --if-not-exists',
    __DIR__
);

passthru($cmd, $exitCode);

if ($exitCode) {
    exit($exitCode);
}

// Create or update test database schema
$cmd = sprintf(
    'php "%s/../bin/adminconsole" doctrine:schema:update --force',
    __DIR__
);

passthru($cmd, $exitCode);

if ($exitCode) {
    exit($exitCode);
}
