<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkLogRequest;
use Illuminate\Http\Request;

use App\Models\Crop;
use App\Models\CropSeason;
use App\Models\User;
use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\WorkLog;
use App\Models\MaterialWorkLog;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * 作業登録画面を呼び出す
     */
    public function create()
    {
        //
        $crop_seasons = CropSeason::all();
        $users = User::all();
        $materials = Material::all(['id', 'name', 'type_id', 'default_dilution_rate', 'standard_spray_volume', 'manufacturer']);

        //
        $types = MaterialCategory::all();

        $mat_new = $materials->map(function ($item) use($types) {
            return $item->type_label = $types[$item->type_id - 1]->label;
        });

        // $work_log = WorkLog::all();
        // dd($work_log[0]->pivot->quantity);


        return response()->view('create', compact('crop_seasons', 'users', 'materials', 'types'));
    }

    /**
     * 作業記録の登録
     *
     * @param Request $request
     * @return void
     *
     */
    public function store(StoreWorkLogRequest $request)
    {
        //

        $validated = $request->validated();

        // 登録する作業が予定plan、完了completed、下書きdraftで分岐
        $status = $validated['status'] ?? 'completed';

        // dd($validated['crop_season_id']);

        $worklog = WorkLog::create([
            'crop_season_id' => $validated['crop_season_id'],
            'created_by' => $validated['created_by'],
            'performed_by' => $validated['performed_by'],
            'work_date' => $validated['work_date'],
            'status' => $status,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'updated_by' => null,
        ]);

        // 登録された作業記録のなかで使用資材が記録されていれば登録を行う
        // 資材が複数あればすべて中間テーブルに登録する
        if (isset($validated['material_on_work'])) {
            $material_work_log = $validated['material_on_work'];
            foreach ($material_work_log as $key => $material) {
                $worklog->materials()->attach($material["material_id"], [
                    'quantity' => $material["quantity"],
                    'dilution_rate' => $material["dilution_rate"],
                    'material_amount' => $material["material_amount"],
                    ]);
            }
        }

        // return redirect()->route('create')->with('success', '作業記録を保存しました。');
        return redirect()->route('create');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
