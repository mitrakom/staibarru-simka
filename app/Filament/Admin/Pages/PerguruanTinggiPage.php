<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use App\Models\PerguruanTinggi;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class PerguruanTinggiPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $title = 'Data Perguruan Tinggis';
    protected static ?string $navigationLabel = 'Perguruan Tinggis';


    protected static ?string $navigationGroup = 'Master';
    protected static ?string $pluralModelLabel = 'Data Perguruan Tinggi';
    protected static ?int $navigationSort = 9;

    public ?array $data = [];

    public function mount(): void
    {
        $pt = PerguruanTinggi::first();
        if ($pt) {
            $this->form->fill($pt->toArray());
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('Identitas')
                    ->description('Informasi utama perguruan tinggi')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode')->label('Kode PT'),
                                Forms\Components\TextInput::make('nama')->label('Nama Perguruan Tinggi')->required(),
                                Forms\Components\TextInput::make('telepon')->label('Telepon'),
                                Forms\Components\TextInput::make('faximile')->label('Faximile'),
                                Forms\Components\TextInput::make('email')->label('Email'),
                                Forms\Components\TextInput::make('website')->label('Website'),
                            ])
                    ]),
                Forms\Components\Section::make('Alamat')
                    ->description('Alamat lengkap perguruan tinggi')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('jalan')->label('Jalan'),
                                Forms\Components\TextInput::make('dusun')->label('Dusun'),
                                Forms\Components\TextInput::make('rt_rw')->label('RT/RW'),
                                Forms\Components\TextInput::make('kelurahan')->label('Kelurahan'),
                                Forms\Components\TextInput::make('kode_pos')->label('Kode Pos'),
                                Forms\Components\TextInput::make('id_wilayah')->label('ID Wilayah'),
                                Forms\Components\TextInput::make('nama_wilayah')->label('Nama Wilayah'),
                                Forms\Components\TextInput::make('lintang_bujur')->label('Lintang/Bujur'),
                            ])
                    ]),
                Forms\Components\Section::make('Legalitas & Status')
                    ->description('Informasi legalitas dan status kepemilikan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('sk_pendirian')->label('SK Pendirian'),
                                Forms\Components\DatePicker::make('tanggal_sk_pendirian')->label('Tanggal SK Pendirian'),
                                Forms\Components\TextInput::make('id_status_milik')->label('ID Status Milik'),
                                Forms\Components\TextInput::make('nama_status_milik')->label('Nama Status Milik'),
                                Forms\Components\TextInput::make('status_perguruan_tinggi')->label('Status PT'),
                                Forms\Components\TextInput::make('sk_izin_operasional')->label('SK Izin Operasional'),
                                Forms\Components\DatePicker::make('tanggal_izin_operasional')->label('Tanggal Izin Operasional'),
                            ])
                    ]),
                Forms\Components\Section::make('Keuangan & Tanah')
                    ->description('Informasi bank dan tanah')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('bank')->label('Bank'),
                                Forms\Components\TextInput::make('unit_cabang')->label('Unit/Cabang'),
                                Forms\Components\TextInput::make('nomor_rekening')->label('Nomor Rekening'),
                                Forms\Components\TextInput::make('mbs')->label('MBS'),
                                Forms\Components\TextInput::make('luas_tanah_milik')->label('Luas Tanah Milik'),
                                Forms\Components\TextInput::make('luas_tanah_bukan_milik')->label('Luas Tanah Bukan Milik'),
                            ])
                    ]),
            ]);
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $pt = PerguruanTinggi::first();

        if ($pt) {
            $pt->update($data);
        } else {
            PerguruanTinggi::create($data);
        }

        Notification::make()
            ->title('Data perguruan tinggi berhasil diperbarui!')
            ->success()
            ->send();
    }

    protected static string $view = 'filament.admin.pages.perguruan-tinggi-form';
}
