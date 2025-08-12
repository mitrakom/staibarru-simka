<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\KrsResource\Pages;
use App\Models\Krs;
use App\Models\Setting;
use App\Services\AcademicService;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class KrsResource extends Resource
{
    protected static ?string $model = Krs::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'KRS';
    protected static ?string $modelLabel = 'Rencana Studi Mahasiswa';
    public static function getPluralModelLabel(): string
    {
        $semester = app(AcademicService::class);
        return 'KRS Semester ' . $semester->semesterAktifKrsOnline();
    }

    public static function table(Table $table): Table
    {
        $isKrsOnlineActive = Setting::isKrsOnlineActive();

        return $table
            ->columns([
                // Kode matakuliah
                Tables\Columns\TextColumn::make('matakuliah.kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                // Nama matakuliah
                Tables\Columns\TextColumn::make('matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable()
                    ->extraAttributes(['class' => 'font-medium']),


                // SKS
                Tables\Columns\TextColumn::make('matakuliah.sks')
                    ->label('SKS')
                    ->alignCenter()
                    ->sortable(),

                // Status verifikasi (ikon)
                Tables\Columns\IconColumn::make('verifikasi')
                    ->label('Status')
                    ->alignCenter()
                    ->icon(fn(string $state): string => $state === 'y' ? 'heroicon-o-check' : 'heroicon-o-clock')
                    ->color(fn(string $state): string => $state === 'y' ? 'success' : 'warning')
                    ->tooltip(fn(string $state): string => $state === 'y' ? 'Terverifikasi' : 'Belum Terverifikasi'),
            ])
            ->defaultSort('matakuliah.kode')
            ->actions([
                // Tombol hapus
                Tables\Actions\DeleteAction::make()
                    ->visible(fn($record) => $isKrsOnlineActive && $record->verifikasi === 'n')
                    ->iconButton()
                    ->tooltip('Hapus Mata Kuliah')
            ])
            ->emptyStateHeading('Belum ada mata kuliah')
            ->emptyStateDescription('Silahkan tambahkan mata kuliah ke KRS Anda')
            ->emptyStateIcon('heroicon-o-academic-cap')
            ->emptyStateActions([
                Tables\Actions\Action::make('tambah')
                    ->label('Tambah Mata Kuliah')
                    ->url(fn(): string => KrsResource::getUrl('add'))
                    ->icon('heroicon-o-plus')
                    ->button(),
            ])
            ->striped()
            ->paginated(false)
        ;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKrs::route('/'),
            'add' => Pages\AddKrs::route('/add'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('semester', Setting::get('krs_online_semester'))
            ->where('mahasiswa_id', Auth::user()->identity->id);
    }
}
