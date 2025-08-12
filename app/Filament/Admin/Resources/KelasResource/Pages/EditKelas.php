<?php

namespace App\Filament\Admin\Resources\KelasResource\Pages;

use App\Filament\Admin\Resources\KelasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;
use Filament\Forms;

class EditKelas extends EditRecord
{
    protected static string $resource = KelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Simpan')
                ->color('primary')
                ->icon('heroicon-o-check')
                ->action('save'),
            Actions\DeleteAction::make(),
        ];
    }

    // Menghilangkan tombol aksi pada footer form
    protected function getFormActions(): array
    {
        return [];
    }

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kelas')
                    ->description(fn($record) => 'Informasi detail tentang kelas: ' .
                        ($record->matakuliah->kode ?? '-') . ' - ' . ($record->matakuliah->nama ?? '-'))
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Kelas')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Contoh: Kelas A, Kelas B, dll')
                            ->placeholder('Masukkan nama kelas'),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->required()
                            ->maxLength(255)
                            ->rows(3)
                            ->placeholder('Masukkan keterangan kelas')
                            ->helperText('Berikan keterangan tambahan tentang kelas ini'),
                    ])
                    ->columns(1),
            ]);
    }
}
