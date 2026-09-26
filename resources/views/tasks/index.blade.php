@extends('layouts.app')

@section('title', 'Your Tasks')

@section('content')
<main class="dashboard page-wrap">
    <section class="page-heading">
        <div>
            <p class="eyebrow">YOUR PERSONAL COMMAND CENTER <span> / </span> 01</p>
            <h1>Make the next move.</h1>
            <p class="heading-copy">A clear list. A quieter mind. Start where you are.</p>
        </div>
        <time class="today-stamp" datetime="{{ now()->toDateString() }}">
            <span class="stamp-label">TODAY</span>
            <span>{{ now()->format('D, M j') }}</span>
        </time>
    </section>

    @if (session('success'))
        <div class="flash-message" role="status">{{ session('success') }}</div>
    @endif

    <section class="stats-row" aria-label="Task totals">
        <div class="stat-block stat-total">
            <span class="stat-label">ALL TASKS</span>
            <strong>{{ str_pad((string) $counts['all'], 2, '0', STR_PAD_LEFT) }}</strong>
        </div>
        <div class="stat-block">
            <span class="stat-label">PENDING</span>
            <strong>{{ str_pad((string) $counts['pending'], 2, '0', STR_PAD_LEFT) }}</strong>
        </div>
        <div class="stat-block">
            <span class="stat-label">COMPLETED</span>
            <strong>{{ str_pad((string) $counts['completed'], 2, '0', STR_PAD_LEFT) }}</strong>
        </div>
        <div class="stat-aside">{{ $counts['pending'] === 0 ? 'Nothing outstanding.' : 'One step at a time.' }}</div>
    </section>

    <div class="workspace-grid">
        <section class="task-section" aria-labelledby="task-list-title">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">FIELD NOTES</p>
                    <h2 id="task-list-title">Your task list</h2>
                </div>
                <nav class="filter-nav" aria-label="Filter tasks">
                    <a href="{{ route('tasks.index') }}" @class(['filter-link', 'is-active' => $status === null])>All <span>{{ $counts['all'] }}</span></a>
                    <a href="{{ route('tasks.index', ['status' => 'pending']) }}" @class(['filter-link', 'is-active' => $status === 'pending'])>Pending <span>{{ $counts['pending'] }}</span></a>
                    <a href="{{ route('tasks.index', ['status' => 'completed']) }}" @class(['filter-link', 'is-active' => $status === 'completed'])>Done <span>{{ $counts['completed'] }}</span></a>
                </nav>
            </div>

            <div class="task-list">
                @forelse ($tasks as $task)
                    <article @class(['task-row', 'task-complete' => $task->status === 'completed'])>
                        <form action="{{ route('tasks.status', $task) }}" method="POST" class="status-form">
                            @csrf
                            @method('PATCH')
                            <button
                                class="status-toggle"
                                type="submit"
                                aria-label="{{ $task->status === 'completed' ? 'Mark pending' : 'Mark completed' }}: {{ $task->title }}"
                                title="{{ $task->status === 'completed' ? 'Mark pending' : 'Mark completed' }}"
                            >
                                @if ($task->status === 'completed')
                                    <span aria-hidden="true">✓</span>
                                @endif
                            </button>
                        </form>
                        <div class="task-details">
                            <h3>{{ $task->title }}</h3>
                            @if ($task->description)
                                <p>{{ $task->description }}</p>
                            @endif
                            <div class="task-meta">
                                <span class="status-label">{{ $task->status }}</span>
                                @if ($task->due_date)
                                    <span class="meta-divider">/</span>
                                    <time datetime="{{ $task->due_date->toDateString() }}">Due {{ $task->due_date->format('M j, Y') }}</time>
                                @endif
                            </div>
                        </div>
                        <div class="task-actions">
                            <a class="text-action" href="{{ route('tasks.edit', $task) }}">Edit</a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-action delete-action" type="submit">Delete</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <span class="empty-mark" aria-hidden="true">—</span>
                        <h3>{{ $status ? 'No ' . $status . ' tasks.' : 'Nothing on the board yet.' }}</h3>
                        <p>{{ $status ? 'Choose another filter or add a new task.' : 'Add the first task and get it out of your head.' }}</p>
                    </div>
                @endforelse
            </div>

            @if ($tasks->hasPages())
                <div class="pagination-wrap">{{ $tasks->links() }}</div>
            @endif
        </section>

        <aside class="quick-add" aria-labelledby="quick-add-title">
            <div class="panel-topline"><span>NEW ENTRY</span><span>01—04</span></div>
            <div class="quick-add-heading">
                <span class="plus-mark" aria-hidden="true">+</span>
                <div>
                    <p class="eyebrow">CAPTURE IT</p>
                    <h2 id="quick-add-title">Add a task</h2>
                </div>
            </div>
            @include('tasks._form', ['task' => null])
            <p class="panel-footnote">Small steps count. Keep it moving.</p>
        </aside>
    </div>
</main>
@endsection