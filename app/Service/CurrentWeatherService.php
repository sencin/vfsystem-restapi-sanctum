<?php
 namespace App\Service;
 use Illuminate\Support\Facades\Http;
 class CurrentWeatherService{

    private function convertToLocalTime($timestamp, $timezoneOffset)
    {
        $dateTime = new \DateTime("@$timestamp");
        $dateTime->setTimezone(new \DateTimeZone('UTC'));
        $dateTime->modify("+{$timezoneOffset} seconds");
        return $dateTime->format('Y-m-d h:i:s A');
    }

    public function fetchCurrentWeather(){
      $apiKey = env('OPENWEATHER_API_KEY');
      $url ="https://api.openweathermap.org/data/2.5/weather?lat=12.4996559&lon=124.6365924&appid={$apiKey}&units=metric";
      $response = Http::get($url);

        if ($response->successful()) {
            $data = $response->json();
            $timestamp = $data['dt'];
            $timezoneOffset = $data['timezone'];
            $localTime = $this->convertToLocalTime($timestamp, $timezoneOffset);
            $iconCode = $data['weather'][0]['icon'];
            $iconUrl = "https://openweathermap.org/img/wn/{$iconCode}@4x.png";
            $temperature = number_format($data['main']['temp'], 1);
            $city = $data['name'];
        //  $humidity = $data['main']['humidity'];
            $weather = $data['weather'][0]['main'];
            $weatherDdescription = $data['weather'][0]['description'];
            $country = $data['sys']['country'];

            return [
                'city' => $city,
                'temperature' => $temperature,
                'weather' => $weather,
                'weather_description' => $weatherDdescription,
                'country'=> $country,
                'icon_url' => $iconUrl,
                'local_time' => $localTime
            ];
        } else {
            return null;
        }
    }
 }
