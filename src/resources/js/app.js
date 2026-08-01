import './bootstrap';

import Alpine from 'alpinejs';
// import collapse from '@alpinejs/collapse'; // プラグインのインポート
// import postForm from './components/postForm';
import postForm from './components/workLogCreate';

//コンポーネントの登録
Alpine.data('postForm', postForm);

// AlpineをグローバルなWindowオブジェクトに登録
window.Alpine = Alpine;

// Alpineの起動
Alpine.start();
