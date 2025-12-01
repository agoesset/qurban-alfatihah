<?php

namespace App\Filament\Resources\ListDistribusiResource\Pages;

use App\Filament\Resources\ListDistribusiResource;
use App\Models\DistribusiDetail;
use App\Models\HewanMeatPart;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListDistribusi extends EditRecord
{
    protected static string $resource = ListDistribusiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing meat requests from details
        $data['meat_requests'] = $this->record->details->map(function ($detail) {
            return [
                'jenis_bagian' => $detail->hewanMeatPart->jenis_bagian,
                'berat' => $detail->berat,
            ];
        })->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Store meat_requests temporarily for afterSave hook
        $this->meatRequests = $data['meat_requests'] ?? [];

        // Remove meat_requests from data as it's not a column
        unset($data['meat_requests']);

        return $data;
    }

    protected function afterSave(): void
    {
        $distribusi = $this->record;

        // Delete old details (stock will be released via model event)
        $distribusi->details()->delete();

        // Create new details and allocate stock
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
