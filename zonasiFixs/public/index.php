<?php
session_start();

// Load configuration
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// Load helpers
require_once ROOT_PATH . 'app/Helpers/functions.php';
require_once ROOT_PATH . 'app/Helpers/Router.php';

// Initialize router
$router = new Router();

// Load routes
require_once ROOT_PATH . 'routes/web.php';

// Dispatch the request
$router->dispatch();
