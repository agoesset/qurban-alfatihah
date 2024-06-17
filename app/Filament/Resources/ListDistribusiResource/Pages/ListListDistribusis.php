<?php

namespace App\Filament\Resources\ListDistribusiResource\Pages;

use App\Filament\Resources\ListDistribusiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListDistribusis extends ListRecords
{
    protected static string $resource = ListDistribusiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
