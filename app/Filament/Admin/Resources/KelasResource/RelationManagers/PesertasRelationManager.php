<?php

namespace App\Filament\Admin\Resources\KelasResource\RelationManagers;

use App\Models\Krs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PesertasRelationManager extends RelationManager
{
    protected static string $relationship = 'pesertas';

    // protected function getTableHeaderActions(): array
    // {
    //     return [
    //         Tables\Actions\Action::make('addMahasiswa')
    //             ->label('Tambah Mahasiswa')
    //             ->icon('heroicon-o-plus-circle')
    //             ->modalHeading('Tambah Mahasiswa ke Kelas')
    //             ->modalDescription('Pilih mahasiswa yang akan ditambahkan ke kelas ini.')
    //             ->action(function (array $data): void {
    //                 foreach ($data['mahasiswas'] as $krsId) {
    //                     // Periksa apakah mahasiswa sudah terdaftar di kelas ini
    //                     $exists = $this->ownerRecord->pesertas()
    //                         ->where('krs_id', $krsId)
    //                         ->exists();

    //                     if (!$exists) {
    //                         $this->ownerRecord->pesertas()->create([
    //                             'krs_id' => $krsId,
    //                         ]);
    //                     }
    //                 }
    //             })
    //             ->form([
    //                 Forms\Components\CheckboxList::make('mahasiswas')
    //                     ->label('Pilih Mahasiswa')
    //                     ->options(function () {
    //                         // Ambil daftar KRS yang sesuai dengan matakuliah pada kelas ini
    //                         // dan belum terdaftar di kelas ini
    //                         $existingKrsIds = $this->ownerRecord->pesertas()->pluck('krs_id')->toArray();

    //                         return Krs::query()
    //                             ->where('matakuliah_id', $this->ownerRecord->matakuliah_id)
    //                             ->whereNotIn('id', $existingKrsIds)
    //                             ->join('mahasiswas', 'krss.mahasiswa_id', '=', 'mahasiswas.id')
    //                             ->select('krss.id', 'mahasiswas.nim', 'mahasiswas.nama')
    //                             ->get()
    //                             ->mapWithKeys(function ($krs) {
    //                                 return [$krs->id => $krs->nim . ' - ' . $krs->nama];
    //                             });
    //                     })
    //                     ->searchable()
    //                     ->bulkToggleable()
    //                     ->gridDirection('column')
    //                     ->columns(1)
    //                     ->required(),
    //             ])
    //             ->modalWidth('lg'),

    //         // Alternatif menggunakan tampilan tabel
    //         // Tables\Actions\Action::make('addMahasiswasWithTable')
    //         //     ->label('Tambah Mahasiswa (Tabel)')
    //         //     ->icon('heroicon-o-table')
    //         //     ->modalHeading('Tambah Mahasiswa ke Kelas')
    //         //     ->modalDescription('Pilih mahasiswa dari tabel berikut.')
    //         //     ->modalContent(function (): string {
    //         //         $existingKrsIds = $this->ownerRecord->pesertas()->pluck('krs_id')->toArray();

    //         //         $mahasiswas = Krs::query()
    //         //             ->where('matakuliah_id', $this->ownerRecord->matakuliah_id)
    //         //             ->whereNotIn('id', $existingKrsIds)
    //         //             ->join('mahasiswas', 'krss.mahasiswa_id', '=', 'mahasiswas.id')
    //         //             ->select('krss.id as krs_id', 'mahasiswas.nim', 'mahasiswas.nama')
    //         //             ->get();

    //         //         return view('filament.admin.resources.kelas-resource.relation-managers.mahasiswa-selection-table', [
    //         //             'mahasiswas' => $mahasiswas,
    //         //         ])->render();
    //         //     })
    //         //     ->action(function (array $data): void {
    //         //         if (!isset($data['selected_krs_ids']) || empty($data['selected_krs_ids'])) {
    //         //             return;
    //         //         }

    //         //         foreach ($data['selected_krs_ids'] as $krsId) {
    //         //             // Periksa apakah mahasiswa sudah terdaftar di kelas ini
    //         //             $exists = $this->ownerRecord->pesertas()
    //         //                 ->where('krs_id', $krsId)
    //         //                 ->exists();

    //         //             if (!$exists) {
    //         //                 $this->ownerRecord->pesertas()->create([
    //         //                     'krs_id' => $krsId,
    //         //                 ]);
    //         //             }
    //         //         }
    //         //     })
    //         //     ->form([
    //         //         Forms\Components\Hidden::make('selected_krs_ids')
    //         //             ->default([]),
    //         //     ])
    //         //     ->modalWidth('xl'),
    //     ];
    // }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('krs_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('krs_id')
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa_nim')
                    // ->sortable()
                    ->label('NIM'),
                Tables\Columns\TextColumn::make('mahasiswa_nama')
                    ->label('Nama'),
            ])
            ->filters([
                //
            ])
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ])

            ->headerActions([
                // PENTING: Method headerActions() yang benar di Filament v3
                Tables\Actions\Action::make('addMahasiswa')
                    ->label('Tambah Mahasiswa')
                    ->icon('heroicon-o-plus-circle')
                    ->modalHeading('Tambah Mahasiswa ke Kelas')
                    ->modalDescription('Pilih mahasiswa yang akan ditambahkan ke kelas ini.')
                    ->action(function (array $data): void {
                        foreach ($data['mahasiswas'] as $krsId) {
                            // Periksa apakah mahasiswa sudah terdaftar di kelas ini
                            $exists = $this->ownerRecord->pesertas()
                                ->where('krs_id', $krsId)
                                ->exists();

                            if (!$exists) {
                                $this->ownerRecord->pesertas()->create([
                                    'krs_id' => $krsId,
                                ]);
                            }
                        }
                    })
                    ->form([
                        Forms\Components\CheckboxList::make('mahasiswas')
                            ->label('Pilih Mahasiswa')
                            ->options(function () {
                                // Ambil daftar KRS yang sesuai dengan matakuliah pada kelas ini
                                // dan belum terdaftar di kelas ini
                                $existingKrsIds = $this->ownerRecord->pesertas()->pluck('krs_id')->toArray();

                                return Krs::query()
                                    ->where('krss.matakuliah_id', $this->ownerRecord->matakuliah_id)
                                    ->whereNotIn('krss.id', $existingKrsIds)
                                    ->join('mahasiswas', 'krss.mahasiswa_id', '=', 'mahasiswas.id')
                                    ->select('krss.id', 'mahasiswas.nim', 'mahasiswas.nama')
                                    ->get()
                                    ->mapWithKeys(function ($krs) {
                                        return [$krs->id => $krs->nim . ' - ' . $krs->nama];
                                    });
                            })
                            ->searchable()
                            ->bulkToggleable()
                            ->gridDirection('column')
                            ->columns(1)
                            ->required(),
                    ])
                    ->modalWidth('lg'),
            ])

            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query
                    ->join('krss', 'kelas_pesertas.krs_id', '=', 'krss.id')
                    ->join('mahasiswas', 'krss.mahasiswa_id', '=', 'mahasiswas.id')
                    ->select('kelas_pesertas.*')
                    ->orderBy('mahasiswas.nim');
            })
            ->paginated(false)
        ;
    }
}
