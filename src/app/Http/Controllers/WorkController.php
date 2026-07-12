<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Crop;
use App\Models\CropSeason;
use App\Models\User;
use App\Models\Material;
use App\Models\MaterialCategory;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // $crop_seasons = CropSeason::all();
        $crop_seasons = CropSeason::all();
        $users = User::all();
        $materials = Material::all();

        //
        $types = MaterialCategory::all();

        return response()->view('create', compact('crop_seasons', 'users', 'materials', 'types'));
    }

    /**
     * 作業記録の登録
     *
     * @param Request $request
     * @return void
     *
     */
    public function store(Request $request)
    {
        //
        $request->validate([

        ]);

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
