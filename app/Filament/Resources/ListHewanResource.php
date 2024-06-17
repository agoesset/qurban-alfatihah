<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListHewanResource\Pages;
use App\Models\ListHewan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ListHewanResource extends Resource
{
    protected static ?string $model = ListHewan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_hewan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('kategori_id')
                    ->required()
                    ->relationship('kategori', 'nama_kategori'),
                Forms\Components\TextInput::make('bobot')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('penyembelihan')
                    ->required(),
                Forms\Components\Toggle::make('pengulitan')
                    ->required(),
                Forms\Components\Toggle::make('penimbangan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_hewan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Domba Promo' => 'danger',
                        'Domba Tipe A' => 'gray',
                        'Domba Tipe B' => 'info',
                        'Domba Tipe C' => 'primary',
                        'Domba Tipe D' => 'success',
                        'Domba Spesial' => 'warning',
                        'Kambing Promo' => 'danger',
                        'Kambing Tipe A' => 'gray',
                        'Kambing Tipe B' => 'info',
                        'Kambing Tipe C' => 'primary',
                        'Kambing Tipe D' => 'success',
                        'Kambing Tipe E' => 'warning',
                        'Sapi Jawa Favorit' => 'primary',
                        'Sapi Jawa Premium' => 'success',
                        'Sapi Jawa Super' => 'warning',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('bobot')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('penyembelihan')
                    ->boolean(),
                Tables\Columns\IconColumn::make('pengulitan')
                    ->boolean(),
                Tables\Columns\IconColumn::make('penimbangan')
                    ->boolean(),
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
            'index' => Pages\ListListHewans::route('/'),
            'create' => Pages\CreateListHewan::route('/create'),
            'edit' => Pages\EditListHewan::route('/{record}/edit'),
        ];
    }
}
