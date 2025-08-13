<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PerguruanTinggiResource\Pages;
use App\Filament\Admin\Resources\PerguruanTinggiResource\RelationManagers;
use App\Models\PerguruanTinggi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Livewire\wrap;

class PerguruanTinggiResource extends Resource
{
    protected static ?string $model = PerguruanTinggi::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Perguruan Tinggi';
    protected static ?string $pluralModelLabel = 'Data Perguruan Tinggi';
    protected static ?int $navigationSort = 21;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(55),
                Forms\Components\TextInput::make('jalan')
                    ->required()
                    ->maxLength(155),
                Forms\Components\TextInput::make('telepon')
                    ->tel()
                    ->required()
                    ->maxLength(25),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(25),
                Forms\Components\TextInput::make('website')
                    ->required()
                    ->maxLength(35),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->wrap()
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ])
        ;
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
            'index' => Pages\ListPerguruanTinggis::route('/'),
            // 'create' => Pages\CreatePerguruanTinggi::route('/create'),
            'edit' => Pages\EditPerguruanTinggi::route('/{record}/edit'),
        ];
    }
}
