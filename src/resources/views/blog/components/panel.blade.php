@props(['route','title','value'])
<div {{$attributes}}>

    <a href="{{$route}}" class="text-center text-decoration-none d-block h-100" wire:navigate>


        <div class="card shadow h-100 d-flex flex-column justify-content-center" class="bg-secondary-subtle">
            {{$icon ?? ''}}
            <h6 class="card-title fs_sm">{{$title}}</h6>
            <h3 class="card-text">{{$value}}</h2>
                {{$slot}}
        </div>


    </a>
</div>
