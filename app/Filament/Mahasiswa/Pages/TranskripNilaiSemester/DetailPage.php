<?php

namespace App\Filament\Mahasiswa\Pages\TranskripNilaiSemester;

use Filament\Pages\Page;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Krs;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class DetailPage extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.mahasiswa.pages.transkrip-nilai-semester.detail-page';
    // protected static ?string $navigationLabel = 'Transkrip Nilai';
    protected ?string $heading = 'Transkrip Nilai';
    protected ?string $subheading = 'Detail Transkrip Nilai Semester';
    public $listNilai = [];

    // Menonaktifkan navigasi
    protected static bool $shouldRegisterNavigation = false;


    public function mount()
    {
        $this->listNilai =  $this->getQuery()->get();
    }

    // getHeader Actions back to ListPage
    protected function getHeaderActions(): array
    {
        return [
            Action::make('kembali')
                ->label('Kembali')
                ->url(route('filament.mahasiswa.pages.list-page'))
                ->icon('heroicon-o-arrow-left')
                ->color('primary'),
        ];
    }

    public function getQuery(): Builder
    {
        return Krs::query()
            ->where('mahasiswa_id', Auth::user()->identity->id)
            ->whereHas('nilai')
            ->where('semester', request()->query('semester'))
            ->with('matakuliah');
    }

    // 🔹 Method untuk menghitung total SKS
    public function getTotalSks()
    {
        return $this->getQuery()->get()
            ->sum(fn($record) => $record->matakuliah->sks);
    }

    // 🔹 Method untuk menghitung total nilai (SKS × Index)
    public function getTotalJumlah()
    {
        return $this->getQuery()->get()
            ->sum(fn($record) => $record->matakuliah->sks * ($record->nilai->index ?? 0));
    }

    // 🔹 Method untuk menghitung Indeks Prestasi Semester (IPS)
    public function getIps()
    {
        $totalSks = $this->getTotalSks();
        $totalJumlah = $this->getTotalJumlah();

        return $totalSks > 0 ? round($totalJumlah / $totalSks, 2) : 0;
    }
}
