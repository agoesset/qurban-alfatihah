<?php

namespace App\Filament\Resources\ListDistribusiResource\Pages;

use App\Filament\Resources\ListDistribusiResource;
use App\Models\DistribusiDetail;
use App\Models\HewanMeatPart;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateListDistribusi extends CreateRecord
{
    protected static string $resource = ListDistribusiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Store meat_requests temporarily for afterCreate hook
        $this->meatRequests = $data['meat_requests'] ?? [];

        // Remove meat_requests from data as it's not a column in list_distribusis table
        unset($data['meat_requests']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $distribusi = $this->record;

        // Create DistribusiDetail records and allocate stock
        if (!empty($this->meatRequests)) {
            foreach ($this->meatRequests as $request) {
                $jenisBagian = $request['jenis_bagian'];
                $berat = (float) $request['berat'];

                // Find available meat part with enough stock
                $meatPart = HewanMeatPart::ofType($jenisBagian)
                    ->where('berat_tersedia', '>=', $berat)
                    ->orderBy('created_at', 'asc') // FIFO
                    ->first();

                if ($meatPart) {
                    // Allocate stock
                    $meatPart->allocateStock($berat);

                    // Create detail record
                    DistribusiDetail::create([
                        'list_distribusi_id' => $distribusi->id,
                        'hewan_meat_part_id' => $meatPart->id,
                        'berat' => $berat,
                    ]);
                }
            }
        }
    }

    private array $meatRequests = [];
}
