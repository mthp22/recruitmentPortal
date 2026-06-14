<?php

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Application;

$_GET['module'] = 'jobapplication';
$_GET['action'] = $_POST['action'] ?? $_GET['action'] ?? 'ajax';

(new Application())->run();
