<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Kurikulum;
use App\Models\Krs;
use App\Models\Matakuliah;
use App\Models\Prodi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminDashboardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $currentYear = date('Y');
        $currentMonth = date('m');

        return [
            Stat::make('Total Mahasiswa', Mahasiswa::count())
                ->description('Mahasiswa terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([7, 12, 15, 18, 20, 22, 25]),

            Stat::make('Total Dosen', Dosen::count())
                ->description('Dosen aktif')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info')
                ->chart([3, 5, 7, 8, 9, 10, 12]),

            Stat::make('Program Studi', Prodi::count())
                ->description('Prodi tersedia')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('warning'),

            Stat::make('Mata Kuliah', Matakuliah::count())
                ->description('Total mata kuliah')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),

            Stat::make(
                'KRS Semester Ini',
                Krs::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->count()
            )
                ->description('KRS bulan ini')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Kurikulum Aktif', Kurikulum::count())
                ->description('Kurikulum tersedia')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),
        ];
    }
}
