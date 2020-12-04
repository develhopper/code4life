<?php

use Core\handler\Request;
use Core\Kernel;

require_once '../bootstrap.php';

$kernel = new Kernel();

$response = $kernel->handle(new Request());

$response->send();
