<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Resources\KrsResource;
use App\Filament\Admin\Resources\KurikulumResource;
use App\Filament\Admin\Resources\MahasiswaResource;
use App\Filament\Admin\Resources\PerguruanTinggiResource;
use App\Filament\Admin\Resources\ProdiResource;
use App\Http\Middleware\AdminMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Admin\Clusters\Settings;
use App\Filament\Admin\Pages\AdminDashboard;
use App\Filament\Admin\Resources\KurikulumMatakuliahResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverClusters(in: app_path('Filament/Admin/Clusters'), for: 'App\\Filament\\Admin\\Clusters')
            ->pages([
                // Pages\Dashboard::class,
                AdminDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                AdminMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::TOPBAR_START,
                function (): string {
                    $semester = '';
                    return view('filament.admin.topbar', [
                        'user' => Auth::user()->name,
                    ])->render();
                }
            )
            ->navigationGroups([
                NavigationGroup::make('Master'),
                NavigationGroup::make('Akademik'),
            ])
            // ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
            //     return $builder
            //         ->items([
            //             ...MahasiswaResource::getNavigationItems(),
            //             ...Settings::getNavigationItems(),
            //         ])
            //         ->groups([
            //             NavigationGroup::make('Master')
            //                 ->items([
            //                     ...PerguruanTinggiResource::getNavigationItems(),
            //                     ...KurikulumResource::getNavigationItems(),
            //                     ...KurikulumMatakuliahResource::getNavigationItems(),
            //                     ...ProdiResource::getNavigationItems(),
            //                 ]),
            //             NavigationGroup::make('Akademik')
            //                 ->items([
            //                     ...KrsResource::getNavigationItems(),
            //                 ]),
            //         ]);
            // })
        ;
    }
}
