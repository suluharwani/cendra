<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    public $custom = [
        'phone' => [
            'rules'  => 'regex_match[/^(\+62|62|0)8[1-9][0-9]{6,9}$/]',
            'errors' => [
                'regex_match' => 'Format nomor telepon tidak valid. Gunakan format Indonesia.'
            ]
        ],
        'tax_id' => [
            'rules'  => 'regex_match[/^[0-9]{15}$/]',
            'errors' => [
                'regex_match' => 'NPWP harus 15 digit angka.'
            ]
        ],
        'project_code' => [
            'rules'  => 'regex_match[/^[A-Z]{3}-[0-9]{6}$/]',
            'errors' => [
                'regex_match' => 'Format kode proyek: TIGA HURUF-enam angka (contoh: PRJ-202301).'
            ]
        ],
        'subscription_code' => [
            'rules'  => 'regex_match[/^SUB-[A-Z0-9]{8}$/]',
            'errors' => [
                'regex_match' => 'Format kode subscription: SUB-8 karakter alfanumerik.'
            ]
        ],
        'quotation_number' => [
            'rules'  => 'regex_match[/^QUO-[0-9]{4}-[0-9]{2}-[0-9]{4}$/]',
            'errors' => [
                'regex_match' => 'Format nomor quotation: QUO-tahun-bulan-nomor.'
            ]
        ],
        'invoice_number' => [
            'rules'  => 'regex_match[/^INV-[0-9]{4}-[0-9]{2}-[0-9]{4}$/]',
            'errors' => [
                'regex_match' => 'Format nomor invoice: INV-tahun-bulan-nomor.'
            ]
        ],
        'positive_number' => [
            'rules'  => 'greater_than[0]',
            'errors' => [
                'greater_than' => 'Harus lebih besar dari 0.'
            ]
        ],
        'future_date' => [
            'rules'  => 'valid_date|greater_than_today',
            'errors' => [
                'greater_than_today' => 'Tanggal harus di masa depan.'
            ]
        ],
        'json_array' => [
            'rules'  => 'valid_json',
            'errors' => [
                'valid_json' => 'Harus berupa JSON array yang valid.'
            ]
        ],
        'image_base64' => [
            'rules'  => 'regex_match[/^data:image\/(jpeg|jpg|png|gif|webp);base64,[a-zA-Z0-9+\/]+=*$/]',
            'errors' => [
                'regex_match' => 'Format base64 gambar tidak valid.'
            ]
        ]
    ];
    
    // Custom rule for greater than today
    public function greater_than_today($str, &$error = null): bool
    {
        $today = date('Y-m-d');
        if ($str <= $today) {
            $error = 'Tanggal harus di masa depan.';
            return false;
        }
        return true;
    }
    
    // Custom rule for valid JSON
    public function valid_json($str, &$error = null): bool
    {
        json_decode($str);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = 'Format JSON tidak valid.';
            return false;
        }
        return true;
    }
    // --------------------------------------------------------------------
}
