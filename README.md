# farm-note
\
### ディレクトリ構成
laravel-farm-note # プロジェクトルート \
├─ infra/ \
│   ├─ nginx/ \
│   │   └─ default.conf \
│   └─ php/ \
│       ├─ Dockerfile \
│       └─ php.ini # 今回はナシ \
├─ src/ # Laravel本体 \
├─ .env \
├─ docker-compose.yml \
└─ README.md \
\
\
\


## 開発命名規約 (Naming Conventions)


| カテゴリ | 対象 | ケーシング（命名規約） | 例 |
| :--- | :--- | :--- | :--- |
| **1. データベース** | テーブル名 | `snake_case`（複数形） | `work_logs`, `crop_seasons` |
| | カラム名 | `snake_case` | `work_date`, `crop_season_id` |
| | 外部キー | `snake_case`（単数形モデル名_id） | `user_id`, `work_log_id` |
| | 中間テーブル | `snake_case`（アルファベット順・単数形） | `crop_tag` |
| **2. PHP / Laravel** | クラス名 | `PascalCase`（単数形） | `WorkLog`, `WorkLogController` |
| | メソッド名 | `camelCase` | `store()`, `userWorkLogs()` |
| | プロパティ名 | `camelCase` | `$workLogRepository` |
| | クラス定数 | `SCREAMING_SNAKE_CASE` | `STATUS_APPROVED` |
| | 変数名 | `camelCase` | `$workLog`, `$draftWorkLogs` |
| | マイグレーション | `snake_case` | `2026_07_29_000000_create_work_logs_table.php` |
| | 環境変数 (`.env`) | `SCREAMING_SNAKE_CASE` | `APP_ENV`, `DB_HOST` |
| **3. URL・ルーティング** | URLパス | `kebab-case`（複数形名詞） | `/work-logs`, `/crop-seasons/123/edit` |
| | ルート名 | `kebab-case` または `dot.notation` | `work-logs.store`, `crop-seasons.index` |
| | ルートパラメータ | `camelCase` | `{workLog}` |
| **4. ディレクトリ・ビュー** | Viewディレクトリ | `kebab-case` または `snake_case` | `resources/views/work-logs/` |
| | Viewファイル名 | `kebab-case.blade.php` | `create.blade.php`, `index-detail.blade.php` |
| | 一般ディレクトリ | `kebab-case`（`app/` 配下は `PascalCase`） | `resources/js/` |
| **5. Bladeコンポーネント** | 匿名コンポーネント | `kebab-case` | `<x-work-log-card />` |
| | クラスコンポーネント | `PascalCase`（クラス名） / `kebab-case`（タグ名） | `WorkLogCard` / `<x-work-log-card />` |
| **6. JavaScript / Alpine.js** | 変数 / 関数 / プロパティ | `camelCase` | `isOnline`, `submitForm()`, `this.formData` |
| | Alpine データコンポーネント | `camelCase` | `Alpine.data('workLogForm', ...)` |
| | Alpine カスタムイベント | `kebab-case` または `colon:separated` | `$dispatch('work-log-updated')` |
| **7. Livewire** | コンポーネントクラス | `PascalCase` | `WorkLogForm` |
| | Viewファイル名 | `kebab-case.blade.php` | `work-log-form.blade.php` |
| | タグ呼び出し | `kebab-case` | `<livewire:work-log-form />` |
| | プロパティ / メソッド | `camelCase` | `$publicProperty`, `saveWorkLog()` |
| | イベント名 | `kebab-case` または `dot.notation` | `$this->dispatch('work-log-created')` |
| **8. その他** | APIレスポンス (JSON) | `snake_case` または `camelCase`（プロジェクト内統一） | `{"redirect_url": "..."}` |