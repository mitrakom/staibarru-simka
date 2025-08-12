<?php

namespace App\Filament\Admin\Clusters;

use Filament\Clusters\Cluster;

class Users extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Akun';
    protected static ?string $pluralModelLabel = 'Data Akun Pengguna';
}
