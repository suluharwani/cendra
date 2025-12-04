<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * -------------------------------------------------------------------
 * AUTOLOADER CONFIGURATION
 * -------------------------------------------------------------------
 *
 * This file defines the namespaces and class maps so the Autoloader
 * can find the files as needed.
 *
 * NOTE: If you use an identical key in $psr4 or $classmap, then
 *       the values in this file will overwrite the framework's values.
 *
 * NOTE: This class is required prior to Autoloader instantiation,
 *       and does not extend BaseConfig.
 */
class Autoload extends AutoloadConfig
{
    /**
     * -------------------------------------------------------------------
     * Namespaces
     * -------------------------------------------------------------------
     * This maps the locations of any namespaces in your application to
     * their location on the file system. These are used by the autoloader
     * to locate files the first time they have been instantiated.
     *
     * The 'Config' (APPPATH . 'Config') and 'CodeIgniter' (SYSTEMPATH) are
     * already mapped for you.
     *
     * You may change the name of the 'App' namespace if you wish,
     * but this should be done prior to creating any namespaced classes,
     * else you will need to modify all of those classes for this to work.
     *
     * @var array<string, list<string>|string>
     */
 public $psr4 = [
        APP_NAMESPACE => APPPATH, // For custom app namespace
        'Config'      => APPPATH . 'Config',
        'PhpOffice\\PhpSpreadsheet' => APPPATH . '../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet',
        'TCPDF'       => APPPATH . '../vendor/tecnickcom/tcpdf',
        'Carbon'      => APPPATH . '../vendor/nesbot/carbon/src/Carbon',
        'Intervention\\Image' => APPPATH . '../vendor/intervention/image/src/Intervention/Image',
        'Dompdf'      => APPPATH . '../vendor/dompdf/dompdf/src',
        'Midtrans'    => APPPATH . '../vendor/midtrans/midtrans-php',
        'PHPMailer\\PHPMailer' => APPPATH . '../vendor/phpmailer/phpmailer/src',
        'Ramsey\\Uuid' => APPPATH . '../vendor/ramsey/uuid/src',
        'Stripe'      => APPPATH . '../vendor/stripe/stripe-php/lib',
        'Mike42\\Escpos' => APPPATH . '../vendor/mike42/escpos-php/src/Mike42',
    ];

    public $classmap = [
        'Faker\Factory' => APPPATH . '../vendor/fakerphp/faker/src/Factory.php',
    ];
    /**
     * -------------------------------------------------------------------
     * Files
     * -------------------------------------------------------------------
     * The files array provides a list of paths to __non-class__ files
     * that will be autoloaded. This can be useful for bootstrap operations
     * or for loading functions.
     *
     * Prototype:
     *   $files = [
     *       '/path/to/my/file.php',
     *   ];
     *
     * @var list<string>
     */
    public $files = [];

    /**
     * -------------------------------------------------------------------
     * Helpers
     * -------------------------------------------------------------------
     * Prototype:
     *   $helpers = [
     *       'form',
     *   ];
     *
     * @var list<string>
     */
    public $helpers = [];
}
