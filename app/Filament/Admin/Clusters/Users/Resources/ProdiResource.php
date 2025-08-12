<?php

namespace App\Filament\Admin\Clusters\Users\Resources;

use App\Filament\Admin\Clusters\Users;
use App\Filament\Admin\Clusters\Users\Resources\ProdiResource\Pages;
use App\Filament\Admin\Clusters\Users\Resources\ProdiResource\RelationManagers;
use App\Models\Prodi;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdiResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Users::class;

    // protected static ?string $navigationGroup = 'Kemiskinan';
    protected static ?string $navigationLabel = 'Prodi';
    // protected static ?string $pluralModelLabel = 'Daftar Mappadeceng Miskin';
    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(),
                Forms\Components\Select::make('identity_id')
                    ->label('Prodi')
                    ->options(function () {
                        return Prodi::pluck('nama', 'id');
                    })
                    ->required(),

                // Forms\Components\Hidden::make('identity_id')
                //     ->default('prodi_id'),
                // Forms\Components\Hidden::make('identity_type')
                //     ->default(Prodi::class),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('identity.nama')
                    ->label('Prodi')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProdis::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'prodi');
            });
    }
}
