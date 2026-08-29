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
// import createUserChangeRequest from './components/modules/admin/requests/users/createRequest';
import createUserChangeRequest from './components/modules/admin/requests/users/create';
import updateUserChangeRequest from './components/modules/admin/requests/users/updateRequest';
import editUserChangeRequest from './components/modules/admin/requests/users/edit';
import indexUserChangeRequest from './components/modules/admin/requests/users/index';
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
Alpine.data('createUserChangeRequest', createUserChangeRequest);
Alpine.data('updateUserChangeRequest', updateUserChangeRequest);
Alpine.data('editUserChangeRequest', editUserChangeRequest);
Alpine.data('indexUserChangeRequest', indexUserChangeRequest);
Alpine.data('approveUser', approveUser);
Alpine.data('indexApprovals', indexApprovals);

Alpine.data('indexLog', indexLog);
Alpine.data('recentLog', recentLog);

// AlpineをグローバルなWindowオブジェクトに登録
window.Alpine = Alpine;

// Alpineの起動
Alpine.start();
