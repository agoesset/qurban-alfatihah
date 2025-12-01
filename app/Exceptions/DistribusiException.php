<?php

namespace App\Exceptions;

class DistribusiException extends QurbanException
{
    public static function invalidRequestType(string $type): self
    {
        return new self(
            "Jenis permintaan '{$type}' tidak valid. Harus salah satu dari: Daging, Jeroan, Kepala & Kaki, Buntut.",
            400,
            ['invalid_type' => $type]
        );
    }

    public static function alreadyDistributed(int $distribusiId): self
    {
        return new self(
            "Distribusi dengan ID {$distribusiId} sudah terdistribusi.",
            400,
            ['distribusi_id' => $distribusiId]
        );
    }

    public static function notYetPacked(int $distribusiId): self
    {
        return new self(
            "Distribusi dengan ID {$distribusiId} belum dikemas. Tidak dapat mendistribusikan sebelum dikemas.",
            400,
            ['distribusi_id' => $distribusiId]
        );
    }
}
