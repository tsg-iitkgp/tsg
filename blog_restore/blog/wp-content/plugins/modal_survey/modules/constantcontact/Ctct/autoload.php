<?php
require_once(sprintf("%s/SplClassLoader.php", dirname(__FILE__)));

// Load the Ctct namespace
$loader = new \Ctct\SplClassLoader('Ctct', dirname(__DIR__));
$loader->register();
