import '../bootstrap.js';
import * as bootstrap from 'bootstrap';
import '~icons/bootstrap-icons.scss';
import.meta.glob([
    '../img/**'
]);


import { Livewire, Alpine } from '~resources/../vendor/livewire/livewire/dist/livewire.esm';

/* DarkMode Keep for both guests/admin */
/* import theme from '../alpine/theme.js';
Alpine.data('theme', theme); */

import postgen from './postgen.js';
Alpine.data('postgen', postgen);


Livewire.start();


