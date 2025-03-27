<?php

namespace App\Http\Controllers\Web;
use App\Models\Reading;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class ReadingController extends Controller{

    public function getNewReadings(Request $request) {
        $lastReadingId = $request->input('lastReadingId');
        $towerId = $request->input('towerId');
        $query = Reading::where('reading_id', '>', $lastReadingId);
        if ($towerId) {
            $query = $query->whereHas('sensor', function ($query) use ($towerId) {
                $query->where('tower_id', $towerId);
            });
        }
        $newReadings = $query->with('sensor')
                             ->orderBy('reading_id', 'desc')
                             ->take(50)
                             ->get()
                             ->reverse();

        $newReadings = $newReadings->map(function($reading) {
            return [
                'reading_id' => $reading->reading_id,
                'reading_value' => $reading->reading_value,
                'record_date' => $reading->record_date,
                'tower_id' => $reading->sensor->tower_id,
                'sensor_name' => $reading->sensor->sensor_name
            ];
        });

        if ($newReadings->isNotEmpty()) {
            return response()->json($newReadings->values()->toArray());
        }
        return response()->json(['message' => 'No new readings available.', 'lastFetchedId' => $lastReadingId], 204);
    }





  public function getData(string $interval){
      $query = DB::table('readings');
      $labelTitle = '';
      $groupFormat = '';

      // Adjust query and label title based on interval
      switch ($interval) {
          case 'hour':
              $labelTitle = 'Average per Hour (Last 24 Hours)';
              $groupFormat = '%Y-%m-%d %H:00:00'; // Group by hour
              $query->where('record_date', '>=', Carbon::now()->subDay()); // Last 24 hours
              break;
          case 'day':
              $labelTitle = 'Average per Day (Last 30 Days)';
              $groupFormat = '%Y-%m-%d'; // Group by day
              $query->where('record_date', '>=', Carbon::now()->subMonth()); // Last 30 days
              break;
          case 'week':
              $labelTitle = 'Average per Week (Last 12 Weeks)';
              $groupFormat = '%Y-%u'; // Group by week number
              $query->where('record_date', '>=', Carbon::now()->subWeeks(12)); // Last 12 weeks
              break;
          case 'month':
              $labelTitle = 'Average per Month (Last 12 Months)';
              $groupFormat = '%Y-%m'; // Group by month
              $query->where('record_date', '>=', Carbon::now()->subYear()); // Last 12 months
              break;
      }

      // Aggregate the data by grouping and averaging
      $data = $query->select(DB::raw("DATE_FORMAT(record_date, '$groupFormat') as period, AVG(reading_value) as avg_value"))
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();

      // Format the data for Chart.js
      return response()->json([
          'labels' => $data->pluck('period'), // Grouped time period
          'values' => $data->pluck('avg_value'), // Averaged values
          'labelTitle' => $labelTitle // Dynamic label title
      ]);
  }
}
