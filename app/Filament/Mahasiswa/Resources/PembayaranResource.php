<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\PembayaranResource\Pages;
use App\Filament\Mahasiswa\Resources\PembayaranResource\RelationManagers;
use App\Models\Pembayaran;
use App\Models\PembayaranItem;
use App\Models\RefSemester;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $semesterAktif = Setting::get('krs_online_semester', 'Tidak Diketahui');

        return $form
            ->schema([
                Forms\Components\Section::make('')
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('semester')
                            ->options(RefSemester::orderBy('key', 'desc')->pluck('value', 'key'))
                            ->default($semesterAktif)
                            ->required(),
                        Forms\Components\Select::make('pembayaran_item_id')
                            ->label('Jenis Pembayaran')
                            ->options(
                                PembayaranItem::where(
                                    'prodi_id',
                                    session('id_prodi')
                                )->where('angkatan', Auth::user()->identity->angkatan)->pluck('nama', 'id')
                            )
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_bayar')
                            ->label('Tanggal Pembayaran')
                            ->required(),
                        Forms\Components\TextInput::make('jumlah')
                            ->required()
                            ->numeric(),
                        Forms\Components\FileUpload::make('bukti_bayar')
                            ->label('Bukti Pembayaran')
                            ->image()
                            ->required(),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan (boleh kosong)')
                            ->maxLength(50),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('semester')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pembayaranItem.nama')
                    ->label('Jenis Pembayaran')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()->visible(fn($record) => $record->status === 'belum verifikasi'),
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'view' => Pages\ViewPembayaran::route('/{record}'),
            // 'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('mahasiswa_id', Auth::user()->identity->id)
            ->orderBy('semester', 'desc');
    }
}
