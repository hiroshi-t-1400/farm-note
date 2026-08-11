import './bootstrap';

import Alpine from 'alpinejs';
// import collapse from '@alpinejs/collapse'; // プラグインのインポート
import createWorkLog from './components/modules/work-logs/create';
import showSingleLog from './components/modules/work-logs/show';
import indexSimple from './components/modules/work-logs/index';
import editWorkLog from './components/modules/work-logs/edit';

import indexLog from './components/modules/dashboard/index';
import recentLog from './components/modules/dashboard/recent';

import { registerNetworkStore } from './components/debug/debug';

// ストアの登録
registerNetworkStore(Alpine);


//コンポーネントの登録
Alpine.data('createWorkLog', createWorkLog);
Alpine.data('showSingleLog', showSingleLog);
Alpine.data('indexSimple', indexSimple);
Alpine.data('editWorkLog', editWorkLog);

Alpine.data('indexLog', indexLog);
Alpine.data('recentLog', recentLog);

// AlpineをグローバルなWindowオブジェクトに登録
window.Alpine = Alpine;

// Alpineの起動
Alpine.start();
