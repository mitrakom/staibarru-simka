<?php

namespace App\Filament\Admin\Resources\KelasResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengampusRelationManager extends RelationManager
{
    protected static string $relationship = 'pengampus';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('dosen_id')
                    ->relationship('dosen', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('dosen_id')
            ->columns([
                Tables\Columns\TextColumn::make('dosen.nidn')
                    ->label('NIDN'),
                Tables\Columns\TextColumn::make('dosen.nama')
                    ->label('Nama Dosen'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->paginated(false)
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ])
        ;
    }
}
