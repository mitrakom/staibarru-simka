<?php

namespace App\Filament\Mahasiswa\Resources\KrsResource\Pages;

use App\Filament\Mahasiswa\Resources\KrsResource;
use Filament\Resources\Pages\Page;

use App\Models\Mahasiswa;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
// use Filament\Tables\Table;


use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Krs;
use App\Models\KurikulumMatakuliah;
use App\Models\Matakuliah;
use App\Models\Setting;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;
use Filament\Actions;
use Illuminate\Database\Eloquent\Collection;

use Filament\Actions\Action;

class AddKrs extends Page implements HasTable
{
    use InteractsWithTable;
    public $semesterAktif;

    protected static string $resource = KrsResource::class;

    protected static string $view = 'filament.mahasiswa.resources.krs-resource.pages.add-krs';
    protected ?string $heading = 'Tambah Mata Kuliah';
    public $selectedMatakuliah = [];


    // Tambahkan metode ini untuk menambahkan tombol kembali pada header
    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\Action::make('kembali')
    //             ->label('Kembali')
    //             ->url(KrsResource::getUrl('index'))
    //             ->icon('heroicon-o-arrow-left')
    //             ->color('secondary'),
    //     ];
    // }

    // buat construct untuk init nilai semesterAktif dari setting
    public function __construct()
    {
        $this->semesterAktif = Setting::get('krs_online_semester', 'Tidak Diketahui');
    }

    protected function getHeaderActions(): array
    {

        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->url(KrsResource::getUrl('index')),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $semesterList = array_keys($this->getListSemester());
        // dd($semesterList);
        // dd(Auth::user()->identity->kurikulum_id);

        return KurikulumMatakuliah::query()
            // ->whereHas('kurikulum', function ($query) {
            //     $query->where('id', Auth::user()->identity->kurikulum_id);
            // })
            // ->whereDoesntHave('krs', function ($query) {
            //     $query->where('mahasiswa_id', Auth::user()->identity->id)
            //         ->where('semester', Setting::get('krs_online_semester'));;
            // })

            ->whereHas('matakuliah', function ($query) {
                $query->whereDoesntHave('krs', function ($query) {
                    $query->where('mahasiswa_id', Auth::user()->identity->id)
                        ->where('semester', Setting::get('krs_online_semester'));
                });
            })

            ->where('kurikulum_id', Auth::user()->identity->kurikulum_id)
            ->wherein('semester_ke', $semesterList)
            ->orderBy('semester_ke')
            // TODO order by kode matakuliah
        ;
    }

    protected function getTableColumns(): array
    {


        return [
            Tables\Columns\TextColumn::make('matakuliah.kode')
                ->label('Kode')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('matakuliah.nama')
                ->label('Nama Matakuliah')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('matakuliah.sks')
                ->label('SKS')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('semester_ke')
                ->label('Semester')
                ->sortable()
                ->searchable(),

        ];
    }

    protected function getTableFilters(): array
    {

        return [
            Tables\Filters\SelectFilter::make('semester')
                ->label('Semester')
                ->options($this->getListSemester())->query(function (Builder $query, array $data) {
                    $value = $data['value'] ?? null;
                    if ($value) {
                        $query->where('semester', $value);
                    }
                }),
        ];
    }

    public function table(Table $table): Table
    {

        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns())
            ->filters($this->getTableFilters())
            ->bulkActions([
                Tables\Actions\BulkAction::make('tambahKeKRS')
                    ->label('Tambah Ke KRS')
                    ->action(function (Collection $records) {

                        $isKrsOnlineActive = Setting::isKrsOnlineActive();
                        if (!$isKrsOnlineActive) {
                            Notification::make()
                                ->title('KRS Online belum dibuka')
                                ->body('Silakan hubungi bagian akademik untuk informasi lebih lanjut')
                                ->danger()
                                ->send();
                            return;
                        }

                        $userId = Auth::user()->identity->id;
                        // $smtAktif = Setting::get('krs_online_semester');

                        foreach ($records as $matakuliah) {
                            // $exists = Krs::where('mahasiswa_id', $userId)
                            //     ->where('matakuliah_id', $matakuliah->id)
                            //     ->where('semester', $this->semesterAktif)
                            //     ->exists();

                            // if (!$exists) {
                            Krs::create([
                                'mahasiswa_id' => $userId,
                                'matakuliah_id' => $matakuliah->matakuliah->id,
                                'semester' => $this->semesterAktif,
                            ]);
                            // }
                        }

                        Notification::make()
                            ->title('Matakuliah berhasil ditambahkan')
                            ->success()
                            ->send();

                        $this->redirect($this->getResource()::getUrl('index'));
                    })
                    ->deselectRecordsAfterCompletion()
                    ->icon('heroicon-o-plus')
                    ->color('primary'),
            ])
            ->paginated(false)
            ->striped();
    }

    // format semester adalah tahun + semester, contoh 20241
    // 2024 adalah tahun, 1 adalah semester ganjil
    // 20242 adalah tahun, 2 adalah semester genap
    // 20243 adalah tahun, 3 adalah semester pendek

    private function getListSemester(): array
    {
        $semesterType = substr($this->semesterAktif, -1);

        $ganjil = [1, 3, 5, 7];
        $genap = [2, 4, 6, 8];
        $pendek = [1, 2, 3, 4, 5, 6, 7, 8];

        return match ($semesterType) {
            '1' => array_combine($ganjil, array_map(fn($s) => "Semester $s", $ganjil)),
            '2' => array_combine($genap, array_map(fn($s) => "Semester $s", $genap)),
            '3' => array_combine($pendek, array_map(fn($s) => "Semester $s", $pendek)),
            default => [],
        };
    }

    // private function getListSemester()
    // {

    //     $semesterAktif = Setting::get('krs_online_semester', 'Tidak Diketahui');

    //     $semesterType = substr($semesterAktif, -1);

    //     $options = match ($semesterType) {
    //         '1' => [
    //             1 => 'Semester 1',
    //             3 => 'Semester 3',
    //             5 => 'Semester 5',
    //             7 => 'Semester 7',
    //         ],
    //         '2' => [
    //             2 => 'Semester 2',
    //             4 => 'Semester 4',
    //             6 => 'Semester 6',
    //             8 => 'Semester 8',
    //         ],
    //         '3' => [
    //             1 => 'Semester 1',
    //             2 => 'Semester 2',
    //             3 => 'Semester 3',
    //             4 => 'Semester 4',
    //             5 => 'Semester 5',
    //             6 => 'Semester 6',
    //             7 => 'Semester 7',
    //             8 => 'Semester 8',
    //         ],
    //         default => [],
    //     };

    //     return $options;
    // }
}
