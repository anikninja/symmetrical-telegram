<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoteResource\Pages;
use App\Filament\Resources\NoteResource\RelationManagers;
use App\Models\Note;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Library;
use App\Models\Folder;


class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Note Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('time_duration')
                    ->required(),
                Forms\Components\Textarea::make('transcription')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('summary')
                    ->columnSpanFull(),
                Forms\Components\Select::make('language_id')
                    ->label('Language')
                    ->relationship('language', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('type_id')
                    ->label('Note Type')
                    ->relationship('type', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('library_id', null);
                        $set('folder_id', null);
                    }),
                Forms\Components\Select::make('library_id')
                    ->label('Library')
                    ->relationship('library', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(function (callable $get) {
                        $userId = $get('user_id');
                        return $userId ? Library::where('user_id', $userId)->pluck('name', 'id') : [];
                    }),
                Forms\Components\Select::make('folder_id')
                    ->label('Folder')
                    ->relationship('folder', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(function (callable $get) {
                        $userId = $get('user_id');
                        return $userId ? Folder::where('user_id', $userId)->pluck('name', 'id') : [];
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('time_duration'),
                Tables\Columns\TextColumn::make('type.name')
                    ->label('Note Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('language.name')
                    ->label('Language')
                    ->sortable(),
                Tables\Columns\TextColumn::make('library.name')
                    ->label('Library')
                    ->sortable(),
                Tables\Columns\TextColumn::make('folder.name')
                    ->label('Folder')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotes::route('/'),
            'create' => Pages\CreateNote::route('/create'),
            'edit' => Pages\EditNote::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
