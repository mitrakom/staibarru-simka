<?php

namespace App\Filament\Admin\Clusters\Referensi\Resources;

use App\Filament\Admin\Clusters\Referensi;
use App\Filament\Admin\Clusters\Referensi\Resources\RefAgamaResource\Pages;
use App\Filament\Admin\Clusters\Referensi\Resources\RefAgamaResource\RelationManagers;
use App\Models\RefAgama;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RefAgamaResource extends Resource
{
    protected static ?string $model = RefAgama::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Agama';
    protected static ?string $pluralModelLabel = 'Data Agama';

    protected static ?string $cluster = Referensi::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode')
                    ->required()
                    ->maxLength(1),
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(25),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRefAgamas::route('/'),
        ];
    }
}
