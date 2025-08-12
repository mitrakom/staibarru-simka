<?php

namespace App\Filament\Mahasiswa\Pages\TranskripNilaiSemester;

use Filament\Pages\Page;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\RefSemester;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;

class ListPage extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.mahasiswa.pages.transkrip-nilai-semester.list-page';
    protected static ?string $navigationLabel = 'Transkrip Nilai';
    protected ?string $heading = 'Transkrip Nilai';
    protected ?string $subheading = 'List Semester';

    public $listSemester = [];

    public function mount()
    {
        $this->listSemester =  $this->getQuery()->get();
    }

    protected function getQuery(): Builder
    {
        return RefSemester::query()
            ->select('ref_semesters.key', 'ref_semesters.value', 'ref_semesters.id')
            ->join('krss', 'krss.semester', '=', 'ref_semesters.key')
            ->where('krss.mahasiswa_id', Auth::user()->identity->id)
            ->groupBy('ref_semesters.key', 'ref_semesters.value', 'ref_semesters.id')
            ->orderBy('key', 'desc');
    }
}
