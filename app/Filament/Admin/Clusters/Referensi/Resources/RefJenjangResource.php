<?php

namespace App\Filament\Admin\Clusters\Referensi\Resources;

use App\Filament\Admin\Clusters\Referensi;
use App\Filament\Admin\Clusters\Referensi\Resources\RefJenjangResource\Pages;
use App\Filament\Admin\Clusters\Referensi\Resources\RefJenjangResource\RelationManagers;
use App\Models\RefJenjang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RefJenjangResource extends Resource
{
    protected static ?string $model = RefJenjang::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Jenjang';
    protected static ?string $pluralModelLabel = 'Data Jenjang';

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
                // Forms\Components\TextInput::make('status')
                //     ->maxLength(255),
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
                // Tables\Columns\TextColumn::make('status')
                //     ->searchable(),
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
            'index' => Pages\ManageRefJenjangs::route('/'),
        ];
    }
}
