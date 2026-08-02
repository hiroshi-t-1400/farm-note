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

        $cropSeasons = CropSeason::with('crops')
            ->withCount('workLogs')
            ->get();

        $latestWorkLogs = WorkLog::with('cropSeasons', 'createdBy', 'paerformedBy', 'updatedBy')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return response()->view('/dashboard', compact('cropSeason', 'latestWorkLogs'));
    }
}
