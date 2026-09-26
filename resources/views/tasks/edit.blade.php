@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
<main class="edit-page page-wrap">
    <a class="back-link" href="{{ route('tasks.index') }}"><span aria-hidden="true">←</span> Back to your list</a>
    <section class="edit-panel">
        <p class="eyebrow">FIELD NOTE {{ str_pad((string) $task->id, 4, '0', STR_PAD_LEFT) }}</p>
        <h1>Refine the plan.</h1>
        <p class="heading-copy">Update the details or move this task to completed.</p>
        @include('tasks._form', ['task' => $task])
    </section>
</main>
@endsection