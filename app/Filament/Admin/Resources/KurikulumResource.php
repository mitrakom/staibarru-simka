<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KurikulumResource\Pages;
use App\Filament\Admin\Resources\KurikulumResource\RelationManagers;
use App\Filament\Admin\Resources\KurikulumMatakuliahResource;
use App\Models\Kurikulum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class KurikulumResource extends Resource
{
    protected static ?string $model = Kurikulum::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Kurikulum';
    protected static ?string $pluralModelLabel = 'Data Kurikulum';
    protected static ?int $navigationSort = 1;

    // Override method getNavigationItems untuk menambahkan sub-menu
    // public static function getNavigationItems(): array
    // {
    //     return [
    //         ...parent::getNavigationItems(),
    //         ...KurikulumMatakuliahResource::getNavigationItems(),
    //     ];
    // }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Select::make('prodi_id')
                //     ->relationship('prodi', 'id')
                //     ->required(),
                // Forms\Components\TextInput::make('feeder_id')
                //     ->required()
                //     ->maxLength(36),
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(55),
                Forms\Components\TextInput::make('semester')
                    ->label('Semester Mulai Berlaku')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('keterangan')
                    ->label('Keterangan Semester Mulai Berlaku')
                    ->maxLength(155),
                Forms\Components\TextInput::make('jumlah_sks_lulus')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jumlah_sks_wajib')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jumlah_sks_pilihan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jumlah_sks_mata_kuliah_wajib')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jumlah_sks_mata_kuliah_pilihan')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_sks_lulus')
                    ->label('Jml. SKS Lulus')
                    ->numeric()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('jumlah_sks_wajib')
                    ->label('Jml. SKS Wajib')
                    ->numeric()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('jumlah_sks_pilihan')
                    ->label('Jml. SKS Pilihan')
                    ->alignCenter()
                    ->numeric(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('semester', 'desc')

        ;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageKurikulums::route('/'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        return parent::getEloquentQuery()->where('prodi_id', $user->prodi_id);
    }
}
