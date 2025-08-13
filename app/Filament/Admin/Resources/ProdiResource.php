<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProdiResource\Pages;
use App\Filament\Admin\Resources\ProdiResource\RelationManagers;
use App\Models\Prodi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdiResource extends Resource
{
    protected static ?string $model = Prodi::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Prodi';
    protected static ?string $pluralModelLabel = 'Data Prodi';
    protected static ?int $navigationSort = 22;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Program Studi')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('jenjang_pendidikan_id')
                            ->label('Jenjang Pendidikan')
                            ->options(\App\Models\RefJenjang::pluck('nama', 'id'))
                            ->required(),
                        Forms\Components\TextInput::make('telepon')
                            ->tel()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Fieldset::make('Kaprodi')
                            ->schema([
                                Forms\Components\TextInput::make('kaprodi_nama')
                                    ->label('Nama')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('kaprodi_nidn')
                                    ->label('NIDN')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('fakultas.nama')
                //     ->numeric()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Prodi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kaprodi_nama')
                    ->label('Nama Kaprodi')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('jenjang.nama')
                //     ->label('Jenjang')
                //     ->searchable()
                //     ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProdis::route('/'),
            'create' => Pages\CreateProdi::route('/create'),
            'edit' => Pages\EditProdi::route('/{record}/edit'),
        ];
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->where('periode_id', Auth::user()->periode_id)->where('user_id', Auth::user()->id);
    // }

}
