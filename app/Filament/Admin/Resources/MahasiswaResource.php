<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MahasiswaResource\Pages;
use App\Filament\Admin\Resources\MahasiswaResource\RelationManagers;
use App\Models\Kurikulum;
use App\Models\Mahasiswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Mahasiswa';
    protected static ?string $pluralModelLabel = 'Data Mahasiswa';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Hidden::make('prodi_id')
                //     ->default(fn() => session('prodi_id')),
                Forms\Components\Section::make('Data Akademik')
                    ->description('Informasi akademik mahasiswa')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nim')
                                    ->label('NIM')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('angkatan')
                                    ->label('Angkatan')
                                    ->required()
                                    ->minLength(4)
                                    ->maxLength(4)
                                    ->numeric(),
                                Forms\Components\Select::make('kurikulum_id')
                                    ->label('Kurikulum')
                                    ->options(Kurikulum::all()->where('prodi_id', session('prodi_id'))->pluck('nama', 'id'))
                                    ->required(),
                            ])
                    ]),

                Forms\Components\Section::make('Data Pribadi')
                    ->description('Identitas dan data pribadi mahasiswa')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(155),
                                Forms\Components\Select::make('jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('agama')
                                    ->label('Agama')
                                    ->options([
                                        'I' => 'Islam',
                                        'P' => 'Protestan',
                                        'K' => 'Katolik',
                                        'H' => 'Hindu',
                                        'B' => 'Buddha',
                                        'C' => 'Khonghucu',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->required()
                                    ->maxLength(55),
                                Forms\Components\DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->required()
                                    ->default(now()->subYears(14)),
                            ])
                    ]),

                Forms\Components\Section::make('Data Kontak & Identitas')
                    ->description('Kontak dan identitas resmi mahasiswa')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(55),
                                Forms\Components\TextInput::make('handphone')
                                    ->label('No. Handphone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(55),
                                Forms\Components\TextInput::make('alamat')
                                    ->label('Alamat')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nisn')
                                    ->label('NISN')
                                    ->maxLength(25),
                                Forms\Components\TextInput::make('nik')
                                    ->label('NIK')
                                    ->maxLength(25),
                                Forms\Components\TextInput::make('npwp')
                                    ->label('NPWP')
                                    ->maxLength(25),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('nim')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('angkatan'),
                Tables\Columns\TextColumn::make('jenis_kelamin'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('nim', 'desc')
        ;
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
            'index' => Pages\ListMahasiswas::route('/'),
            'create' => Pages\CreateMahasiswa::route('/create'),
            'edit' => Pages\EditMahasiswa::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('prodi_id', session('prodi_id'));
    }
}
