<?php

namespace App\Filament\Admin\Resources;

// use App\Filament\Admin\Clusters\settings\Pages\Semester;
use App\Filament\Admin\Resources\PenasehatAkademikResource\Pages;
use App\Filament\Admin\Resources\PenasehatAkademikResource\RelationManagers;
use App\Models\PenasehatAkademik;
use App\Models\RefSemester;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenasehatAkademikResource extends Resource
{
    protected static ?string $model = PenasehatAkademik::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $navigationLabel = 'Penasehat Akademik';
    protected static ?string $pluralModelLabel = 'Data Penasehat Akademik';
    protected static ?int $navigationSort = 53;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('semester')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('dosen.nama')
                    ->label('Nama Dosen')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('semester')
                    ->options(RefSemester::OrderBy('key', 'desc')->pluck('value', 'key')),
                SelectFilter::make('dosen')
                    ->relationship('dosen', 'nama'),
                SelectFilter::make('mahasiswa')
                    ->relationship('mahasiswa', 'nama'),
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
            'index' => Pages\ListPenasehatAkademiks::route('/'),
            'create' => Pages\CreatePenasehatAkademik::route('/create'),
            'edit' => Pages\EditPenasehatAkademik::route('/{record}/edit'),
        ];
    }
}
