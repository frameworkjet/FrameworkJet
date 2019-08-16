<?php

/**
 * The purpose of this helper is to send transactional emails.
 */

namespace Helpers;

use App\App;
use App\Config;

class Mailer
{
    private static $instance = null;
    private static $config = null;

    private function __construct() {}
    private function __clone() {}

    /**
     * @desc Get PHPMailer instance
     * @return null|array
     */
    private static function getInstance()
    {
        if (self::$instance === null) {
            $config = Config::getByName('Services');
            self::$config = $config = $config['mail'];

            // Get instance and configure
            self::$instance = new \PHPMailer;
            self::$instance->isSMTP(); // Set mailer to use SMTP
            self::$instance->Host = $config['host']; // Specify main and backup SMTP servers
            self::$instance->SMTPAuth = $config['smtp_auth']; // Enable SMTP authentication
            self::$instance->Username = $config['username']; // SMTP username
            self::$instance->Password = $config['password']; // SMTP password
            self::$instance->SMTPSecure = $config['smtp_secure']; // Enable TLS encryption, `ssl` also accepted
            self::$instance->Port = $config['port']; // TCP port to connect to
            self::$instance->CharSet = 'UTF-8';
            self::$instance->setFrom($config['username'], $config['from']);
        }

        return self::$instance;
    }

    /**
     * @desc Send an email.
     * @param string $to_email
     * @param string $to_name
     * @param string $content_key
     * @param array $parameters
     * @param boolean|string $lang
     * @return boolean
     */
    public static function send($to_email, $to_name, $content_key, $parameters = array(), $lang = false)
    {
        // Set language
        if (!$lang) {
            $lang = App::config('DEFAULT_LANGUAGE');
        }

        // Use the language, the parameters and the content key to prepare the subject and the body of the email. 
		// You can connect to the database to extract your email templates or you can store them in the file system.
        $subject = 'SUBJECT_OF_THE_EMAIL';
        $body = 'BODY_OF_THE_EMAIL';

        // Prepare the email
        $mail = self::getInstance();
        $mail->clearAddresses();
        $mail->addAddress($to_email, $to_name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
	$mail->AltBody = strip_tags($body);
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		));

        if (!$mail->send()) {
            Log::alert('mailer', 'The email messages has not been sent. To: '.$to_email.', Subject: '.$subject);

            return false;
        }

        return true;
    }
}
