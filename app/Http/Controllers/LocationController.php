<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;

class LocationController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function addLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required|string|min:3'
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
        } else {
            Location::updateOrCreate(...[['ip' => $request->ip()], ['location' => $request->get('location')]]);
        }

        return ['error' => $error ?? null];
    }

    public function getLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|alpha|min:3'
        ]);
        $result = null;
        if ($validator->passes()) {
            $queryLocation = $request->get('query');
            $result = Redis::get($queryLocation);
            if (empty($result)) {
                try {
                    $result = \GooglePlaces::placeAutocomplete($queryLocation)
                        ->get('predictions', collect())
                        ->map(function ($item) {
                            return $item['description'] ?? null;
                        })
                        ->filter()
                        ->all();

                    Redis::set($queryLocation, json_encode($result));
                } catch (GooglePlacesApiException $e) {
                    report($e);
                }
            }
        }

        return response($result)->header('Content-Type', 'application/json; charset=utf-8');
    }
}
