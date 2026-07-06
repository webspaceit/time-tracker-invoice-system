<?php

return [
    'logo' => env('INVOICE_LOGO', 'images/logo.png'),
    'signature' => env('INVOICE_SIGNATURE', 'images/signature.jpg'),

    'company' => [
        'name' => env('INVOICE_COMPANY_NAME', 'MOHAMMAD KUDRAT-E-KHUDA'),
        'address' => env('INVOICE_COMPANY_ADDRESS', 'HOUSE-04, ROAD-S-8, BLOCK-L, EASTERN HOUSING PALLABI-2ND PHASE, MIRPUR, DHAKA, BANGLADESH'),
    ],

    'bank' => [
        'bank_name' => env('INVOICE_BANK_NAME', 'BRAC BANK PLC'),
        'account_number' => env('INVOICE_ACCOUNT_NUMBER', '1548202099706001'),
        'account_name' => env('INVOICE_ACCOUNT_NAME', 'MOHAMMAD KUDRAT E KHUDA'),
        'branch' => env('INVOICE_BANK_BRANCH', 'BEGUM ROKEYA SARANI'),
        'swift_code' => env('INVOICE_SWIFT_CODE', 'BRAKBDDH'),
    ],

    'terms' => [
        'Payment is due within 7 days',
        'Please email the invoice after sending payment',
    ],
];
