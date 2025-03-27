<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Service\CurrentWeatherService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public CurrentWeatherService $currentWeatherService;
    public function __construct(CurrentWeatherService $currentWeatherService) {
        $this->currentWeatherService = $currentWeatherService;
    }
    
    public function index(){
        $weather = $this->currentWeatherService->fetchCurrentWeather();
        return view('index', ['Weather'=> $weather]);
    }
}
