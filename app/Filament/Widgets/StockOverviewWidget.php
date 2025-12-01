<?php

namespace App\Filament\Widgets;

use App\Models\HewanMeatPart;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $dagingTotal = HewanMeatPart::ofType('Daging')->sum('berat_total');
        $dagingTersedia = HewanMeatPart::ofType('Daging')->sum('berat_tersedia');

        $jeroanTotal = HewanMeatPart::ofType('Jeroan')->sum('berat_total');
        $jeroanTersedia = HewanMeatPart::ofType('Jeroan')->sum('berat_tersedia');

        $kepalaKakiTotal = HewanMeatPart::ofType('Kepala & Kaki')->sum('berat_total');
        $kepalaKakiTersedia = HewanMeatPart::ofType('Kepala & Kaki')->sum('berat_tersedia');

        $buntutTotal = HewanMeatPart::ofType('Buntut')->sum('berat_total');
        $buntutTersedia = HewanMeatPart::ofType('Buntut')->sum('berat_tersedia');

        return [
            Stat::make('Stock Daging', number_format($dagingTersedia, 2) . ' kg')
                ->description('Dari total ' . number_format($dagingTotal, 2) . ' kg (' .
                    round($dagingTotal > 0 ? ($dagingTersedia / $dagingTotal) * 100 : 0, 1) . '% tersedia)')
                ->descriptionIcon('heroicon-m-cube')
                ->color($this->getStockColor($dagingTotal, $dagingTersedia))
                ->chart($this->getSparklineData($dagingTotal, $dagingTersedia)),

            Stat::make('Stock Jeroan', number_format($jeroanTersedia, 2) . ' kg')
                ->description('Dari total ' . number_format($jeroanTotal, 2) . ' kg (' .
                    round($jeroanTotal > 0 ? ($jeroanTersedia / $jeroanTotal) * 100 : 0, 1) . '% tersedia)')
                ->descriptionIcon('heroicon-m-cube')
                ->color($this->getStockColor($jeroanTotal, $jeroanTersedia)),

            Stat::make('Stock Kepala & Kaki', number_format($kepalaKakiTersedia, 2) . ' kg')
                ->description('Dari total ' . number_format($kepalaKakiTotal, 2) . ' kg (' .
                    round($kepalaKakiTotal > 0 ? ($kepalaKakiTersedia / $kepalaKakiTotal) * 100 : 0, 1) . '% tersedia)')
                ->descriptionIcon('heroicon-m-cube')
                ->color($this->getStockColor($kepalaKakiTotal, $kepalaKakiTersedia)),

            Stat::make('Stock Buntut', number_format($buntutTersedia, 2) . ' kg')
                ->description('Dari total ' . number_format($buntutTotal, 2) . ' kg (' .
                    round($buntutTotal > 0 ? ($buntutTersedia / $buntutTotal) * 100 : 0, 1) . '% tersedia)')
                ->descriptionIcon('heroicon-m-cube')
                ->color($this->getStockColor($buntutTotal, $buntutTersedia)),
        ];
    }

    private function getStockColor(float $total, float $available): string
    {
        if ($total == 0) return 'gray';

        $percentage = ($available / $total) * 100;

        return match(true) {
            $percentage >= 75 => 'success',
            $percentage >= 50 => 'info',
            $percentage >= 25 => 'warning',
            default => 'danger'
        };
    }

    private function getSparklineData(float $total, float $available): array
    {
        $used = $total - $available;
        return [$used, $available];
    }
}
