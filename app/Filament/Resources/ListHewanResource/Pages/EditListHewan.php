<?php

namespace App\Filament\Resources\ListHewanResource\Pages;

use App\Filament\Resources\ListHewanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListHewan extends EditRecord
{
    protected static string $resource = ListHewanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
