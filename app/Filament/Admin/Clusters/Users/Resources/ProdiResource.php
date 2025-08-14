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
    protected static ?string $navigationLabel = 'Admin';
    protected static ?string $pluralModelLabel = 'Daftar Akun';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\Select::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Admin',
                        'prodi' => 'Prodi',
                    ])
                    ->required(),
                Forms\Components\Select::make('identity_id')
                    ->label('Prodi')
                    ->options(function () {
                        return Prodi::pluck('nama', 'id');
                    })
                    ->required(),
                Forms\Components\Hidden::make('identity_type')
                    ->default(Prodi::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'prodi' => 'success',
                        default => 'gray',
                    }),
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
                $query->whereIn('name', ['admin', 'prodi']);
            });
    }
}
