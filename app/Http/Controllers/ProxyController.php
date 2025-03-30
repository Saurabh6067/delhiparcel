<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProxyController extends Controller
{
    public function handle(Request $request)
    {
        // Retrieve the origin and destination from the request
        $origin = $request->query('origin');
        $destination = $request->query('destination');

        // API Key for Google Maps Directions API
        $apiKey = 'AIzaSyAx_5V0k3AP2ZxGMNZ7TSy0LnhwChWuDoE';

        // Construct the Google Maps API URL
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=$origin&destination=$destination&mode=driving&key=$apiKey";

        // Fetch the response from the API
        $response = file_get_contents($url);

        // Return the response as JSON
        return response($response, 200)->header('Content-Type', 'application/json');
    }
}
