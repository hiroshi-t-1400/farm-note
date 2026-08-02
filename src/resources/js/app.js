import './bootstrap';

import Alpine from 'alpinejs';
// import collapse from '@alpinejs/collapse'; // プラグインのインポート
// import postForm from './components/postForm';
import postForm from './components/workLogCreate';
import indexLog from './components/workLogIndex';

//コンポーネントの登録
Alpine.data('postForm', postForm);
Alpine.data('indexLog',indexLog );

// AlpineをグローバルなWindowオブジェクトに登録
window.Alpine = Alpine;

// Alpineの起動
Alpine.start();
