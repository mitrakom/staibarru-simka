<?php

namespace App\Filament\Admin\Clusters\Users\Resources\DosenResource\Pages;

use App\Filament\Admin\Clusters\Users\Resources\DosenResource;
use App\Models\Dosen;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;


use Illuminate\Support\Facades\Auth;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class CreateDosen extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = DosenResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Pilih Dosen')
                ->schema([
                    Forms\Components\Select::make('dosen_id')
                        ->label('Dosen')
                        ->options(function () {
                            // Ambil dosen yang belum terdaftar pada tabel user dengan peran 'dosen'
                            return \App\Models\Dosen::whereDoesntHave('user', function ($query) {
                                $query->where('identity_type', \App\Models\Dosen::class);
                            })->pluck('nama', 'id');
                        })
                        ->reactive()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            $dosen = Dosen::find($get('dosen_id'));
                            $set('nama', $dosen->nama);
                            $set('nidn', $dosen->nidn);
                        })
                        ->required(),

                    Forms\Components\TextInput::make('email')
                        ->email()->unique('users', 'email'),
                ]),

            Step::make('detail')
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->disabled(),
                    Forms\Components\TextInput::make('nidn')
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
        $dosen = Dosen::find($data['dosen_id']);

        $data['roles'] = ['dosen'];
        $data['name'] = $dosen->nama;
        $data['password'] = bcrypt($dosen->nidn);
        $data['identity_id'] = $data['dosen_id'];
        $data['identity_type'] = Dosen::class;

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

        // Tetapkan peran dosen
        $user->assignRole('dosen');

        return $user;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'User dosen sudah dibuat';
    }
}
