<?php

namespace App\Filament\Admin\Resources\KelasResource\Pages;

use App\Filament\Admin\Resources\KelasResource;
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
use App\Models\Matakuliah;
use App\Models\Setting;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;
use Filament\Actions;
use Illuminate\Database\Eloquent\Collection;

use Filament\Actions\Action;


class ListRekapMatakuliahPeserta extends Page implements HasTable
{

    use InteractsWithTable;


    protected static string $resource = KelasResource::class;

    protected static string $view = 'filament.admin.resources.kelas-resource.pages.list-rekap-matakuliah-peserta';
    protected ?string $heading = 'Tambah Mata Kuliah';

    protected function getHeaderActions(): array
    {

        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    protected function getTableQuery(): Builder
    {
        // $semesterList = array_keys($this->getListSemester());

        // return Matakuliah::query()
        //     // ->whereDoesntHave('krs', function ($query) {
        //     //     $query->where('mahasiswa_id', Auth::user()->identity->id)
        //     //         ->where('semester', Setting::get('krs_online_semester'));;
        //     // })
        //     // ->where('prodi_id', Auth::user()->identity->prodi_id)
        //     // ->where('kurikulum_id', Auth::user()->identity->kurikulum_id)
        //     // ->wherein('semester', $semesterList)
        //     // ->orderBy('semester')
        //     ->orderBy('kode')

        return Matakuliah::query()
            ->whereHas('krs', function ($query) {
                $query->whereHas('mahasiswa', function ($subQuery) {
                    $subQuery->where('prodi_id', session('prodi_id')); // Mahasiswa dengan prodi aktif
                });
            })
            ->withCount(['krs as jumlah_peserta' => function ($query) {
                $query->whereHas('mahasiswa', function ($subQuery) {
                    $subQuery->where('prodi_id', session('prodi_id')); // Mahasiswa dengan prodi aktif
                });
            }])
            ->orderBy('kode');;
    }

    protected function getTableColumns(): array
    {

        return [
            Tables\Columns\TextColumn::make('kode')
                ->label('Kode')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('nama')
                ->label('Nama Matakuliah')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('jumlah_peserta')
                ->label('Jumlah Peserta')
                ->sortable(),

        ];
    }

    public function table(Table $table): Table
    {

        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns())
            // tambahkan action tambah ke kelas dimana menampilkan modal dengan form id_matakuliah yang aktif dan nama kelas
            ->actions([
                Tables\Actions\Action::make('tambah')
                    // ->label('Tambah')
                    // ->action(function (Matakuliah $record) {
                    //     $this->redirect(KelasResource::getUrl('create', ['record' => $record->id]));
                    // })
                    ->form([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->label('Nama Kelas')
                            ->placeholder('Masukkan Nama Kelas'),
                        Forms\Components\TextInput::make('keterangan')
                            ->placeholder('Bisa di kosongkan'),

                    ])
                    ->action(function (array $data, Matakuliah $record) {
                        $kelas = \App\Models\Kelas::create([
                            'nama' => $data['nama'],
                            'matakuliah_id' => $record->id,
                            'prodi_id' => session('prodi_id'),
                            'semester' => session('semester'),
                        ]);

                        Notification::make()
                            ->title('Kelas berhasil ditambahkan')
                            ->success()
                            ->send();

                        $this->redirect(KelasResource::getUrl('edit', ['record' => $kelas->id]));
                    })
                    // ->action(function (array $data) {
                    //     \App\Models\Kelas::create([
                    //         'nama' => $data['nama'],
                    //         'matakuliah_id' => fn($record) => $record->id,
                    //         'prodi_id' => session('prodi_id'),
                    //         'semester' => session('semester'),
                    //     ]);

                    //     Notification::make()
                    //         ->title('Kelas berhasil ditambahkan')
                    //         ->success()
                    //         ->send();
                    // })

                    ->modalHeading('Tambah Kelas')
                    ->modalWidth('sm')
                    ->icon('heroicon-o-plus')
                    ->button()
                    ->color('primary'),
            ])



            // ->filters($this->getTableFilters())
            // ->bulkActions([
            //     Tables\Actions\BulkAction::make('tambahKeKRS')
            //         ->label('Tambah Ke KRS')
            //         ->action(function (Collection $records) {

            //             $isKrsOnlineActive = Setting::isKrsOnlineActive();
            //             if (!$isKrsOnlineActive) {
            //                 Notification::make()
            //                     ->title('KRS Online belum dibuka')
            //                     ->body('Silakan hubungi bagian akademik untuk informasi lebih lanjut')
            //                     ->danger()
            //                     ->send();
            //                 return;
            //             }

            //             $userId = Auth::user()->identity->id;
            //             // $smtAktif = Setting::get('krs_online_semester');

            //             foreach ($records as $matakuliah) {
            //                 $exists = Krs::where('mahasiswa_id', $userId)
            //                     ->where('matakuliah_id', $matakuliah->id)
            //                     ->where('semester', $this->semesterAktif)
            //                     ->exists();

            //                 if (!$exists) {
            //                     Krs::create([
            //                         'mahasiswa_id' => $userId,
            //                         'matakuliah_id' => $matakuliah->id,
            //                         'semester' => $this->semesterAktif,
            //                     ]);
            //                 }
            //             }

            //             Notification::make()
            //                 ->title('Matakuliah berhasil ditambahkan')
            //                 ->success()
            //                 ->send();

            //             $this->redirect($this->getResource()::getUrl('index'));
            //         })
            //         ->deselectRecordsAfterCompletion()
            //         ->icon('heroicon-o-plus')
            //         ->color('primary'),
            // ])
            ->paginated(false)
            ->striped();
    }
}
