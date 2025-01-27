<?php
// app/bootstrap.php

// Load Composer's autoloader (handles namespaces)
require __DIR__ . '/../vendor/autoload.php';

// Load configuration (if needed)
$config = require __DIR__ . '/../app/Config/database.php'; // Capital "C" in Config
// Add other setup code here