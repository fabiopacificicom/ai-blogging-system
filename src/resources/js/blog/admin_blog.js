import '../bootstrap.js';
import * as bootstrap from 'bootstrap';
/* import '~resources/js/blog/prism_blog.js';
 */
//import '~resources/scss/blog/admin_blog.scss';

import * as bootstrap from 'bootstrap';
import.meta.glob([
    '../img/**'
]);


import { Livewire, Alpine } from '../../../vendor/livewire/livewire/dist/livewire.esm.js';

/* DarkMode Keep for both guests/admin */
import theme from '../alpine/theme.js';
Alpine.data('theme', theme);

/* 🔔 TODO:
All alpine files below needs to be moved into their respective components so that the code loads only
if necessary and not on all pages. 🔔*/

// Only Admin Blog Features
/* Post generation */

import postgen from './postgen.js';
Alpine.data('postgen', postgen);


Livewire.start();


