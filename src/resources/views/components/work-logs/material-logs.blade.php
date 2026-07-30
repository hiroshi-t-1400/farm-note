{{-- 使用資材記録 --}}

    {{-- ヘッダー --}}
    <div class="grid grid-cols-1 bg-white mb-1 px-1 py-2">
        <div class="form-label mb-1 font-semibold text-lg">資材の記録</div>
        {{-- 資材タイプフィルター --}}
        <div class="material_logs_inner">
            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">種別で絞り込み</label>
                <div class="flex flex-wrap gap-3">
                    <label class="inline-flex items-center">
                        <input type="radio" name="type_filter" value='' x-model="selectedType" class="form-radio text-blue-600">
                        <span class="ml-1 text-sm">すべて</span>
                    </label>
                    <template x-for="type in types" :key="type.id">
                        <label class="inline-flex items-center">
                            <input type="radio" name="type_filter" :value="type.id" x-model="selectedType" class="form-radio text-blue-600">
                            <span class="ml-1 text-sm" x-text="type.label"></span>
                        </label>
                    </template>
                </div>
            </div>

            {{-- 資材選択フォーム --}}
            <div class="mb-2">
                <select x-model="selectedMaterialId"
                        class="w-full border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- 資材を選択してください（<span x-text="filteredMaterials.length"></span>件該当） --</option>
                    <template x-for="material in filteredMaterials" :key="material.id">
                        <option :value="material.id" x-text="isDuplicated(material.id) + material.name + ' | メーカー名：' + (material?.manufacturer || '未登録') ">
                        </option>
                    </template>
                </select>
            {{-- 動的フォーム追加ボタン --}}
            <button
                type="button"
                :disabled="selectedMaterialId === ''"
                @click="addMaterialLogs()"
                class="mt-1 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                資材を追加する
            </button>

            {{-- 追加フォームボディ --}}
            <template x-for="(material_log, index) in formData.material_logs" :key="material_log.addForm_uuid">
                <div class="grid sm:grid-cols-2 rounded-md border border-gray-200 text-sm">
                    <div class="">
                        <span class="" x-text="'資材' + (index + 1)"></span>
                        <button
                            type="button"
                            @click="removeMaterial_log(addForm_uuid)"
                            x-show="formData.material_logs.length > 1"
                            class="px-1 py-1 text-red-600 hover:bg-red-50 rounded-md transition"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                    {{-- 資材情報表示 --}}
                    <div class="sm:col-span-2">
                        <span class="text-xs text-gray-600">種別：</span>
                        <span class="font-medium text-gray-900" x-text="material_log.type_label"></span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-600">名称：</span>
                        <span class="font-medium text-gray-900" x-text="material_log.name"></span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-600">メーカー名：</span>
                        <span class="font-medium text-gray-900" x-text="material_log.manufacturer"></span>
                    </div>

                    {{-- ユーザ入力エリア --}}
                    <div class="grid sm:grid-cols-[auto_1fr] gap-x-4 px-2 m-0.5 ">
                        <label :for="`formData.material_logs[${index}][quantity]`" >使用量</label>
                        <div class="form-parts-block  sm:col-span-2">
                            <input type="text"
                            :name="`formData.material_logs[${index}][quantity]`"
                            x-model="material_log.quantity"
                            class="rounded-md outline-2 outline-gray-600 px-2 m-0.5"
                            placeholder="例：10本 300L"
                            >
                        </div>
                        <div class="form-parts-block  sm:col-span-2">
                            <x-common.form.error field='quantity' addUuid='material_log.addForm_uuid' />
                        </div>
                    </div>

                    <div
                        x-show="material_log.type_id == 1 || material_log.type_id == 2"
                        class="grid sm:grid-cols-[auto_1fr] gap-x-4 px-2 m-0.5 ">
                        <label :for="`formData.material_logs[${index}][dilution_rate]`" >希釈倍率</label>
                        <div class="form-parts-block  sm:col-span-2">

                            <input
                            type="text"
                            :name="`formData.material_logs[${index}][dilution_rate]`"
                            x-model="material_log.dilution_rate"
                            class="rounded-md outline outline-2 outline-gray-600 px-2 m-0.5"
                            placeholder="例：150"
                            >
                        </div>
                        <div class="form-parts-block  sm:col-span-2">
                            <x-common.form.error field='dilution_rate' addUuid='material_log.addForm_uuid' />
                        </div>
                    </div>

                    <div
                        x-show="material_log.type_id == 1 || material_log.type_id == 2"
                        class="grid sm:grid-cols-[auto_1fr] sm:col-start-2 gap-x-4 px-2 m-0.5 ">
                        <label :for="`formData.material_logs[${index}][material_amount]`" >原液量</label>
                        <div class="form-parts-block  sm:col-span-2">

                            <input
                            type="text"
                            :name="`formData.material_logs[${index}][material_amount]`"
                            x-model="material_log.material_amount"
                            class="rounded-md outline-2 outline-gray-600 px-2 m-0.5"
                            placeholder="例：150"
                            >
                        </div>
                        <div class="form-parts-block  sm:col-span-2">
                            <x-common.form.error field='material_amount' addUuid='material_log.addForm_uuid' />

                        </div>
                    </div>
                </div>
            </template>

        </div>

    </div>
