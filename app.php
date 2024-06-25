<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use App\App;

$args = $_SERVER['argv'];
if (count($args) < 2) {
    echo "Please provide a file path. \n";

    return;
}

$filePath = $args[1];
(new App())->run($filePath);