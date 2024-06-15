<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgressResource\Pages;
use App\Filament\Resources\ProgressResource\RelationManagers;
use App\Models\Progress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgressResource extends Resource
{
    protected static ?string $model = Progress::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_progress')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jumlah_target')
                    ->maxLength(255),
                Forms\Components\TextInput::make('jumlah_progress')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('nama_progress')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_target')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_progress')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProgress::route('/'),
            'create' => Pages\CreateProgress::route('/create'),
            'edit' => Pages\EditProgress::route('/{record}/edit'),
        ];
    }
}
