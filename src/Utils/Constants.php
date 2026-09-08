<?php

namespace App\Utils;

class Constants
{
    
    /**
     * 
     */
    const STATUS = [
        'DRAFT' => 'DRAFT', 
        'ACTIVE' => 'ACTIVE', 
        'VALIDATED' => 'VALIDATED',
        'CONFIRMED' => 'CONFIRMED',
        'REJECTED' => 'REJECTED',
    ];


    const ROLES = [
        'SUPERADMIN' => 'SUPERADMIN',
        'MEDICAL_ADMIN' => 'MEDICAL_ADMIN',
        'ADMIN' => 'ADMIN',
        'USER' => 'USER',
    ];

    const ENV = [
        'DEV' => 'DEV',
        'TEST' => 'TEST',
        'PROD' => 'PROD',
        'ALL' => 'ALL',
    ];

    const MIME_TYPE = [
        'PDF' => 'application/pdf',
        'JSON' => 'application/json',
        'PLAIN' => 'text/plain',
        'HTML' => 'text/html',
        'JPEG' => 'image/jpeg',
        'PNG' => 'image/png',
        'GIF' => 'image/gif',
        'ZIP' => 'application/zip',
    ];  

    const CIR_EDITION = [
        '2025' => '2025',
        '2026' => '2026'
    ];
}
