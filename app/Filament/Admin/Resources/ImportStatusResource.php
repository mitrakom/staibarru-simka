<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ImportStatusResource\Pages;
use App\Filament\Admin\Resources\ImportStatusResource\RelationManagers;
use App\Http\Controllers\ImportController;
use App\Models\ImportStatus;
use App\Services\Feeder\DosenImportService;
use App\Services\Feeder\FeederClient;
use App\Services\Feeder\Import\FakultasImportService;
use App\Services\Feeder\Import\KurikulumImportService;
use App\Services\Feeder\Import\KurikulumMatakuliahImportService;
use App\Services\Feeder\Import\MataKuliahImportService;
use App\Services\Feeder\Import\PerguruanTinggiImportService;
use App\Services\Feeder\Import\ProdiImportService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImportStatusResource extends Resource
{
    protected static ?string $model = ImportStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Sembunyikan dari sidemenu
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\Textarea::make('error_summary')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('method'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('error_summary')
                    ->label('error')
                    ->wrap()
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make()

                Tables\Actions\Action::make('runImport')
                    ->label('Jalankan Import')
                    ->icon('heroicon-o-play')
                    ->requiresConfirmation()
                    ->action(function (ImportStatus $record) {
                        // Menentukan service berdasarkan 'name'
                        $serviceMap = [
                            'perguruan_tinggi' => PerguruanTinggiImportService::class,
                            'fakultas' => FakultasImportService::class,
                            'prodi' => ProdiImportService::class,
                            'kurikulum' => KurikulumImportService::class,
                            'matakuliah' => MataKuliahImportService::class,
                            'dosen' => DosenImportService::class,
                            'kurikulum_matakuliah' => KurikulumMatakuliahImportService::class,
                            // 'mahasiswa' => MahasiswaImportService::class,
                        ];

                        $method = strtolower($record->name);
                        if (isset($serviceMap[$method])) {
                            $record->update(['status' => 'in_progress']);
                            try {
                                app($serviceMap[$method])->import();
                                $record->update(['status' => 'done']);
                                Notification::make()
                                    ->title('Import Selesai')
                                    ->body("Import untuk {$record->name} berhasil dijalankan.")
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                $record->update(['status' => 'failed', 'error_summary' => $e->getMessage()]);
                                Notification::make()
                                    ->title('Import Gagal')
                                    ->body("Terjadi kesalahan: " . $e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        } else {
                            Notification::make()
                                ->title('Gagal Menjalankan Import')
                                ->body("Service untuk {$record->name} tidak ditemukan.")
                                ->danger()
                                ->send();
                        }
                    }),

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
            'index' => Pages\ListImportStatuses::route('/'),
            'create' => Pages\CreateImportStatus::route('/create'),
            'edit' => Pages\EditImportStatus::route('/{record}/edit'),
        ];
    }
}
