<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkLogRequest;
use App\Http\Resources\CropSeasonResource;
use App\Http\Resources\MaterialResource;
use App\Http\Resources\MaterialCategoryResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WorkLogResource;
use Illuminate\Http\Request;

use App\Models\Crop\CropSeason;
use App\Models\User;
use App\Models\Material\Material;
use App\Models\Material\MaterialCategory;
use App\Models\WorkLog\WorkLog;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class WorkController extends Controller
{
    /**
     * タイムライン表示の実装を検討
     */
    public function index()
    {

    }

    public function indexSimple(?CropSeason $cropSeason = null)
    // public function indexSimple(string $id = '')
    {
        $query = WorkLog::with(['cropSeason', 'createdBy'])
            ->whereBetween('work_date', ['2026-01-01 00:00:00', '2026-12-31 00:00:00'])
            ->orderBy('work_date');
        if ($cropSeason !== null) {
            $query->where('crop_season_id', $cropSeason->id);
        }
        $work_log = $query->cursorPaginate(15);

        $crop_seasons = CropSeason::with('crop', 'field')
            ->get();

        $models = [
            'cropSeasons' => CropSeasonResource::collection($crop_seasons)->resolve(),
            'workLog' => WorkLogResource::collection($work_log)->response()->getData(true),
        ];
        // $cropSeasons = CropSeasonResource::collection($crop_seasons)->resolve();

        return response()->view('work-logs.index', compact('models'));
    }

    /**
     * 作業登録画面を呼び出す
     */
    public function create()
    {
        $crop_seasons = CropSeason::with('crop')->get();
        $users = User::all();
        $materials = Material::with('materialCategory')->get();
        $mat_types = MaterialCategory::all();

        $models = [
            'cropSeasons' => CropSeasonResource::collection($crop_seasons)->resolve(),
            'users' => UserResource::collection($users)->resolve(),
            'materials' => MaterialResource::collection($materials)->resolve(),
            'matTypes' => MaterialCategoryResource::collection($mat_types)->resolve()
        ];

        return response()->view('work-logs.create', compact('models'));
    }

    /**
     * 作業記録の登録
     *
     * @param StoreWorkLogRequest $request
     * @return JsonResponse
     *
     */
    public function store(Request $request, StoreWorkLogRequest $workLog): JsonResponse
    // public function store(StoreWorkLogRequest $request): JsonResponse
    {
        $validated = $workLog->validated();
        // $validated = $request->validated();

        // 登録する作業が予定plan、完了completed、下書きdraftで分岐
        $status = $validated['status'] ? 'plan' : 'completed';

        $workLog = WorkLog::create([
            'crop_season_id' => $validated['crop_season_id'],
            // 'created_by' => $validated['created_by'],
            'created_by' => $request->user()->id,
            'work_date' => $validated['work_date'],
            'status' => $status,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'updated_by' => null,
        ]);

        foreach ($validated['performed_by'] as $pu) {
            $workLog->performedBy()->sync($pu['id']);
        };

        // 登録された作業記録のなかで使用資材が記録されていれば登録を行う
        // 資材が複数あればすべて中間テーブルに登録する
        if (!empty($validated['material_logs'])) {
            foreach ($validated['material_logs'] as $material) {
                $workLog->material()->attach($material["material_id"], [
                    'quantity' => $material["quantity"],
                    'dilution_rate' => $material["dilution_rate"],
                    'material_amount' => $material["material_amount"] ?? null,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => '日誌を保存しました。',
            'redirect_url' => route('create')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, WorkLog $workLog)
    // public function show(string $id)
    {
        $work_log = $workLog->load([
            'material',
            'cropSeason',
            'createdBy',
            'performedBy',
            'updatedBy'
        ]);
        // $work_log = WorkLog::with([
        //         'material',
        //         'cropSeason',
        //         'createdBy',
        //         'performedBy',
        //         'updatedBy'])
        //     ->find($workLog->id);

        $workLog = new WorkLogResource($work_log)->resolve();

            return response()->view('work-logs.show', compact('workLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, WorkLog $workLog)
    // public function edit(string $id)
    {
        Gate::authorize('update', $workLog);

        $work_log = $workLog->load([
            'material',
            'performedBy'
        ]);
        // ])
        // $work_log = WorkLog::with([
        //         'material',
        //         'performedBy'])
        //     ->find($workLog->id);

        $crop_seasons = CropSeason::with('crop')->get();
        $users = User::all();
        $materials = Material::with('materialCategory')->get();
        $mat_types = MaterialCategory::all();

        $models = [
            'cropSeasons' => CropSeasonResource::collection($crop_seasons)->resolve(),
            'users' => UserResource::collection($users)->resolve(),
            'materials' => MaterialResource::collection($materials)->resolve(),
            'matTypes' => MaterialCategoryResource::collection($mat_types)->resolve(),
            'workLog' => new WorkLogResource($work_log)->resolve()
        ];

        return response()->view('work-logs.edit', compact('models'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreWorkLogRequest $request, WorkLog $workLog): JsonResponse
    {
        Gate::authorize('update', $workLog);
        $validated = $request->validated();

        $target_log = $workLog;

        // 登録する作業が予定plan、完了completed、下書きdraftで分岐
        $status = $validated['status'] ? 'plan' : 'completed';

        $target_log->update([
                'crop_season_id' => $validated['crop_season_id'],
                'work_date' => $validated['work_date'],
                'status' => $status,
                'title' => $validated['title'],
                'content' => $validated['content'],
                'updated_by' => $validated['created_by'],
            ]);

        foreach ($validated['performed_by'] as $pu) {
            $target_log->performedBy()->sync($pu['id']);
        };

        // 登録された作業記録のなかで使用資材が記録されていれば登録を行う
        // 資材が複数あればすべて中間テーブルに登録する
        if (!empty($validated['material_logs'])) {
            foreach ($validated['material_logs'] as $material) {
                $target_log->material()->sync($material["material_id"], [
                    'quantity' => $material["quantity"],
                    'dilution_rate' => $material["dilution_rate"],
                    'material_amount' => $material["material_amount"] ?? null,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => '日誌を保存しました。',
            'redirect_url' => route('dashboard')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, WorkLog $workLog)
    {
        Gate::authorize('delete', $workLog);

        // $work_log = WorkLog::find($workLog->id);
        $workLog->delete();


        return response()->json([
            'status' => 'success',
            'message' => '日誌を削除しました。',
            'delCount' => $workLog,
            'redirect_url' => route('dashboard')
        ]);
    }
}
