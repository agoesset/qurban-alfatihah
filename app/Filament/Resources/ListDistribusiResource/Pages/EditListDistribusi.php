<?php

namespace App\Filament\Resources\ListDistribusiResource\Pages;

use App\Filament\Resources\ListDistribusiResource;
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
}
