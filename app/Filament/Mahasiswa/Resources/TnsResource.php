<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\TnsResource\Pages;
use App\Filament\Mahasiswa\Resources\TnsResource\RelationManagers;
use App\Models\Krs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TnsResource extends Resource
{
    protected static ?string $model = Krs::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Transkrip Nilai Sementara';
    protected static ?string $navigationLabel = 'TNS';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('semester')
                    // ->label('Kode')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListTns::route('/'),
            // 'create' => Pages\CreateTns::route('/create'),
            // 'edit' => Pages\EditTns::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->select('semester', 'id')
            ->groupBy('matakuliah_id', 'semester', 'kelas_id', 'verifikasi', 'created_at', 'updated_at', 'id')
            ->where('mahasiswa_id', Auth::user()->identity->id);
    }
}
