<?php

namespace App\Filament\Admin\Clusters\settings\Pages;

use App\Filament\Admin\Clusters\settings;
use Filament\Pages\Page;

use Illuminate\Support\Facades\Auth;
use Filament\Forms;
use App\Models\RefSemester;
use App\Models\User;
use Filament\Notifications\Notification;

class Semester extends Page
{
    public $semester_aktif;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.clusters.settings.pages.semester';

    protected static ?string $cluster = settings::class;

    public function mount()
    {
        $this->semester_aktif = User::where('id', Auth::id())->first()->semester;
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Semester Aktif')
                ->label('Pilih Semester')
                ->schema([
                    Forms\Components\Select::make('semester_aktif')
                        ->label('Pilih Semester')
                        ->options(RefSemester::pluck('value', 'key'))
                        ->default($this->semester_aktif),
                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('Simpan')
                            ->action('save')
                    ])
                ]),

        ];
    }

    public function save()
    {
        $user = User::where('id', Auth::id())->first();
        $user->semester = $this->semester_aktif;
        $user->save();

        session([
            'semester' => $this->semester_aktif
        ]);

        Notification::make()
            ->success()
            ->title('Pengaturan Berhasil Disimpan')
            ->body('Semester aktif telah diperbarui.')
            ->send();

        return redirect(request()->header('Referer'));
    }
}
