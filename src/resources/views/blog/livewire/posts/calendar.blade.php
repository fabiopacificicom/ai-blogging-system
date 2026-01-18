<div class="p-2">
  @include('pacificdev::blog.partials.session')
  @include('pacificdev::blog.partials.validation')

  <form wire:submit.prevent="saveSchedule">
    
    <!-- Timezone Selector -->
    <div class="mb-4 p-3 border rounded bg-light">
      <h6 class="mb-2">⏰ Scheduler Timezone</h6>
      <select wire:model.live="schedulerTimezone" class="form-select">
        <option value="UTC">UTC (Coordinated Universal Time)</option>
        <option value="Europe/Rome">Europe/Rome (CET/CEST)</option>
        <option value="Europe/London">Europe/London (GMT/BST)</option>
        <option value="America/New_York">America/New_York (EST/EDT)</option>
        <option value="America/Los_Angeles">America/Los_Angeles (PST/PDT)</option>
        <option value="Asia/Tokyo">Asia/Tokyo (JST)</option>
      </select>
      <small class="text-muted">Selected timezone: {{ $schedulerTimezone }}</small>
    </div>

    <!-- Post Generation Scheduler -->
    <h6 class="mt-2">Post Generation Scheduler</h6>
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

    <!-- Multiple Share Times Per Day -->
    <div class="mb-3 p-3 border rounded">
      <h6 class="mb-2">📅 Multiple Shares Per Day</h6>
      <div class="mb-2">
        <label class="form-label">Max shares per day:</label>
        <input type="number" wire:model.live="maxSharesPerDay" min="1" max="10" class="form-control" style="max-width: 100px;">
      </div>
      <div class="mb-2">
        <label class="form-label">Share times (up to {{ $maxSharesPerDay }} times):</label>
        @for($i = 0; $i < $maxSharesPerDay; $i++)
          <div class="mb-2">
            <label class="text-muted">Share {{ $i + 1 }}:</label>
            <input type="time" wire:model.defer="postShareTimes.{{ $i }}" class="form-control" style="max-width: 150px;">
          </div>
        @endfor
      </div>
      <small class="text-muted">💡 Each selected time will trigger one share on the configured days. Leave empty to auto-generate times.</small>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Save</button>
  </form>

</div>