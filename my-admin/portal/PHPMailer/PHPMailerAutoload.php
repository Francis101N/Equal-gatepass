<?php
/**
 * PHPMailer SPL autoloader.
 * Updated to remove deprecated __autoload() for PHP 7+ compatibility.
 */

function PHPMailerAutoload($classname)
{
    // Convert class names like 'PHPMailer' to 'class.phpmailer.php'
    $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'class.' . strtolower($classname) . '.php';
    if (is_readable($filename)) {
        require $filename;
    }
}

// Use only SPL autoloader — __autoload() is removed in PHP 7+
if (version_compare(PHP_VERSION, '5.1.2', '>=')) {
    if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
        spl_autoload_register('PHPMailerAutoload', true, true);
    } else {
        spl_autoload_register('PHPMailerAutoload');
    }
}