<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\AdminDashboardStatsWidget;
use App\Filament\Admin\Widgets\AdminDashboardChartWidget;
use App\Filament\Admin\Widgets\RecentActivityWidget;
use Filament\Pages\Page;

class AdminDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.admin.pages.admin-dashboard';

    protected static ?string $title = '';

    protected function getHeaderWidgets(): array
    {
        return [
            // Kosongkan header widgets agar tidak muncul di atas
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            AdminDashboardChartWidget::class,
            RecentActivityWidget::class,
        ];
    }
}
