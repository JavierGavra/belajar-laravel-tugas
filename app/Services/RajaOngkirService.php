<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        // Catatan: Di Laravel produksi, lebih disarankan memanggil konfigurasi
        // menggunakan config('services.rajaongkir.key') daripada env() langsung.
        $this->apiKey = env('RAJAONGKIR_API_KEY');
        $this->baseUrl = env('RAJAONGKIR_BASE_URL');
    }
    
    public function getDestination(string $keyword): array
    {
        $response = Http::timeout(10)
            ->withHeaders([
                'Accept' => 'application/json',
                'key'    => $this->apiKey,
            ])
            ->get($this->baseUrl . 'destination/domestic-destination', [
                'search' => $keyword,
                'limit'  => 50,
            ]);

        // Method json() otomatis mengubah body JSON response menjadi array
        return $response->json() ?? [];
    }
    
    public function getCost(string $origin, string $destination, int $weight, string $courier): array 
    {
        $response = Http::timeout(10)
            ->asForm() // Setara dengan 'form_params' di CI4/Guzzle
            ->withHeaders([
                'Accept' => 'application/json',
                'key'    => $this->apiKey,
            ])
            ->post($this->baseUrl . 'calculate/domestic-cost', [
                'origin'      => $origin,
                'destination' => $destination,
                'weight'      => $weight,
                'courier'     => $courier,
            ]);

        return $response->json() ?? [];
    }
}