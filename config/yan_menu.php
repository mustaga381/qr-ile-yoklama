<?php

return [
    'marka' => [
        'ikon'   => 'qr-code',
        'yazi'   => 'QR',
        'vurgu'  => 'Yoklama',
        'versiyon' => 'v1',
        'anasayfa' => '/',
    ],

    'ogeler' => [
        // =========================
        // ADMIN
        // =========================
        [
            'grup'   => 'Admin',
            'etiket' => 'Panel',
            'rota'   => 'admin.panel',
            'aktif'  => ['admin.panel'],
            'ikon'   => 'squares-2x2',
            'roller' => ['admin'],
        ],
        [
            'grup'   => 'Admin',
            'etiket' => 'Hocalar',
            'rota'   => 'admin.hocalar.index',
            'aktif'  => ['admin.hocalar.*'],
            'ikon'   => 'users',
            'roller' => ['admin'],
        ],
        [
            'grup'   => 'Admin',
            'etiket' => 'Öğrenciler',
            'rota'   => 'admin.ogrenciler.index',
            'aktif'  => ['admin.ogrenciler.*'],
            'ikon'   => 'academic-cap',
            'roller' => ['admin'],
        ],
        [
            'grup'   => 'Yönetim',
            'etiket' => 'Bölümler',
            'rota'   => 'admin.bolumler.index',
            'aktif'  => ['admin.bolumler.*'],
            'ikon'   => 'building-office-2',
            'roller' => ['admin'],
        ],

        [
            'grup'   => 'Genel',
            'etiket' => 'Panel',
            'rota'   => 'hoca.panel',
            'aktif'  => ['hoca.panel'],
            'ikon'   => 'squares-2x2',
            'roller' => ['hoca'],
        ],
        [
            'grup'   => 'Yoklama',
            'etiket' => 'Yoklama',
            'rota'   => 'hoca.yoklama.start',
            'aktif'  => ['hoca.yoklama.*'],
            'ikon'   => 'signal',
            'roller' => ['hoca'],
        ],
        [
            'grup'   => 'Ders',
            'etiket' => 'Ders Açılışları',
            'rota'   => 'hoca.ders_acilimlari.index',
            'aktif'  => ['hoca.ders_acilimlari.*'],
            'ikon'   => 'academic-cap',
            'roller' => ['hoca'],
        ],
        [
            'grup'   => 'Ders',
            'etiket' => 'Dersler',
            'rota'   => 'hoca.dersler.index',
            'aktif'  => ['hoca.dersler.*'],
            'ikon'   => 'book-open',
            'roller' => ['hoca'],
        ],

        // =========================
        // ÖĞRENCİ
        // =========================
        [
            'grup'   => 'Genel',
            'etiket' => 'Panel',
            'rota'   => 'ogrenci.panel',
            'aktif'  => ['ogrenci.panel'],
            'ikon'   => 'squares-2x2',
            'roller' => ['ogrenci'],
        ],
        [
            'grup'   => 'Ders',
            'etiket' => 'Dersler',
            'rota'   => 'ogrenci.derslerim.index',
            'aktif'  => ['ogrenci.derslerim.*'],
            'ikon'   => 'book-open',
            'roller' => ['ogrenci'],
        ],
        [
            'grup'   => 'Yoklama',
            'etiket' => 'Yoklamalar',
            'rota'   => 'ogrenci.yoklamalarim',
            'aktif'  => ['ogrenci.yoklamalarim'],
            'ikon'   => 'clipboard-document-check',
            'roller' => ['ogrenci'],
        ],
    ],

    'alt' => [
        'butonlar' => [
            [
                'tip'   => 'link',
                'etiket' => 'Profil',
                'rota'  => 'profil.goster',
                'ikon'  => 'user-circle',
                'roller' => ['ogrenci', 'hoca', 'admin'],
            ],
            [
                'tip'   => 'form',
                'etiket' => 'Çıkış',
                'rota'  => 'cikis',
                'ikon'  => 'arrow-right-on-rectangle',
                'roller' => ['ogrenci', 'hoca', 'admin'],
            ],
        ],
    ],
];
