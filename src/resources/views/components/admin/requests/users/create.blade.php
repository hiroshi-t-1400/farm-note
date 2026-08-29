{{-- /var/www/src/resources/views/components/admin/requests/users/create.blade.php --}}


        <template x-if="resultData == {}" x-transition>
            <div class="result-area py-2 px-4 sm:w-[20rem] sm:y-[7rem] mb-4 border-1 border-gray-500 rounded-lg">
                <h3 class="text-base text-gray-700 font-semibold ">申請した内容</h3>
                <dl class="px-1">
                    <div class="py-0.5 flex flex-wrap justify-between">
                        <dt class="text-base font-medium text-gray-600">氏名：</dt>
                        <dd x-text="resultData.name" class="text-base text-gray-800 sm:mt-0 sm:col-span-1"></dd>
                    </div>
                    <div class="py-0.5 flex flex-wrap justify-between">
                        <dt class="text-base font-medium text-gray-600">メールアドレス：</dt>
                        <dd x-text="resultData.email" class="text-base text-gray-800 sm:mt-0 sm:col-span-1"></dd>
                    </div>
                    <div class="py-0.5 flex flex-wrap justify-between">
                        <dt class="text-base font-medium text-gray-600">ログインID：</dt>
                        <dd x-text="resultData.loginId" class="text-base text-gray-800 sm:mt-0 sm:col-span-1"></dd>
                    </div>
                    <div class="py-0.5 flex flex-wrap justify-between">
                        <dt class="text-base font-medium text-gray-600">役職：</dt>
                        <dd x-text="resultData.roleLabel" class="text-base text-gray-800 sm:mt-0 sm:col-span-1"></dd>
                    </div>
                </dl>
            </div>
        </template>

        <x-ui.form-group
            name="username"
            label="お名前"
        >
            <x-ui.input
                type="text"
                name="username"
                x-model="formData.username"
                placeholder="例：アグリ 太郎"
                required
            />
            <span x-show="isUpdate"
                class="px-2 text-gray-600 text-sm font-semibold"
            >
                変更前：
                <span x-text="old.username"></span>
            </span>
        </x-ui.form-group>

        <x-ui.form-group
            name="loginId"
            label="ログインID"
        >
            <x-ui.input
                type="text"
                name="loginId"
                x-model="formData.loginId"
                placeholder="例：nihon_taro"
                required
            />
            <span x-show="isUpdate"
                class="px-2 text-gray-600 text-sm font-semibold"
            >
                変更前：
                <span x-text="old.loginId"></span>
            </span>
        </x-ui.form-group>

        <x-ui.form-group
            name="email"
            label="メールアドレス"
        >
            <x-ui.input
                type="email"
                name="email"
                x-model="formData.email"
                placeholder="例：farm_taro@example.org"
                required
            />
            <span x-show="isUpdate"
                class="px-2 text-gray-600 text-sm font-semibold"
            >
                変更前：
                <span x-text="old.email"></span>
            </span>
        </x-ui.form-group>

        <div x-data="{ show: false }">
                <x-ui.form-group
                    name="password"
                >
                <x-slot:label>
                    <span>パスワード</span>
                    <span x-show="isUpdate" x-text="passwordMessage"></span>
                </x-slot>

                <div class="relative">
                    <x-ui.input
                        ::type="show ? 'text' : 'password'"
                        name="password"
                        x-model="formData.password"
                        placeholder="パスワード"
                        class="w-full"
                    />

                    <button
                        type="button"
                        @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3 text-gray-400 hover:text-gray-600 focus:outline-none"
                        :aria-label="show ? 'パスワードを非表示にする' : 'パスワードを表示する'"
                    >

                        <svg
                            x-show="show"
                            x-cloak
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-[1.5em] w-[1.5em]"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12c1.73-4.387 6-7.5 10.964-7.5s9.234 3.113 10.965 7.5c-1.73 4.387-6 7.5-10.965 7.5S3.766 16.387 2.036 12z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        <svg
                            x-show="!show"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-[1.5em] w-[1.5em]"
                        >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>

                <p class="py-1 ms-5 text-sm text-gray-600 font-semibold">使用できる記号<span class="rounded-md px-4 py-0.5 bg-gray-200">! @ # $ % & * - _ .</span></p>
            </x-ui.form-group>

            <x-ui.form-group
                name="role"
                label="権限"
            >
                <x-ui.select
                    name="role"
                    x-model="formData.role"
                    required
                >
                    <option value="worker" selected>一般ユーザー</option>
                    <option value="manager">管理者</option>
                </x-ui.select>
                <span x-show="isUpdate"
                    class="px-2 text-gray-600 text-sm font-semibold"
                >
                    変更前：
                    <span x-text="old.roleLabel"></span>
                </span>
            </x-ui.form-group>

            <div class="flex py-5 justify-center gap-x-4">
                <x-ui.button
                    type="button"
                    @click="submitCreate()"
                    name="submit" dusk="submit-button"
                    class="w-[10rem]">
                    {{ $submitButton }}
                </x-ui.button>
                <x-ui.button
                    type="href"
                    name="cancel"
                    ::href="backUrl"
                    variant="secondary-ghost"
                    dusk="cancel-button"
                    class="w-[10rem]">
                    キャンセル
                </x-ui.button>
            </div>

        </div>

