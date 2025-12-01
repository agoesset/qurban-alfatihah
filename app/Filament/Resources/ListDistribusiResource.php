<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListDistribusiResource\Pages;
use App\Filament\Resources\ListDistribusiResource\RelationManagers;
use App\Models\ListDistribusi;
use App\Models\HewanMeatPart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;

class ListDistribusiResource extends Resource
{
    protected static ?string $model = ListDistribusi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Penerima')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Penerima')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('alamat')
                            ->label('Alamat')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah Penerima (orang)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1),

                        Forms\Components\Toggle::make('shohibul_qurban')
                            ->label('Shohibul Qurban')
                            ->default(false)
                            ->helperText('Centang jika penerima adalah shohibul qurban'),
                    ])
                    ->columns(2),

                Section::make('Stock Tersedia')
                    ->schema([
                        Placeholder::make('stock_info')
                            ->label('')
                            ->content(function () {
                                $stocks = [
                                    'Daging' => HewanMeatPart::ofType('Daging')->sum('berat_tersedia'),
                                    'Jeroan' => HewanMeatPart::ofType('Jeroan')->sum('berat_tersedia'),
                                    'Kepala & Kaki' => HewanMeatPart::ofType('Kepala & Kaki')->sum('berat_tersedia'),
                                    'Buntut' => HewanMeatPart::ofType('Buntut')->sum('berat_tersedia'),
                                ];

                                $html = '<div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">';
                                foreach ($stocks as $jenis => $berat) {
                                    $color = $berat > 10 ? 'green' : ($berat > 5 ? 'yellow' : 'red');
                                    $html .= "<div class='p-2 bg-{$color}-50 dark:bg-{$color}-900/20 rounded-lg'>";
                                    $html .= "<div class='font-semibold text-{$color}-800 dark:text-{$color}-300'>{$jenis}</div>";
                                    $html .= "<div class='text-lg font-bold text-{$color}-600 dark:text-{$color}-400'>" . number_format($berat, 2) . " kg</div>";
                                    $html .= "</div>";
                                }
                                $html .= '</div>';

                                return new \Illuminate\Support\HtmlString($html);
                            }),
                    ])
                    ->collapsible(),

                Section::make('Permintaan Daging')
                    ->schema([
                        Forms\Components\Repeater::make('meat_requests')
                            ->label('Pilih Jenis & Berat Daging')
                            ->schema([
                                Forms\Components\Select::make('jenis_bagian')
                                    ->label('Jenis Bagian')
                                    ->options([
                                        'Daging' => 'Daging',
                                        'Jeroan' => 'Jeroan',
                                        'Kepala & Kaki' => 'Kepala & Kaki',
                                        'Buntut' => 'Buntut',
                                    ])
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state) {
                                            $available = HewanMeatPart::ofType($state)->sum('berat_tersedia');
                                            $set('available_stock', $available);
                                        }
                                    }),

                                Forms\Components\Hidden::make('available_stock'),

                                Forms\Components\TextInput::make('berat')
                                    ->label('Berat (kg)')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->suffix('kg')
                                    ->helperText(function (Forms\Get $get) {
                                        $jenis = $get('jenis_bagian');
                                        if (!$jenis) {
                                            return 'Pilih jenis bagian terlebih dahulu';
                                        }

                                        $available = HewanMeatPart::ofType($jenis)->sum('berat_tersedia');
                                        return "Stock tersedia: " . number_format($available, 2) . " kg";
                                    })
                                    ->rules([
                                        function (Forms\Get $get) {
                                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                $jenis = $get('jenis_bagian');
                                                if (!$jenis) {
                                                    $fail('Pilih jenis bagian terlebih dahulu');
                                                    return;
                                                }

                                                $available = HewanMeatPart::ofType($jenis)->sum('berat_tersedia');

                                                if ($value > $available) {
                                                    $fail("Berat melebihi stock tersedia ({$available} kg)");
                                                }
                                            };
                                        },
                                    ]),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Jenis Daging')
                            ->collapsible()
                            ->columnSpanFull()
                            ->minItems(1)
                            ->required(),

                        // Keep the old 'request' field for backward compatibility but hide it
                        Forms\Components\Hidden::make('request')
                            ->default([]),
                    ]),

                Section::make('Status Distribusi')
                    ->schema([
                        Forms\Components\Toggle::make('terbungkus')
                            ->label('Sudah Terbungkus')
                            ->default(false),

                        Forms\Components\Toggle::make('terdistribusi')
                            ->label('Sudah Terdistribusi')
                            ->default(false),
                    ])
                    ->columns(2),
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
