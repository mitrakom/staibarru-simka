<?php

namespace App\Filament\Admin\Clusters\Referensi\Resources;

use App\Filament\Admin\Clusters\Referensi;
use App\Filament\Admin\Clusters\Referensi\Resources\RefSemesterResource\Pages;
use App\Filament\Admin\Clusters\Referensi\Resources\RefSemesterResource\RelationManagers;
use App\Models\RefSemester;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RefSemesterResource extends Resource
{
    protected static ?string $model = RefSemester::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Semester';
    protected static ?string $pluralModelLabel = 'Data Semester';

    protected static ?string $cluster = Referensi::class;

    // TODO Buat form otomatis yaitu input semester: Ganjil/Genap, input tahun: 2021
    // Nantinya otomatis akan membuat key dan value sesuai dengan inputan tersebut

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label('Kode Semester')
                    ->required()
                    ->maxLength(5)
                    ->numeric(),
                Forms\Components\TextInput::make('value')
                    ->label('Nama Semester')
                    ->required()
                    ->maxLength(25),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Kode')
                    // ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Nama Semester')
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
            'index' => Pages\ManageRefSemesters::route('/'),
        ];
    }
}
