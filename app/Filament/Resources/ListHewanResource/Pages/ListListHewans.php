<?php

namespace App\Filament\Resources\ListHewanResource\Pages;

use App\Filament\Resources\ListHewanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListHewans extends ListRecords
{
    protected static string $resource = ListHewanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
