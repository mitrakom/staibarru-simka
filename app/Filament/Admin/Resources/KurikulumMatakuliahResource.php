<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KurikulumMatakuliahResource\Pages;
use App\Filament\Admin\Resources\KurikulumMatakuliahResource\RelationManagers;
use App\Models\KurikulumMatakuliah;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KurikulumMatakuliahResource extends Resource
{
    protected static ?string $model = KurikulumMatakuliah::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Mata Kuliah';
    protected static ?string $pluralModelLabel = 'Data Kurikulum Matakuliah';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationParentItem = 'Kurikulum';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('kurikulum_id')
                    ->relationship('kurikulum', 'nama', fn(Builder $query) => $query->where('prodi_id', session('prodi_id'))->orderBy('semester', 'desc'))
                    ->required()
                    ->preload()
                    ->searchable()
                // ->columnSpanFull()
                ,
                Forms\Components\Select::make('matakuliah_id')
                    ->relationship('matakuliah', 'nama', fn(Builder $query) => $query->where('prodi_id', session('prodi_id')))
                    ->required()
                    ->preload()
                    ->searchable()
                // ->columnSpanFull()
                ,
                Forms\Components\TextInput::make('semester_ke')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('apakah_wajib')
                    // ->label('Diktat')
                    ->default(false),

                // Forms\Components\TextInput::make('apakah_wajib')
                //     ->numeric(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kurikulum.nama')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('matakuliah.nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester_ke')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('apakah_wajib')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kurikulum_id')
                    ->relationship('kurikulum', 'nama', fn(Builder $query) => $query->where('prodi_id', session('prodi_id')))
                    ->preload()
                    ->placeholder('Semua Kurikulum'),
                SelectFilter::make('semester_ke')
                    ->options([
                        '1' => 'Semester 1',
                        '2' => 'Semester 2',
                        '3' => 'Semester 3',
                        '4' => 'Semester 4',
                        '5' => 'Semester 5',
                        '6' => 'Semester 6',
                        '7' => 'Semester 7',
                        '8' => 'Semester 8',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageKurikulumMatakuliahs::route('/'),
        ];
    }
}
