<?php
session_start();

// Error reporting
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// Error log - PHP
ini_set("log_errors", 1);
ini_set("error_log", __DIR__.'/../logs/php-error.log.'.date('Y\-m\-d'));

/**
 * Speed up.
 */
ob_start();

/**
 * FrameworkJet - A PHP Framework
 *
 * @package FrameworkJet
 * @author Pavel Tashev
 */
/*
 |----------------------------------------------------------------------
 | Load and Register the Auto Loader
 |----------------------------------------------------------------------
 |
 | The purpose of the auto loader is to load all classes required
 | for the work of the framework.
 |
 */
require __DIR__ . '/../App/Autoload.php';
use App\App;

App::registerAutoload();

/*
 |----------------------------------------------------------------------
 | Run the framework
 |----------------------------------------------------------------------
 |
 | Process the routing and run the corresponding controllers and methods.
 |
 */
App::run();

/*
 |----------------------------------------------------------------------
 | Render the response
 |----------------------------------------------------------------------
 */
\App\Response::render();


/**
 * Speed up clause.
 */
ob_end_flush();