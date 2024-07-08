<div class="p-2">
  @include('pacificdev::blog.partials.session')
  @include('pacificdev::blog.partials.validation')

  <!-- Post Generation Scheduler -->
  <h6 class="mt-2">Post Generation Scheduler</h6>
  <form wire:submit.prevent="saveSchedule">
    <!-- Days of the Week for Post Generation -->
    <div class="mb-3">
      @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $index => $day)
      <label class="form-check-label border p-2 m-1 rounded">
        <input class="form-check-input" type="checkbox" wire:model="postGenerationDays.{{ $index }}" value="{{ $index }}" {{in_array($index, $postGenerationDays) ? 'checked' : ''}}>
        {{ $day }}
      </label>
      @endforeach
    </div>

    <div class="mb-3">
      <!-- Time Picker for Post Generation -->
      <label>
        Generation Time:
        <input class="form-control" type="time" wire:model.live="postGenerationTime">
      </label>
    </div>


    <!-- Post Share Scheduler -->
    <div class="mb-3">
      <h6 class="mt-2">Post Share Scheduler</h6>
      <!-- Days of the Week for Post Share -->
      @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $index => $day)
      <label class="form-check-label border p-2 m-1 rounded">
        <input class="form-check-input" type="checkbox" wire:model="postShareDays.{{ $index }}" value="{{ $index }}" {{in_array($index, $postShareDays) ? 'checked' : ''}}>
        {{ $day }}
      </label>
      @endforeach

    </div>
    <div class="mb-3">
      <!-- Time Picker for Post Share -->
      <label>
        Share Time:
        <input class="form-control" type="time" wire:model.live="postShareTime">
      </label>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Save</button>
  </form>

</div>