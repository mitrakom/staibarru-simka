<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KelasResource\Pages;
use App\Filament\Admin\Resources\KelasResource\RelationManagers;
use App\Filament\Admin\Resources\KelasResource\RelationManagers\PengampusRelationManager;
use App\Filament\Admin\Resources\KelasResource\RelationManagers\PesertasRelationManager;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $navigationLabel = 'Kelas';
    protected static ?string $pluralModelLabel = 'Data Kelas';

    protected static ?int $navigationSort = 51;


    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('nama')
    //                 ->required()
    //                 ->maxLength(255),
    //             Forms\Components\TextInput::make('keterangan')
    //                 ->required()
    //                 ->maxLength(255),
    //             Forms\Components\TextInput::make('semester')
    //                 ->required()
    //                 ->numeric(),
    //             Forms\Components\TextInput::make('prodi_id')
    //                 ->required()
    //                 ->numeric(),
    //             Forms\Components\TextInput::make('matakuliah_id')
    //                 ->required()
    //                 ->numeric(),
    //             Forms\Components\TextInput::make('jenis_kelas')
    //                 ->required()
    //                 ->maxLength(255),
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prodi_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('matakuliah_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            PesertasRelationManager::class,
            PengampusRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
            'rekap' => Pages\ListRekapMatakuliahPeserta::route('/rekap'),
        ];
    }
}
