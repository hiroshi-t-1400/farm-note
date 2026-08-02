<?php

namespace App\Http\Controllers;

use App\Models\Crop\CropSeason;
use App\Models\WorkLog\WorkLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // $user = Auth::user();

        $crop_seasons = CropSeason::with('crops', 'fields')
            ->withCount('workLogs')
            ->get();

        $latest_work_logs = WorkLog::with('cropSeasons', 'createdBy', 'performedBy', 'updatedBy')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return response()->view('/dashboard', compact('crop_seasons', 'latest_work_logs'));
    }
}
