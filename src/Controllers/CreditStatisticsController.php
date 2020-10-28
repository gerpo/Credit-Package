<?php

namespace Gerpo\DmsCredits\Controllers;

use Carbon\Carbon;
use DB;
use Gerpo\DmsCredits\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CreditStatisticsController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'type'            => 'nullable|sometimes|in:daily,monthly',
            'start_timestamp' => 'date',
            'end_timestamp'   => 'date',
        ]);

        $data['type'] = $data['type'] ?? 'monthly';

        if ($data['type'] === 'daily') {
            $result = $this->getDailyStatistics($data);
        } else {
            $result = $this->getMonthlyStatistics($data);
        }

        $result['total_data'] = $this->getTotalStatistics();
        $result['creator_data'] = $this->getCreatorStatistics();

        return $result;
    }

    private function getDailyStatistics($data)
    {
        $result['type'] = 'daily';
        $result['start_timestamp'] = $data['start_timestamp'] ?? Carbon::today()->subMonth();
        $result['end_timestamp'] = $data['end_timestamp'] ?? Carbon::today()->addDay();

        $result['period_data'] = $this->getBaseQuery()
            ->addSelect(DB::raw('DATE_FORMAT(created_at, "%d.%m.%y") as day'))
            ->whereBetween('created_at', [$result['start_timestamp'], $result['end_timestamp']])
            ->groupBy('day')
            ->get()
            ->keyBy('day');

        return $result;
    }

    private function getBaseQuery()
    {
        return Code::select(
            DB::raw('count(id) as created_codes'),
            DB::raw('convert(sum(value), UNSIGNED) as created_value'),
            DB::raw('count(used_at) as used_codes'),
            DB::raw('convert(sum(if(used_at IS NOT NULL, value, 0)), UNSIGNED) as used_value')
        );
    }

    private function getMonthlyStatistics($data)
    {
        $result['type'] = 'monthly';
        $result['start_timestamp'] = $data['start_timestamp'] ?? Carbon::today()->subYear();
        $result['end_timestamp'] = $data['end_timestamp'] ?? Carbon::today()->addDay();

        $result['period_data'] = $this->getBaseQuery()
            ->addSelect(DB::raw('DATE_FORMAT(created_at, "%m.%y") as month'))
            ->whereBetween('created_at', [$result['start_timestamp'], $result['end_timestamp']])
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        return $result;
    }

    private function getTotalStatistics()
    {
        return $this->getBaseQuery()->first();
    }

    private function getCreatorStatistics()
    {
        return $this->getBaseQuery()
            ->addSelect('created_by')
            ->with('creator:id,firstname,lastname,username')
            ->groupBy('created_by')
            ->get();
    }
}
