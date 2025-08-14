<?php

namespace App\Filament\Admin\Resources\PenasehatAkademikResource\Pages;

use App\Filament\Admin\Resources\PenasehatAkademikResource;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use Filament\Forms;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;

class CreatePenasehatAkademik extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = PenasehatAkademikResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['semester'] = session('semester');
        unset($data['nim']);
        unset($data['nama_mahasiswa']);
        unset($data['alamat_mahasiswa']);
        unset($data['nidn']);
        unset($data['nama_dosen']);
        unset($data['alamat_dosen']);
        return $data;
    }

    protected function getSteps(): array
    {
        return [

            Step::make('dosen')
                ->label('Pilih Dosen')
                ->schema([
                    Forms\Components\Select::make('dosen_id')
                        ->label('')
                        // ->options(Dosen::where('prodi_id', session('prodi_id'))->pluck('nama', 'id'))
                        ->options(Dosen::pluck('nama', 'id'))
                        ->preload()
                        ->required()
                        ->searchable()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            $dosen = Dosen::find($get('dosen_id'));
                            $set('nidn', $dosen->nidn);
                            $set('nama_dosen', $dosen->nama);
                            $set('alamat_dosen', $dosen->alamat);
                        }),
                ]),
            Step::make('mahasiswa')
                ->label('Pilih Mahasiswa')
                ->schema([
                    Forms\Components\Select::make('mahasiswa_id')
                        ->label('')
                        ->options(Mahasiswa::where('prodi_id', session('prodi_id'))->pluck('nama', 'id'))
                        ->preload()
                        ->required()
                        ->searchable()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            $mahasiswa = Mahasiswa::find($get('mahasiswa_id'));
                            $set('nim', $mahasiswa->nim);
                            $set('nama_mahasiswa', $mahasiswa->nama);
                            $set('alamat_mahasiswa', $mahasiswa->alamat);
                        }),
                ]),
            Step::make('identitas')
                // ->label('Identitas Mahasiswa')
                ->schema([
                    Forms\Components\Fieldset::make('Dosen')
                        ->schema([
                            Forms\Components\TextInput::make('nidn')
                                ->label('NIDN')
                                ->disabled(),
                            Forms\Components\TextInput::make('nama_dosen')
                                ->disabled(),
                            Forms\Components\Textarea::make('alamat_dosen')
                                ->disabled()
                                ->columnSpanFull(),
                        ]),

                    Forms\Components\Fieldset::make('Mahasiswa')
                        ->schema([
                            Forms\Components\TextInput::make('nim')
                                ->label('NIM')
                                ->disabled(),
                            Forms\Components\TextInput::make('nama_mahasiswa')
                                ->disabled(),
                            Forms\Components\Textarea::make('alamat_mahasiswa')
                                ->disabled()
                                ->columnSpanFull(),
                        ]),
                ]),

        ];
    }
}
