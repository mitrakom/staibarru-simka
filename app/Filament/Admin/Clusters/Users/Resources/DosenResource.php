<?php

namespace App\Filament\Admin\Clusters\Users\Resources;

use App\Filament\Admin\Clusters\Users;
use App\Filament\Admin\Clusters\Users\Resources\DosenResource\Pages;
use App\Filament\Admin\Clusters\Users\Resources\DosenResource\RelationManagers;
use App\Models\Dosen;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DosenResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Dosen';
    protected static ?string $pluralModelLabel = 'Daftar Akun Dosen';

    protected static ?string $cluster = Users::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('identity_id')
                    ->label('Pilih Dosen')
                    ->options(
                        Dosen::whereDoesntHave('user')
                            ->pluck('nama', 'id')
                            ->toArray()
                    )
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $dosen = Dosen::find($state);
                            if ($dosen) {
                                $domain = env('USER_EMAIL_DOMAIN', 'uit.ac.id');
                                $set('name', $dosen->nama); // username = NIDN
                                $set('username', $dosen->nidn);
                                $set('email', $dosen->nidn . '@' . $domain);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('username')
                    ->label('Username (NIDN)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Name (NIDN)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
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
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDosens::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('identity_type', 'App\\Models\\Dosen');
    }
}
