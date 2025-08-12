<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DosenResource\Pages;
use App\Filament\Admin\Resources\DosenResource\RelationManagers;
use App\Models\Dosen;
use App\Models\RefAgama;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;

class DosenResource extends Resource
{
    protected static ?string $model = Dosen::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Dosen';
    protected static ?string $pluralModelLabel = 'Data Dosen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nidn')
                    ->label('NIDN')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nuptk')
                    ->label('NUPTK')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                // Forms\Components\TextInput::make('handphone')
                //     ->tel()
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('email')
                //     ->email()
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('alamat')
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('gelar_depan')
                //     // ->required()
                //     ->maxLength(255),
                // Forms\Components\TextInput::make('gelar_belakang')
                //     ->required()
                //     ->maxLength(255),
                // Forms\Components\select::make('status')
                //     ->label('Status')
                //     ->options([
                //         'Aktif' => 'Aktif',
                //         'Tidak Aktif' => 'Tidak Aktif',
                //     ])
                //     ->required(),
                // Forms\Components\Select::make('jenis_kelamin')
                //     ->label('Jenis Kelamin')
                //     ->options([
                //         'L' => 'Laki-laki',
                //         'P' => 'Perempuan',
                //     ])
                //     ->required(),
                // Forms\Components\DatePicker::make('tanggal_lahir')
                //     ->required(),
                // Forms\Components\TextInput::make('tempat_lahir')
                //     ->required()
                //     ->maxLength(255),
                // // Forms\Components\TextInput::make('foto')
                // //     ->required()
                // //     ->maxLength(255),
                // Forms\Components\FileUpload::make('foto')
                //     // ->required()
                //     ->image(),


                // Forms\Components\Select::make('agama')
                //     ->label('Agama')
                //     ->options([
                //         'I' => 'Islam',
                //         'P' => 'Protestan',
                //         'K' => 'Katolik',
                //         'H' => 'Hindu',
                //         'B' => 'Buddha',
                //         'C' => 'Konghuchu',
                //     ])
                //     ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nidn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nuptk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('jenis_kelamin'),
                // Tables\Columns\TextColumn::make('tanggal_lahir')
                //     ->date()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('tempat_lahir')
                //     ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
