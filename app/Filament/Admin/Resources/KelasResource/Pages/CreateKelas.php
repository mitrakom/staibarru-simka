<?php

namespace App\Filament\Admin\Resources\KelasResource\Pages;

use App\Filament\Admin\Resources\KelasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Form;
use Filament\Forms;

class CreateKelas extends CreateRecord
{
    protected static string $resource = KelasResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Kelas')
                    ->placeholder('Masukkan Nama Kelas'),
            ]);
    }
}
