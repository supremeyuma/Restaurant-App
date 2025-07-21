import './bootstrap';

import Alpine from 'alpinejs';

// Import the new custom directive
import './directives/scroll-drag'; // Adjust path if you put it elsewhere

window.Alpine = Alpine;

// Add x-collapse if you haven't already for the subcategory accordion effect in the previous suggestion
import collapse from '@alpinejs/collapse'
Alpine.plugin(collapse)


Alpine.start();
