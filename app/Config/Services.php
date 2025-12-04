<?php

namespace Config;

use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    public static function email($config = null, $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('email', $config);
        }

        $config = $config ?? config('Email');
        $email = new \CodeIgniter\Email\Email($config);
        
        // Use PHPMailer if available
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $email->setMailer('phpmailer');
        }
        
        return $email;
    }
    
    public static function spreadsheet($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('spreadsheet');
        }
        
        return new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    }
    
    public static function pdf($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('pdf', $orientation, $unit, $format, $unicode, $encoding);
        }
        
        return new \TCPDF($orientation, $unit, $format, $unicode, $encoding);
    }
    
    public static function dompdf($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('dompdf');
        }
        
        return new \Dompdf\Dompdf();
    }
    
    public static function image($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('image');
        }
        
        return new \Intervention\Image\ImageManager([
            'driver' => 'gd' // or 'imagick'
        ]);
    }
    
    public static function midtrans($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('midtrans');
        }
        
        $config = config('Midtrans');
        \Midtrans\Config::$serverKey = $config->serverKey;
        \Midtrans\Config::$clientKey = $config->clientKey;
        \Midtrans\Config::$isProduction = $config->isProduction;
        \Midtrans\Config::$isSanitized = $config->isSanitized;
        \Midtrans\Config::$is3ds = $config->is3ds;
        
        return new \Midtrans\Snap();
    }
    
    public static function stripe($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('stripe');
        }
        
        $config = config('Stripe');
        \Stripe\Stripe::setApiKey($config->secretKey);
        \Stripe\Stripe::setApiVersion($config->apiVersion);
        
        return new \Stripe\StripeClient($config->secretKey);
    }
}