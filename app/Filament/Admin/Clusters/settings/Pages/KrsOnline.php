<?php

namespace App\Filament\Admin\Clusters\settings\Pages;

use App\Filament\Admin\Clusters\settings;
use Filament\Pages\Page;
use App\Models\Setting;

use Illuminate\Support\Facades\Auth;
use Filament\Forms;
use App\Models\RefSemester;
use App\Models\User;
use Filament\Notifications\Notification;

class KrsOnline extends Page
{
    public $semester_aktif;
    public $tanggal_mulai;
    public $tanggal_selesai;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.clusters.settings.pages.krs-online';

    protected static ?string $cluster = settings::class;

    public function mount()
    {
        // $this->semester_aktif = Setting::where('key', 'krs_online_semester')->value('value');
        $this->semester_aktif = Setting::get('krs_online_semester');
        $this->tanggal_mulai = Setting::get('krs_online_start_date');
        $this->tanggal_selesai = Setting::get('krs_online_end_date');
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make()
                ->columns(2)
                ->label('Pilih Semester')
                ->schema([
                    Forms\Components\Select::make('semester_aktif')
                        ->label('Pilih Semester')
                        ->options(RefSemester::pluck('value', 'key'))
                        ->default($this->semester_aktif)
                        ->columnSpanFull()
                        ->required(),

                    Forms\Components\DatePicker::make('tanggal_mulai')
                        ->label('Tanggal Mulai')
                        ->default($this->tanggal_mulai)
                        ->required(),

                    Forms\Components\DatePicker::make('tanggal_selesai')
                        ->label('Tanggal Selesai')
                        ->default($this->tanggal_selesai)
                        ->required(),

                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('Simpan')
                            ->action('save')
                    ])
                ]),

        ];
    }

    public function save()
    {

        if ($this->tanggal_mulai > $this->tanggal_selesai) {
            Notification::make()
                ->danger()
                ->title('Gagal Menyimpan Pengaturan')
                ->body('Tanggal mulai tidak boleh lebih besar dari tanggal selesai.')
                ->send();
            return redirect(request()->header('Referer'));
        }

        Setting::set('krs_online_semester', $this->semester_aktif);
        Setting::set('krs_online_start_date', $this->tanggal_mulai);
        Setting::set('krs_online_end_date', $this->tanggal_selesai);

        Notification::make()
            ->success()
            ->title('Pengaturan Berhasil Disimpan')
            ->body('KRS Online Semester aktif telah diperbarui.')
            ->send();

        return redirect(request()->header('Referer'));
    }
}
