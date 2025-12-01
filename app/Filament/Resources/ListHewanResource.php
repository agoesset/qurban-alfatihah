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
                Tables\Filters\SelectFilter::make('kategori')
                    ->relationship('kategori', 'nama_kategori')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('Kategori Hewan'),

                Tables\Filters\TernaryFilter::make('penyembelihan')
                    ->label('Status Penyembelihan')
                    ->placeholder('Semua')
                    ->trueLabel('Sudah Disembelih')
                    ->falseLabel('Belum Disembelih'),

                Tables\Filters\TernaryFilter::make('pengulitan')
                    ->label('Status Pengulitan')
                    ->placeholder('Semua')
                    ->trueLabel('Sudah Dikuliti')
                    ->falseLabel('Belum Dikuliti'),

                Tables\Filters\TernaryFilter::make('penimbangan')
                    ->label('Status Penimbangan')
                    ->placeholder('Semua')
                    ->trueLabel('Sudah Ditimbang')
                    ->falseLabel('Belum Ditimbang'),

                Tables\Filters\TrashedFilter::make()
                    ->label('Status Data'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dibuat Dari'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Dibuat Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Dibuat dari ' . \Carbon\Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Dibuat sampai ' . \Carbon\Carbon::parse($data['created_until'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
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
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),

                    // Custom bulk actions for workflow status
                    Tables\Actions\BulkAction::make('mark_penyembelihan')
                        ->label('Tandai Sudah Disembelih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['penyembelihan' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('mark_pengulitan')
                        ->label('Tandai Sudah Dikuliti')
                        ->icon('heroicon-o-check-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['pengulitan' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('mark_penimbangan')
                        ->label('Tandai Sudah Ditimbang')
                        ->icon('heroicon-o-check-circle')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['penimbangan' => true]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
