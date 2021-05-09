@extends('layouts.main')

@section('title')
    Tasks
@endsection

@section('content')
<div class="md:mx-4 relative overflow-hidden">
    <main class="h-full flex flex-col overflow-auto" id="app">
        <kanban-board :initial-data="{{ $tasks }}"></kanban-board>
    </main>
</div>
@endsection
