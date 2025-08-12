<?php

namespace App\Filament\Admin\Clusters\Users\Resources;

use App\Filament\Admin\Clusters\Users;
use App\Filament\Admin\Clusters\Users\Resources\MahasiswaResource\Pages;
use App\Filament\Admin\Clusters\Users\Resources\MahasiswaResource\RelationManagers;
use App\Models\Mahasiswa;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MahasiswaResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Users::class;
    protected static ?string $navigationLabel = 'Mahasiswa';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('identity.nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('identity.nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('identity.prodi.nama')
                    ->label('Prodi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            'index' => Pages\ListMahasiswas::route('/'),
            'create' => Pages\CreateMahasiswa::route('/create'),
            'edit' => Pages\EditMahasiswa::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'mahasiswa');
            });
    }
}
