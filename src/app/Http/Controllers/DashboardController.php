<?php

namespace App\Http\Controllers;

use App\Http\Resources\CropSeasonResource;
use App\Http\Resources\MaterialCategoryResource;
use App\Http\Resources\MaterialResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WorkLogResource;
use App\Models\Crop\CropSeason;
use App\Models\Material\Material;
use App\Models\Material\MaterialCategory;
use App\Models\User;
use App\Models\WorkLog\WorkLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        // $user = Auth::user();
        $users = User::all();
        $materials = Material::with('materialCategory')->get();
        $mat_types = MaterialCategory::all();
        $crop_seasons = CropSeason::with('crop', 'field')
            ->withCount('workLog')
            ->get();

        $get_recent = WorkLog::with(['cropSeason', 'createdBy', 'performedBy', 'updatedBy'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        $models = [
            'cropSeasons' => CropSeasonResource::collection($crop_seasons)->resolve(),
            'users' => UserResource::collection($users)->resolve(),
            'materials' => MaterialResource::collection($materials)->resolve(),
            'matTypes' => MaterialCategoryResource::collection($mat_types)->resolve(),
            'recent' => WorkLogResource::collection($get_recent)->resolve()
        ];

        return response()->view('/dashboard', compact('models'));
    }
}
