<?php

namespace App\Filament\Admin\Clusters\Users\Resources;

use App\Filament\Admin\Clusters\Users;
use App\Filament\Admin\Clusters\Users\Resources\MahasiswaResource\Pages;
use App\Filament\Admin\Clusters\Users\Resources\MahasiswaResource\RelationManagers;
use App\Models\Mahasiswa;
use App\Models\User;
use Filament\Facades\Filament;
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
    protected static ?string $navigationLabel = 'Mahasiswa';

    protected static ?string $cluster = Users::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('identity_id')
                    ->label('Pilih Mahasiswa')
                    ->options(
                        Mahasiswa::whereDoesntHave('user')
                            ->where('prodi_id', session('prodi_id'))
                            ->orderBy('nim')
                            ->get()
                            ->mapWithKeys(fn($m) => [$m->id => $m->nim . ' - ' . $m->nama])
                            ->toArray()
                    )
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $mahasiswa = Mahasiswa::find($state);
                            if ($mahasiswa) {
                                $domain = env('USER_EMAIL_DOMAIN', 'uit.ac.id');
                                $set('name', $mahasiswa->nama); // username = NIM
                                $set('username', $mahasiswa->nim);
                                $set('email', $mahasiswa->nim . '@' . $domain);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('username')
                    ->label('Username (NIM)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Name (Username)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                // Forms\Components\TextInput::make('password')
                //     ->password()
                //     ->required()
                //     ->maxLength(255)
                //     ->dehydrateStateUsing(fn($state) => bcrypt($state)),
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
            'index' => Pages\ManageMahasiswas::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('identity_type', 'App\\Models\\Mahasiswa')
            // ->whereHas('identity', function ($query) {
            //     $query->where('prodi_id', session()->get('prodi_id'));
            // })
        ;
    }
}
