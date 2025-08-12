<?php

namespace App\Filament\Admin\Clusters\Users\Resources\MahasiswaResource\Pages;

use App\Filament\Admin\Clusters\Users\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use Filament\Resources\Pages\CreateRecord;

use Filament\Forms;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;

class CreateMahasiswa extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = MahasiswaResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Pilih Mahasiswa')
                ->schema([
                    Forms\Components\Select::make('mahasiswa_id')
                        ->label('Mahasiswa')
                        ->options(function () {
                            // Ambil mahasiswa yang belum terdaftar pada tabel user dengan peran 'mahasiswa'
                            return \App\Models\Mahasiswa::whereDoesntHave('user', function ($query) {
                                $query->where('identity_type', \App\Models\Mahasiswa::class);
                            })->pluck('nama', 'id');
                        })
                        ->reactive()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            $mahasiswa = Mahasiswa::find($get('mahasiswa_id'));
                            $set('nama', $mahasiswa->nama);
                            $set('nim', $mahasiswa->nim);
                        })
                        ->required(),

                    Forms\Components\TextInput::make('email')
                        ->email()->unique('users', 'email'),
                ]),

            Step::make('detail')
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->disabled(),
                    Forms\Components\TextInput::make('nim')
                        ->disabled(),
                ])
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $mahasiswa = Mahasiswa::find($data['mahasiswa_id']);

        $data['roles'] = ['mahasiswa'];
        $data['name'] = $mahasiswa->nama;
        $data['password'] = bcrypt($mahasiswa->nim);
        $data['identity_id'] = $data['mahasiswa_id'];
        $data['identity_type'] = Mahasiswa::class;

        // Debugging: Cek apakah data sudah benar
        // dd($data);

        unset($data['nama']);
        unset($data['nidn']);
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Buat instance User baru
        $user = new \App\Models\User($data);
        $user->save();

        // Tetapkan peran mahasiswa
        $user->assignRole('mahasiswa');

        return $user;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'User mahasiswa sudah dibuat';
    }
}
