<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Mahasiswa;
use Filament\Widgets\ChartWidget;

class AdminDashboardChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Statistik Mahasiswa per Bulan';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $months = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');
            $data[] = Mahasiswa::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Mahasiswa Baru',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
