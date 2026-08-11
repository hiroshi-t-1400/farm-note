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

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


    }

    public function indexSimple(string $id = '')
    {
        //
        if ($id == '') {
            $work_log = WorkLog::with(['cropSeason', 'createdBy'])
                ->orderBy('work_date')
                ->cursorPaginate(15);
        } else {
            $work_log = WorkLog::with(['cropSeason', 'createdBy'])
                ->where('crop_season_id', $id)
                ->orderBy('work_date')
                ->cursorPaginate(15);
        }

        $crop_seasons = CropSeason::with('crop', 'field')
            ->withCount('workLog')
            ->get();

        $models = [
            'cropSeasons' => CropSeasonResource::collection($crop_seasons)->resolve(),
            'workLog' => WorkLogResource::collection($work_log)->response()->getData(true),
        ];
        // $cropSeasons = CropSeasonResource::collection($crop_seasons)->resolve();

        return response()->view('/work-logs.index', compact('models'));
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

        return response()->view('/work-logs.create', compact('models'));
    }

    /**
     * 作業記録の登録
     *
     * @param StoreWorkLogRequest $request
     * @return JsonResponse
     *
     */
    public function store(StoreWorkLogRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // 登録する作業が予定plan、完了completed、下書きdraftで分岐
        $status = $validated['status'] ? 'plan' : 'completed';

        $workLog = WorkLog::create([
            'crop_season_id' => $validated['crop_season_id'],
            'created_by' => $validated['created_by'],
            'work_date' => $validated['work_date'],
            'status' => $status,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'updated_by' => null,
        ]);

        foreach ($validated['performed_by'] as $pu) {
            $workLog->performedBy()->sync($pu['id']);
        };
        // $workLog->performedBy()->sync($validated['performed_by']);
        // $workLog->performedBy()->sync($validated['performed_by']);

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
    public function show(string $id)
    {
        $work_log = WorkLog::with([
                'material',
                'cropSeason',
                'createdBy',
                'performedBy',
                'updatedBy'])
            ->find($id);

        $workLog = new WorkLogResource($work_log)->resolve();

            return response()->view('/work-logs.show', compact('workLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        //
        $work_log = WorkLog::with([
                'material',
                'performedBy'])
            ->find($id);

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

        return response()->view('/work-logs.edit', compact('models'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreWorkLogRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        // 登録する作業が予定plan、完了completed、下書きdraftで分岐
        $status = $validated['status'] ? 'plan' : 'completed';

        $target_log = WorkLog::find($id);

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
    public function destroy(string $id)
    {
        $work_log = WorkLog::find($id);
        $work_log->delete();


        return response()->json([
            'status' => 'success',
            'message' => '日誌を削除しました。',
            'delCount' => $work_log,
            'redirect_url' => route('dashboard')
        ]);
    }
}
