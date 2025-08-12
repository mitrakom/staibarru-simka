<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class feederKoneksi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:feeder-koneksi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Menguji koneksi ke Feeder...');
        try {
            $client = app(\App\Services\Feeder\FeederClient::class);
            $result = $client->authenticate();
            if (is_string($result)) {
                $this->info('Koneksi ke Feeder BERHASIL! Token: ' . $result);
                return 0;
            } else {
                $this->error('Koneksi ke Feeder GAGAL!');
                $this->line(print_r($result, true));
                return 1;
            }
        } catch (\Throwable $e) {
            $this->error('Terjadi error: ' . $e->getMessage());
            return 2;
        }
    }
}
