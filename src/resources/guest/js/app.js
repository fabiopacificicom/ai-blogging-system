import '~resources/js/bootstrap';
import * as bootstrap from 'bootstrap';
import '~icons/bootstrap-icons.scss';
import.meta.glob([
    '../img/**'
]);

/* Todo:
Make a separate js file that handles chat features in the admin side so that the homepage loads without unused css and js */
import { Livewire } from '~resources/../vendor/livewire/livewire/dist/livewire.esm';

Livewire.start();


