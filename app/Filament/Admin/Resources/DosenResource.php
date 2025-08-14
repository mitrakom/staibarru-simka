<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DosenResource\Pages;
use App\Filament\Admin\Resources\DosenResource\RelationManagers;
use App\Models\Dosen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DosenResource extends Resource
{
    protected static ?string $model = Dosen::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Dosen';
    protected static ?string $pluralModelLabel = 'Data Dosen';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pribadi')
                    ->description('Identitas utama dosen')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->maxLength(32),
                                Forms\Components\DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir'),
                                Forms\Components\Select::make('jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->options(['L' => 'Laki-laki', 'P' => 'Perempuan']),
                                Forms\Components\Select::make('agama_id')
                                    ->label('Agama')
                                    ->options(['1' => 'Islam', '2' => 'Kristen', '3' => 'Hindu', '4' => 'Buddha']),
                                Forms\Components\Select::make('status_aktif_id')
                                    ->label('Status Aktif')
                                    ->options(['1' => 'Aktif', '2' => 'Tidak Aktif']),
                                Forms\Components\TextInput::make('nidn')
                                    ->label('NIDN')
                                    ->maxLength(10),
                                Forms\Components\TextInput::make('nuptk')
                                    ->label('NUPTK')
                                    ->maxLength(35),
                                Forms\Components\TextInput::make('nama_ibu_kandung')
                                    ->label('Nama Ibu Kandung')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('nik')
                                    ->label('NIK')
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('nip')
                                    ->label('NIP')
                                    ->maxLength(18),
                                Forms\Components\TextInput::make('npwp')
                                    ->label('NPWP')
                                    ->maxLength(15),
                                Forms\Components\Select::make('jenis_sdm_id')
                                    ->label('Jenis SDM')
                                    ->options(['1' => 'Dosen Tetap', '2' => 'Dosen Tidak Tetap']),
                            ])
                    ]),

                Forms\Components\Section::make('Data Kepegawaian')
                    ->description('Informasi kepegawaian dan pengangkatan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('no_sk_cpns')
                                    ->label('No SK CPNS')
                                    ->maxLength(80),
                                Forms\Components\DatePicker::make('tanggal_sk_cpns')
                                    ->label('Tanggal SK CPNS'),
                                Forms\Components\TextInput::make('no_sk_pengangkatan')
                                    ->label('No SK Pengangkatan')
                                    ->maxLength(80),
                                Forms\Components\DatePicker::make('mulai_sk_pengangkatan')
                                    ->label('Mulai SK Pengangkatan'),
                                Forms\Components\Select::make('lembaga_pengangkatan_id')
                                    ->label('Lembaga Pengangkatan')
                                    ->options(['1' => 'Yayasan', '2' => 'Kemenag', '3' => 'Kemendikbud']),
                                Forms\Components\Select::make('pangkat_golongan_id')
                                    ->label('Pangkat/Golongan')
                                    ->options(['1' => 'III/a', '2' => 'III/b', '3' => 'IV/a']),
                                Forms\Components\Select::make('sumber_gaji_id')
                                    ->label('Sumber Gaji')
                                    ->options(['1' => 'Yayasan', '2' => 'Pemerintah', '3' => 'Swasta']),
                            ])
                    ]),

                Forms\Components\Section::make('Data Kontak & Alamat')
                    ->description('Alamat dan kontak dosen')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('jalan')
                                    ->label('Jalan')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('dusun')
                                    ->label('Dusun')
                                    ->maxLength(60),
                                Forms\Components\TextInput::make('rt')
                                    ->label('RT')
                                    ->maxLength(5),
                                Forms\Components\TextInput::make('rw')
                                    ->label('RW')
                                    ->maxLength(5),
                                Forms\Components\TextInput::make('ds_kel')
                                    ->label('Desa/Kelurahan')
                                    ->maxLength(60),
                                Forms\Components\TextInput::make('kode_pos')
                                    ->label('Kode Pos')
                                    ->maxLength(5),
                                Forms\Components\TextInput::make('wilayah_id')
                                    ->label('Wilayah ID')
                                    ->maxLength(8),
                                Forms\Components\TextInput::make('telepon')
                                    ->label('Telepon')
                                    ->tel()
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('handphone')
                                    ->label('Handphone')
                                    ->tel()
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(60),
                            ])
                    ]),

                Forms\Components\Section::make('Data Keluarga')
                    ->description('Informasi keluarga dosen')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status_pernikahan')
                                    ->label('Status Pernikahan')
                                    ->options(['1' => 'Menikah', '2' => 'Belum Menikah']),
                                Forms\Components\TextInput::make('nama_suami_istri')
                                    ->label('Nama Suami/Istri')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('nip_suami_istri')
                                    ->label('NIP Suami/Istri')
                                    ->maxLength(18),
                                Forms\Components\DatePicker::make('tanggal_mulai_pns')
                                    ->label('Tanggal Mulai PNS'),
                                Forms\Components\Select::make('pekerjaan_suami_istri_id')
                                    ->label('Pekerjaan Suami/Istri')
                                    ->options(['1' => 'PNS', '2' => 'Wiraswasta', '3' => 'Lainnya']),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('nidn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tempat_lahir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelamin'),

                Tables\Columns\TextColumn::make('handphone')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('nidn', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDosens::route('/'),
            'create' => Pages\CreateDosen::route('/create'),
            'edit' => Pages\EditDosen::route('/{record}/edit'),
        ];
    }
}
