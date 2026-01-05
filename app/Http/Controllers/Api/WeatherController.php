<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function current(Request $request)
    {
        $validated = $request->validate([
            'lat' => ['required', 'numeric'],
            'lon' => ['required', 'numeric'],
        ]);

        $apiKey = config('services.openweather.key', env('OPENWEATHER_API_KEY'));

        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'lat'   => $validated['lat'],
            'lon'   => $validated['lon'],
            'appid' => $apiKey,
            'units' => 'metric',
            'lang'  => 'id',
        ]);

        if ($response->failed()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengambil data cuaca dari OpenWeather',
                'detail'  => $response->json(),
            ], $response->status());
        }

        $d = $response->json();

        // bentuk respons yang simpel untuk mobile (bisa kamu ubah sesuai kebutuhan)
        return response()->json([
            'status' => 'success',
            'city'   => $d['name'] ?? null,
            'temp'   => $d['main']['temp'] ?? null,
            'desc'   => $d['weather'][0]['description'] ?? null,
            'icon'   => $d['weather'][0]['icon'] ?? null,
            // kalau butuh mentahnya:
            // 'raw' => $d,
        ]);
    }
}
