<form action="{{ $task ? route('tasks.update', $task) : route('tasks.store') }}" method="POST" class="task-form">
    @csrf
    @if ($task)
        @method('PUT')
    @endif

    <label class="field-label" for="title">Task name <span>REQUIRED</span></label>
    <input
        class="field-input"
        id="title"
        name="title"
        type="text"
        maxlength="120"
        value="{{ old('title', $task?->title) }}"
        placeholder="What needs your attention?"
        required
        autofocus
    >
    @error('title')
        <p class="field-error">{{ $message }}</p>
    @enderror

    <label class="field-label" for="description">Details <span>OPTIONAL</span></label>
    <textarea
        class="field-input field-textarea"
        id="description"
        name="description"
        rows="4"
        maxlength="1000"
        placeholder="Add a note or next step"
    >{{ old('description', $task?->description) }}</textarea>
    @error('description')
        <p class="field-error">{{ $message }}</p>
    @enderror

    <label class="field-label" for="due_date">Due date <span>OPTIONAL</span></label>
    <input
        class="field-input"
        id="due_date"
        name="due_date"
        type="date"
        value="{{ old('due_date', $task?->due_date?->format('Y-m-d')) }}"
    >
    @error('due_date')
        <p class="field-error">{{ $message }}</p>
    @enderror

    @if ($task)
        <label class="field-label" for="status">Status</label>
        <select class="field-input" id="status" name="status">
            <option value="pending" @selected(old('status', $task->status) === 'pending')>Pending</option>
            <option value="completed" @selected(old('status', $task->status) === 'completed')>Completed</option>
        </select>
        @error('status')
            <p class="field-error">{{ $message }}</p>
        @enderror
    @endif

    <button class="button button-gold form-submit" type="submit">
        {{ $task ? 'Save changes' : 'Add to the list' }}
        <span aria-hidden="true">↗</span>
    </button>
</form>