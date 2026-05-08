import './bootstrap';

// Bootstrap 5 JS（Modal / Dropdown / Tooltip 等）
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Bootstrap Icons の CSS は app.js から import する（SCSS 経由だと Vite がフォントを解決できないため）
import 'bootstrap-icons/font/bootstrap-icons.css';

// SortableJS: 好きなものリスト・SNSリンクの並び替え用（Phase 5/6）
import Sortable from 'sortablejs';
window.Sortable = Sortable;
