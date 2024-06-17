<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListDistribusiResource\Pages;
use App\Filament\Resources\ListDistribusiResource\RelationManagers;
use App\Models\ListDistribusi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ListDistribusiResource extends Resource
{
    protected static ?string $model = ListDistribusi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                Forms\Components\TagsInput::make('request')
                    ->suggestions([
                        'Daging Domba',
                        'Daging Kambing',
                        'Daging Sapi',
                        'Jeroan',
                        'Kepala & Kaki',
                        'Buntut',
                    ])
                    ->separator(',')
                    ->required(),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('shohibul_qurban')
                    ->required(),
                Forms\Components\Toggle::make('terbungkus')
                    ->required(),
                Forms\Components\Toggle::make('terdistribusi')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\IconColumn::make('shohibul_qurban')
                    ->boolean(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('request')
                    ->badge()
                    ->separator(','),
                Tables\Columns\IconColumn::make('terbungkus')
                    ->boolean(),
                Tables\Columns\IconColumn::make('terdistribusi')
                    ->boolean(),
                Tables\Columns\TextColumn::make('alamat')
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
            'index' => Pages\ListListDistribusis::route('/'),
            'create' => Pages\CreateListDistribusi::route('/create'),
            'edit' => Pages\EditListDistribusi::route('/{record}/edit'),
        ];
    }
}
