<?php

/* ========================= Error reporting ======================== */
// Error reporting
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// Error log - PHP
ini_set("log_errors", 1);
ini_set("error_log", __DIR__.'/../logs/php-error.log.'.date('Y\-m\-d'));

/* ========================= Autoload and preparation ======================== */
require __DIR__ . '/../App/Autoload.php';

use App\App;
use App\Config;
use Helpers\Mailer;

App::registerAutoload();

/* ========================= Task ======================== */
// Define a few variables
$logs_dir = App::getRootDir().Config::getByName('App')['DEFAULT_LOGS_DIR'];
$to = date('Y-m-d H:i:s');
$from = date('Y-m-d H:i:s', strtotime('-36 hours', time()));

// Check if there are files created today and if yes, send email notification to the system administrator
$created_files = shell_exec('find '.$logs_dir.' -type f -newermt "'.$from.'" \! -newermt "'.$to.'"');
if ($created_files !== null) {
    $hostname = shell_exec('hostname');

    // Send email
    $parameters = ['{{HOSTNAME}}' => $hostname, '{{FROM_DATE}}' => $from, '{{TO_DATE}}' => $to, '{{LOGS}}' => $created_files];
    Mailer::send(Config::getByName('App')['DEFAULT_SYSTEM_ADMIN_EMAIL'], 'FrameworkJet System Administrator', 'email_error_logs', $parameters);
}

echo 'OK';