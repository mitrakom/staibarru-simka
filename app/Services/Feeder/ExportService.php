<?php

namespace App\Services\Feeder;

class ExportService
{
    protected FeederClient $client;

    public function __construct(FeederClient $client)
    {
        $this->client = $client;
    }

    public function insertMahasiswa(array $record)
    {
        return $this->client->insert('InsertBiodataMahasiswa', $record);
    }

    public function updateMahasiswa(array $keys, array $record)
    {
        return $this->client->update('UpdateBiodataMahasiswa', $keys, $record);
    }

    public function deleteMahasiswa(array $keys)
    {
        return $this->client->delete('DeleteBiodataMahasiswa', $keys);
    }
}
