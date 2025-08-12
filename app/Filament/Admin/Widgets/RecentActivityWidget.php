<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\Dosen;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivityWidget extends BaseWidget
{
    protected static ?string $heading = 'Aktivitas Terbaru';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Mahasiswa::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('prodi.nama')
                    ->label('Program Studi')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn(Mahasiswa $record): string => \App\Filament\Admin\Resources\MahasiswaResource::getUrl('view', ['record' => $record])),
            ])
            ->emptyStateHeading('Belum ada mahasiswa terdaftar')
            ->emptyStateDescription('Mahasiswa yang baru mendaftar akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
