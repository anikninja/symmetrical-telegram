<?php

namespace App\Filament\Resources\NoteTypeResource\Pages;

use App\Filament\Resources\NoteTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNoteType extends EditRecord
{
    protected static string $resource = NoteTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
