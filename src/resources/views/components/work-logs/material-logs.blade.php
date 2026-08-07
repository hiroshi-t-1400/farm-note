{{-- 使用資材記録 --}}

    {{-- ヘッダー --}}
<x-ui.form-group>
        <div class="form-label mb-1 font-semibold text-lg">資材の記録</div>
        {{-- 資材タイプフィルター --}}
        <div class="materialLogs_inner">
            <div class="mb-2">
                <span class="block text-sm font-medium text-gray-700 mb-2">種別で絞り込み</span>
                <div class="flex flex-wrap gap-3">
                    <label class="inline-flex items-center">
                        <input type="radio" name="typeFilter" value='' x-model="selectedType" class="form-radio text-blue-600">
                        <span class="ml-1 text-sm">すべて</span>
                    </label>
                    <template x-for="type in types" :key="type.id">
                        <label class="inline-flex items-center">
                            <input type="radio" name="typeFilter" :value="type.id" x-model="selectedType" class="form-radio text-blue-600">
                            <span class="ml-1 text-sm" x-text="type.label"></span>
                        </label>
                    </template>
                </div>
            </div>

            {{-- 資材選択フォーム --}}
            <div class="mb-2">
                <x-ui.select x-model="selectedMaterialId" class="max-w-lg">
                    <x-slot>
                        <option value="">-- 資材を選択してください（<span x-text="filteredMaterials.length"></span>件該当） --</option>
                        <template x-for="material in filteredMaterials" :key="material.id">
                            <option :value="material.id" x-text="isDuplicated(material.id) + material.name + ' | メーカー名：' + (material?.manufacturer || '未登録') ">
                            </option>
                        </template>
                    </x-slot>
                </x-ui.select>
            </div>
            {{-- 動的フォーム追加ボタン --}}
            <div>
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
            </div>

            {{-- 追加フォームボディ --}}
            <template x-for="(materialLog, index) in formData.materialLogs" :key="materialLog.addFormUuid">
                <div class="grid grid-cols-1 bg-white rounded-sm border border-gray-200 p-1" >
                    <div class="flex">
                        <span class="text-sm font-bold self-center" x-text="'資材' + (index + 1)"></span>
                        <button
                            type="button"
                            @click="removeMaterialLog(addFormUuid)"
                            x-show="formData.materialLogs.length > 1"
                            class="px-1 py-1 text-red-600 hover:bg-red-50 rounded-md transition"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                    {{-- 資材情報表示 --}}
                    <div class="grid sm:grid-cols-2 grid-cols-1" >
                        <div class="">
                            <span class="text-xs text-gray-600">種別：</span>
                            <span class="font-medium text-sm text-gray-800" x-text="materialLog.typeLabel"></span>
                        </div>
                        <div class="col-start-1">
                            <span class="text-xs text-gray-600">名称：</span>
                            <span class="font-medium text-sm text-gray-800" x-text="materialLog.name"></span>
                        </div>
                        <div class="">
                            <span class="text-xs text-gray-600">メーカー名：</span>
                            <span class="font-medium text-sm text-gray-800" x-text="materialLog.manufacturer"></span>
                        </div>
                    </div>

                    {{-- ユーザ入力エリア --}}
                    <div class="grid sm:grid-cols-2 grid-cols-1">
                        <x-ui.form-group class="flex self-start px-1" >
                            <label :for="`formData.materialLogs[${index}][quantity]`" class="block font-semibold text-sm text-gray-700">
                                使用量
                            </label>
                            <x-ui.input
                            type="text"
                            x-model="materialLog.quantity"
                            ::name="`formData.materialLogs[${index}][quantity]`"
                            ::id="`formData.materialLogs[${index}][quantity]`"
                            class="max-w-xs"
                            placeholder="例：10本 300L"
                            />
                            <span
                                x-show="getError('quantity', formData.materialLogs[index].addFormUuid)"
                                x-text="getError('quantity', formData.materialLogs[index].addFormUuid)"
                                class="alert alert-danger text-sm text-red-500 font-semibold px-2"
                                role="alert">
                            </span>
                        </x-ui.form-group>

                        <div
                        x-show="materialLog.typeId == 1 || materialLog.typeId == 2"
                        class="grid grid-cols-1"
                        >
                            <x-ui.form-group >
                                <label :for="`formData.materialLogs[${index}][dilutionRate]`" class="block font-semibold text-sm text-gray-700">
                                    希釈倍率
                                </label>
                                <x-ui.input
                                    type="number"
                                    x-model="materialLog.dilutionRate"
                                    ::name="`formData.materialLogs[${index}][dilutionRate]`"
                                    ::id="`formData.materialLogs[${index}][dilutionRate]`"
                                    class="max-w-xs"
                                    placeholder="例：3000"
                                />
                                <span
                                    x-show="getError('dilutionRate', formData.materialLogs[index].addFormUuid)"
                                    x-text="getError('dilutionRate', formData.materialLogs[index].addFormUuid)"
                                    class="alert alert-danger text-sm text-red-500 font-semibold px-2"
                                    role="alert">
                                </span>
                            </x-ui.form-group>
                            <x-ui.form-group >
                                <label :for="`formData.materialLogs[${index}][materialAmount]`" class="block font-semibold text-sm text-gray-700">
                                    原液量
                                </label>
                                <x-ui.input
                                    type="text"
                                    x-model="materialLog.materialAmount"
                                    ::name="`formData.materialLogs[${index}][materialAmount]`"
                                    ::id="`formData.materialLogs[${index}][materialAmount]`"
                                    class="max-w-xs"
                                    placeholder="例：150ml"
                                />
                                <span
                                    x-show="getError('materialAmount', formData.materialLogs[index].addFormUuid)"
                                    x-text="getError('materialAmount', formData.materialLogs[index].addFormUuid)"
                                    class="alert alert-danger text-sm text-red-500 font-semibold px-2"
                                    role="alert">
                                </span>
                            </x-ui.form-group>
                        </div>
                    </div>
                </div>
            </template>

        </div>
</x-ui.form-group>
