<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MatakuliahResource\Pages;
use App\Models\Matakuliah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MatakuliahResource extends Resource
{
    protected static ?string $model = Matakuliah::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Matakuliah';
    protected static ?string $pluralModelLabel = 'Data Matakuliah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(55),
                Forms\Components\Select::make('jenis_matakuliah_id')
                    ->label('Jenis Matakuliah')
                    ->options([
                        'A' => 'Wajib',
                        'B' => 'Pilihan',
                        'C' => 'Wajib Peminatan',
                        'D' => 'Pilihan Peminatan',
                        'S' => 'Tugas Akhir, Skripsi, Thesis, Disertasi',
                    ])
                    ->required(),
                Forms\Components\Select::make('kelompok_matakuliah_id')
                    ->label('Kelompok Matakuliah')
                    ->options([
                        'A' => 'MKP',
                        'B' => 'MKK',
                        'C' => 'MKB',
                        'D' => 'MPB',
                        'E' => 'MBB',
                        'F' => 'MKU/MKDU',
                        'G' => 'MKDK',
                        'H' => 'MKK',
                    ])
                    ->required(),

                Forms\Components\Fieldset::make('SKS')
                    ->label('Jumlah SKS')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('sks')
                            ->label('Matakuliah')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('sks_tatap_muka')
                            ->label('Tatap Muka')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('sks_praktek')
                            ->label('Praktek')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('sks_praktek_lapangan')
                            ->label('Praktek Lapangan')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('sks_simulasi')
                            ->label('Simulasi')
                            ->required()
                            ->numeric(),
                    ]),

                Forms\Components\Fieldset::make('Dokumen')
                    ->label('Kelengkapan Matakuliah')
                    ->columns(5)
                    ->schema([
                        Forms\Components\Toggle::make('ada_sap')
                            ->label('SAP')
                            ->default(false),
                        Forms\Components\Toggle::make('ada_silabus')
                            ->label('Silabus')
                            ->default(false),
                        Forms\Components\Toggle::make('ada_bahan_ajar')
                            ->label('Bahan Ajar')
                            ->default(false),
                        Forms\Components\Toggle::make('ada_acara_praktek')
                            ->label('Acara Praktek')
                            ->default(false),
                        Forms\Components\Toggle::make('ada_diktat')
                            ->label('Diktat')
                            ->default(false),
                    ]),

                Forms\Components\TextInput::make('metode_kuliah')
                    ->columnSpanFull()
                    ->maxLength(55),
                Forms\Components\DatePicker::make('tanggal_mulai_efektif'),
                Forms\Components\DatePicker::make('tanggal_selesai_efektif'),
                // Forms\Components\TextInput::make('apakah_wajib')
                //     ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sks')
                    ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('jenis_matakuliah_id'),
                // Tables\Columns\TextColumn::make('kelompok_matakuliah_id'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
        ;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMatakuliahs::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('prodi_id', session('prodi_id'));
    }
}
