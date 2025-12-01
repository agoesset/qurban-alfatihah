<?php

namespace App\Exceptions;

class HewanNotFoundException extends QurbanException
{
    public function __construct(string $kodeHewan = '')
    {
        $message = $kodeHewan
            ? "Hewan dengan kode '{$kodeHewan}' tidak ditemukan."
            : "Hewan tidak ditemukan.";

        parent::__construct($message, 404, ['kode_hewan' => $kodeHewan]);
    }
}
