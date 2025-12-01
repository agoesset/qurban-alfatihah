<?php

namespace App\Observers;

use App\Models\ListHewan;
use App\Models\HewanMeatPart;

class ListHewanObserver
{
    /**
     * Handle the ListHewan "updated" event.
     * Auto-create meat parts when penimbangan is completed
     */
    public function updated(ListHewan $hewan): void
    {
        // Check if penimbangan just changed to true
        if ($hewan->wasChanged('penimbangan') && $hewan->penimbangan) {
            $this->createMeatParts($hewan);
        }
    }

    /**
     * Create meat parts based on hewan bobot
     * This is a simplified distribution - can be customized per jenis
     */
    private function createMeatParts(ListHewan $hewan): void
    {
        // Check if meat parts already exist
        if ($hewan->meatParts()->exists()) {
            return;
        }

        $totalBobot = (float) $hewan->bobot;

        // Distribution percentages (can be customized)
        $distribution = [
            'Daging' => 0.65,      // 65% daging
            'Jeroan' => 0.20,      // 20% jeroan
            'Kepala & Kaki' => 0.12, // 12% kepala & kaki
            'Buntut' => 0.03,      // 3% buntut
        ];

        foreach ($distribution as $jenisBagian => $percentage) {
            $berat = round($totalBobot * $percentage, 2);

            HewanMeatPart::create([
                'list_hewan_id' => $hewan->id,
                'jenis_bagian' => $jenisBagian,
                'berat_total' => $berat,
                'berat_tersedia' => $berat, // Initially all available
            ]);
        }
    }
}
