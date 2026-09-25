import './bootstrap';

import Alpine from 'alpinejs';
// import collapse from '@alpinejs/collapse'; // プラグインのインポート
import tryAuthForm from './components/modules/auth/auth';
import createWorkLog from './components/modules/work-logs/create';
import showSingleLog from './components/modules/work-logs/show';
import indexSimple from './components/modules/work-logs/index';
import editWorkLog from './components/modules/work-logs/edit';

import indexUser from './components/modules/admin/users/index';
import showUser from './components/modules/admin/users/show';
import createUserChangeApplication from './components/modules/admin/applications/users/create';
import reapplyUserChangeApplication from './components/modules/admin/applications/users/reapply';
import editUserChangeApplication from './components/modules/admin/applications/users/edit';
import indexUserChangeApplication from './components/modules/admin/applications/users/index';
import approveUser from './components/modules/admin/approvals/approve';
import indexApprovals from './components/modules/admin/approvals/index';

import indexLog from './components/modules/dashboard/index';
import recentLog from './components/modules/dashboard/recent';

import { registerNetworkStore } from './components/debug/debug';
import { registerAuthStore } from './stores/authStore';

// ストアの登録
registerNetworkStore(Alpine);
registerAuthStore(Alpine);

//コンポーネントの登録
Alpine.data('tryAuthForm', tryAuthForm);
Alpine.data('createWorkLog', createWorkLog);
Alpine.data('showSingleLog', showSingleLog);
Alpine.data('indexSimple', indexSimple);
Alpine.data('editWorkLog', editWorkLog);

// ユーザー情報
Alpine.data('indexUser', indexUser);
Alpine.data('showUser', showUser);
Alpine.data('createUserChangeApplication', createUserChangeApplication);
Alpine.data('reapplyUserChangeApplication', reapplyUserChangeApplication);
Alpine.data('editUserChangeApplication', editUserChangeApplication);
Alpine.data('indexUserChangeApplication', indexUserChangeApplication);
Alpine.data('approveUser', approveUser);
Alpine.data('indexApprovals', indexApprovals);

Alpine.data('indexLog', indexLog);
Alpine.data('recentLog', recentLog);

// AlpineをグローバルなWindowオブジェクトに登録
window.Alpine = Alpine;

// Alpineの起動
Alpine.start();
