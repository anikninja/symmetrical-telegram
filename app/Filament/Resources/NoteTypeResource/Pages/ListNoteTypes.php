<?php

namespace App\Filament\Resources\NoteTypeResource\Pages;

use App\Filament\Resources\NoteTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNoteTypes extends ListRecords
{
    protected static string $resource = NoteTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
