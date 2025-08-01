<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'auth'          => \App\Filters\AuthFilter::class, // Tambahkan baris ini
    ];

    public array $globals = [
        'before' => [
            // 'honeypot',
            'csrf', // Aktifkan CSRF protection untuk semua POST request
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    public array $methods = [];

    public array $filters = [
        'auth' => [ // Terapkan filter 'auth' ke rute yang ingin dilindungi
            'before' => [
                'anggota/*',
                'bunga/*',
                'pencairan/*',
                'user/*',
                'angsuran/*',
                'pembayaran-angsuran/*',
                'kredit/*',
                'home', // Lindungi halaman dashboard
            ],
        ],
    ];
}
