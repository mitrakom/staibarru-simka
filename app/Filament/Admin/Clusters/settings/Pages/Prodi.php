<?php

namespace App\Filament\Admin\Clusters\settings\Pages;

use App\Filament\Admin\Clusters\settings;
use Filament\Pages\Page;

use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Filament\Forms;
use App\Models\Prodi as ProdiModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Prodi extends Page
{
    public $prodi_aktif;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.clusters.settings.pages.prodi';

    protected static ?string $cluster = settings::class;

    public function mount()
    {
        $this->prodi_aktif = User::where('id', Auth::id())->first()->prodi_id;
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Set Program Studi Aktif')
                ->label('')
                ->schema([
                    Forms\Components\Select::make('prodi_aktif')
                        ->label('Pilih Semester')
                        ->options(ProdiModel::pluck('nama', 'id'))
                        ->default($this->prodi_aktif),
                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('Simpan')
                            ->action('saveProdi')
                    ])
                ]),


        ];
    }

    public function saveProdi()
    {

        $user = User::where('id', Auth::id())->first();
        $user->prodi_id = $this->prodi_aktif;
        $user->save();

        session([
            'prodi_id' => $user->prodi->id,
            'prodi_nama' => $user->prodi->nama,
        ]);

        Notification::make()
            ->success()
            ->title('Pengaturan Berhasil Disimpan')
            ->body('Program Studi aktif telah diperbarui.')
            ->send();

        return redirect(request()->header('Referer'));
    }
}
