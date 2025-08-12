<?php

namespace App\Services\Feeder;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class FeederClient
{
    protected string $baseUrl = 'http://103.176.79.62:8787/ws/live2.php';

    public function request(array $payload)
    {
        $response = Http::post($this->baseUrl, $payload);

        // if ($response->failed()) {
        if ($response['error_code'] != 0) {
            Log::error("Feeder API Error", ['payload' => $payload, 'response' => $response->body()]);
            return ['error' => 'Feeder API Error', 'response' => $response->body()];
        }

        return $response->json();
    }

    public function authenticate()
    {
        $username = "wahyudienal1827@gmail.com";
        $password = "UIT@7777";

        $response = $this->request([
            'act' => 'GetToken',
            'username' => $username,
            'password' => $password
        ]);

        if ($response && isset($response['data']['token'])) {
            $token = $response['data']['token'];
            $expiration = now()->addMinutes(10); // Token berlaku selama 10 menit
            Session::put('feeder_token', $token);
            Session::put('feeder_token_expiration', $expiration);
            return $token;
        }

        return ['error' => 'Authentication failed', 'response' => $response];
    }

    private function ensureAuthenticated()
    {
        // $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZF9wZW5nZ3VuYSI6IjllZjEzZmM2LThkOTktNDI3OS04NmJlLWI1NTY4MzZiMzQxZSIsInVzZXJuYW1lIjoid2FoeXVkaWVuYWwxODI3QGdtYWlsLmNvbSIsIm5tX3BlbmdndW5hIjoiQUJESSBFTkFMIFdBSFlVREksIFMuS29tLiwgTS5Lb20iLCJ0ZW1wYXRfbGFoaXIiOiJCVUxVS1VNQkEiLCJ0Z2xfbGFoaXIiOiIxOTk0LTAxLTE3VDE3OjAwOjAwLjAwMFoiLCJqZW5pc19rZWxhbWluIjoiTCIsImFsYW1hdCI6Ii0iLCJ5bSI6IndhaHl1ZGllbmFsMTgyN0BnbWFpbC5jb20iLCJza3lwZSI6IiIsIm5vX3RlbCI6IiIsImFwcHJvdmFsX3BlbmdndW5hIjoiNSIsImFfYWt0aWYiOiIxIiwidGdsX2dhbnRpX3B3ZCI6IjIwMjUtMDMtMDRUMTc6MDA6MDAuMDAwWiIsImlkX3NkbV9wZW5nZ3VuYSI6bnVsbCwiaWRfcGRfcGVuZ2d1bmEiOm51bGwsImlkX3dpbCI6IjE5NjAwMCAgIiwibGFzdF91cGRhdGUiOiIyMDI1LTAzLTA1VDA3OjIwOjUwLjE3N1oiLCJzb2Z0X2RlbGV0ZSI6IjAiLCJsYXN0X3N5bmMiOiIyMDI1LTAzLTI5VDEyOjQzOjE2LjI2N1oiLCJpZF91cGRhdGVyIjoiOWVmMTNmYzYtOGQ5OS00Mjc5LTg2YmUtYjU1NjgzNmIzNDFlIiwiY3NmIjoiLTE1MjQ2OTcyNjYiLCJ0b2tlbl9yZWciOm51bGwsImphYmF0YW4iOm51bGwsInRnbF9jcmVhdGUiOiIxOTY5LTEyLTMxVDE3OjAwOjAwLjAwMFoiLCJuaWsiOm51bGwsInNhbHQiOm51bGwsImlkX3BlcmFuIjozLCJubV9wZXJhbiI6IkFkbWluIFBUIiwiaWRfc3AiOiJhZDZmOTZlMy05ZGRkLTQ1ZjktOWEzMS1kOGE1MWI4ZTA0MDAiLCJpZF9zbXQiOiIyMDI0MiIsImlhdCI6MTc0MzY5NDMxOCwiZXhwIjoxNzQzNzEyMzE4fQ.e_Xb2-2O83ywSyz0eL0PwY5mLBz0iXmFxL69ZBg-ogg';
        // Session::put('feeder_token', $token);

        $token = Session::get('feeder_token');
        $expiration = Session::get('feeder_token_expiration');
        // Periksa apakah token ada dan belum kedaluwarsa
        if ($token && $expiration && now()->lessThan($expiration)) {
            return $token;
        }

        // Jika token tidak ada atau sudah kedaluwarsa, lakukan autentikasi ulang
        return $this->authenticate();
    }

