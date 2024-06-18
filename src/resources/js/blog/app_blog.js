import '~resources/js/bootstrap';
import * as bootstrap from 'bootstrap';
//import '~resources/scss/blog/app.scss';
//import '~resources/scss/blog/prism_blog.scss';
/* Todo: The two Prism imports below are required only on certain pages not all */
//import '~resources/js/blog/prism_blog.js';
import.meta.glob([
    '../img/**'
]);

/* Todo:
Make a separate js file that handles chat features in the admin side so that the homepage loads without unused css and js */
import { Livewire, Alpine } from '../../../vendor/livewire/livewire/dist/livewire.esm';

/* DarkMode Keep for both guests/admin */
import theme from '../alpine/theme.js';
Alpine.data('theme', theme);



Livewire.start();


