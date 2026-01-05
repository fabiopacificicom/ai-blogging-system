import '~resources/js/bootstrap';
import * as bootstrap from 'bootstrap';
import '~icons/bootstrap-icons.scss';
import { helix, hourglass } from 'ldrs'

helix.register()
hourglass.register()
import.meta.glob([
    '../img/**'
]);


import { Livewire } from '~resources/../vendor/livewire/livewire/dist/livewire.esm';

Livewire.start();


