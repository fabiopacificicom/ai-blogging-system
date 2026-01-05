<div>

    <div class="input-group-text gap-2">
        <input class="form-check-input mt-0" type="checkbox" value="" aria-label="Checkbox for following text input" wire:model.live="publishPosts">
        <span>{{$publishPosts ? 'Hide all' : 'Publish all'}}</span>
    </div>

</div>
