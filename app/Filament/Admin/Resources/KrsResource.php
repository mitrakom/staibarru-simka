<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KrsResource\Pages;
use App\Filament\Admin\Resources\KrsResource\RelationManagers;
use App\Models\Krs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KrsResource extends Resource
{
    protected static ?string $model = Krs::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $navigationLabel = 'KRS';
    protected static ?string $pluralModelLabel = 'Data KRS';
    protected static ?int $navigationSort = 50;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('mahasiswa_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('matakuliah_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('semester')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('kelas_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('verifikasi')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('matakuliah.kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('matakuliah.nama')
                    ->label('Matakuliah')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('matakuliah.sks')
                    ->label('SKS')
                    ->sortable(),


                Tables\Columns\TextColumn::make('semester')
                    // ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('kelas_id')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('verifikasi'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('mahasiswa_id')
                    ->label('Nama Mahasiswa')
                    ->relationship('mahasiswa', 'nama')
                    ->searchable()
                    ->preload(false)
                    ->getSearchResultsUsing(
                        fn(string $search): array =>
                        \App\Models\Mahasiswa::where('nama', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('nama', 'id')
                            ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('matakuliah_id')
                    ->label('Matakuliah')
                    ->relationship('matakuliah', 'nama')
                    ->searchable()
                    ->preload(false)
                    ->getSearchResultsUsing(
                        fn(string $search): array =>
                        \App\Models\Matakuliah::where('nama', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('nama', 'id')
                            ->toArray()
                    ),
                Tables\Filters\SelectFilter::make('semester')
                    ->label('Semester')
                    ->options([
                        1 => 'Semester 1',
                        2 => 'Semester 2',
                        3 => 'Semester 3',
                        4 => 'Semester 4',
                        5 => 'Semester 5',
                        6 => 'Semester 6',
                        7 => 'Semester 7',
                        8 => 'Semester 8',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListKrs::route('/'),
            'create' => Pages\CreateKrs::route('/create'),
            'edit' => Pages\EditKrs::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $query->whereHas('mahasiswa', fn($q) => $q->where('prodi_id', session('prodi_id')))
            ->where('semester', session('semester'));
        return $query;
    }
}
