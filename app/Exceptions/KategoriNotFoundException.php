<?php

namespace App\Exceptions;

class KategoriNotFoundException extends QurbanException
{
    public function __construct(int|string $identifier = '')
    {
        $message = $identifier
            ? "Kategori dengan ID/nama '{$identifier}' tidak ditemukan."
            : "Kategori tidak ditemukan.";

        parent::__construct($message, 404, ['identifier' => $identifier]);
    }
}