    public function fetch(string $act, array $filter = [], string $order = '', int $limit = 10, int $offset = 0)
    {
        $token = $this->ensureAuthenticated();
        if (!$token) return null;

        $filterString = $this->buildFilterString($filter);

        return $this->request([
            'act' => $act,
            'token' => $token,
            'filter' => $filterString,
            'order' => $order,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    public function fetchs(string $act, string $filter = "", string $order = '', int $limit = 10, int $offset = 0)
    {
        $token = $this->ensureAuthenticated();
        if (!$token) return null;

        return $this->request([
            'act' => $act,
            'token' => $token,
            'filter' => $filter,  // Pastikan filter dalam bentuk string SQL
            'order' => $order,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }


    // public function fetchs(string $act, string $filter = "", string $order = '', int $limit = 100, int $offset = 0)
    // {
    //     try {
    //         $token = $this->ensureAuthenticated();
    //         if (!$token) {
    //             Log::error("Gagal mendapatkan token Feeder saat mencoba fetchs data dari {$act}.");
    //             return null;
    //         }

    //         $response = $this->request([
    //             'act' => $act,
    //             'token' => $token,
    //             'filter' => $filter,  // Pastikan filter dalam bentuk string SQL
    //             'order' => $order,
    //             'limit' => $limit,
    //             'offset' => $offset
    //         ]);

    //         if (isset($response['error'])) {
    //             Log::error("Gagal mengambil data dari Feeder untuk {$act}", [
    //                 'error' => $response['error'],
    //                 'filter' => $filter,
    //                 'order' => $order,
    //                 'limit' => $limit,
    //                 'offset' => $offset
    //             ]);
    //             return null;
    //         }

    //         if (!isset($response['data']) || empty($response['data'])) {
    //             Log::info("Data kosong dari Feeder untuk {$act}.", [
    //                 'filter' => $filter,
    //                 'order' => $order,
    //                 'limit' => $limit,
    //                 'offset' => $offset
    //             ]);
    //             return [];
    //         }

    //         Log::info("Berhasil mengambil data dari Feeder untuk {$act}.", [
    //             'total_data' => count($response['data']),
    //             'filter' => $filter,
    //             'order' => $order,
    //             'limit' => $limit,
    //             'offset' => $offset
    //         ]);

    //         return $response;
    //     } catch (\Exception $e) {
    //         Log::error("Exception saat mengambil data dari Feeder untuk {$act}: " . $e->getMessage());
    //         return null;
    //     }
    // }

    private function buildFilterString(array $filter): string
    {
        if (empty($filter)) {
            return ""; // Kembalikan string kosong jika tidak ada filter
        }

        $filterArray = [];

        foreach ($filter as $column => $condition) {
            $filterArray[] = "$column $condition";
        }

        return implode(' AND ', $filterArray);
    }

    public function insert(string $act, array $record)
    {
        $token = $this->ensureAuthenticated();
        if (!$token) return null;

        return $this->request([
            'act' => $act,
            'token' => $token,
            'record' => $record
        ]);
    }

    public function update(string $act, array $keys, array $record)
    {
        $token = $this->ensureAuthenticated();
        if (!$token) return null;

        return $this->request([
            'act' => $act,
            'token' => $token,
            'key' => $keys,
            'record' => $record
        ]);
    }

    public function delete(string $act, array $keys)
    {
        $token = $this->ensureAuthenticated();
        if (!$token) return null;

        return $this->request([
            'act' => $act,
            'token' => $token,
            'key' => $keys
        ]);
    }

    // contoh penggunaan pada controller


    /*
        *
    
        $filter = [
            'nama_dosen' => "like '%ga%'",     // Nama dosen mengandung "ga"
            'usia' => "> 30",                 // Usia lebih dari 30
            'status' => "not in ('cuti')",    // Status bukan "cuti"
            'id_dosen' => "in ('123', '456', '789')" // ID Dosen dalam daftar
        ];
        $data = $this->feederClient->fetch('GetListDosen', $filter, 'nama_dosen ASC', 10, 0);

        WHERE nama_dosen LIKE '%ga%'
        AND usia > 30
        AND status NOT IN ('cuti')
        AND id_dosen IN ('123', '456', '789')
        ORDER BY nama_dosen ASC
        LIMIT 10 OFFSET 0

        Contoh Kombinasi AND dan OR
        $filter = ['usia' => "> 30 OR status = 'aktif'"];
        hasil:
        WHERE usia > 30 OR status = 'aktif'

        */
}
