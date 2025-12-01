<?php

namespace App\Filament\Widgets;

use App\Models\ListHewan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WorkflowManagementWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $totalHewans = ListHewan::count();
        $pendingPenyembelihan = ListHewan::where('penyembelihan', false)->count();
        $pendingPengulitan = ListHewan::where('penyembelihan', true)->where('pengulitan', false)->count();
        $pendingPenimbangan = ListHewan::where('pengulitan', true)->where('penimbangan', false)->count();
        $selesai = ListHewan::where('penimbangan', true)->count();

        return [
            Stat::make('Total Hewan', $totalHewans)
                ->description('Semua hewan terdaftar')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary'),

            Stat::make('Menunggu Penyembelihan', $pendingPenyembelihan)
                ->description(round(($totalHewans > 0 ? ($pendingPenyembelihan / $totalHewans) * 100 : 0), 1) . '% dari total')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->url(route('filament.admin.resources.list-hewans.index', [
                    'tableFilters' => ['penyembelihan' => ['value' => false]]
                ])),

            Stat::make('Menunggu Pengulitan', $pendingPengulitan)
                ->description('Sudah disembelih, belum dikuliti')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info')
                ->url(route('filament.admin.resources.list-hewans.index', [
                    'tableFilters' => [
                        'penyembelihan' => ['value' => true],
                        'pengulitan' => ['value' => false]
                    ]
                ])),

            Stat::make('Menunggu Penimbangan', $pendingPenimbangan)
                ->description('Sudah dikuliti, belum ditimbang')
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray')
                ->url(route('filament.admin.resources.list-hewans.index', [
                    'tableFilters' => [
                        'pengulitan' => ['value' => true],
                        'penimbangan' => ['value' => false]
                    ]
                ])),

            Stat::make('Selesai Diproses', $selesai)
                ->description(round(($totalHewans > 0 ? ($selesai / $totalHewans) * 100 : 0), 1) . '% progress')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->url(route('filament.admin.resources.list-hewans.index', [
                    'tableFilters' => ['penimbangan' => ['value' => true]]
                ])),
        ];
    }
}
