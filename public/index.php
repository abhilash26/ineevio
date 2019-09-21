<?php
// Initialize basic bootstrap functions

use core\database\Database;

require_once '../app/bootstrap.php';
// Register and dispatch routes
require_once BASE_PATH. '/routes/routes.php';

// throw new \Exception("Hello");

$database = new Database();
