<?php

require_once __DIR__ . '/app/bootstrap.php';

use App\Core\Application;

$_GET['module'] = $_POST['module'] ?? $_GET['module'] ?? 'jobapplication';
$_GET['action'] = $_POST['action'] ?? $_GET['action'] ?? 'view';

(new Application())->run();
