<?php
spl_autoload_register(function ($class) {
    $class = mb_eregi_replace("[\\\\]", "/", $class);
    require BASE_PATH."/".$class.'.php';
});

define('BASE_PATH', __DIR__.'/..');
define('ROOT_PATH', __DIR__.'/..');

use Classes\Routing\SimpleRoute;
use Classes\Core\AppCore;

$simple_route = new SimpleRoute();

$core = new AppCore($simple_route);
$core->start();