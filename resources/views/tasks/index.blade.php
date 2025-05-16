@extends('layouts.app')
 
@section('content')
<div class="card">
    <h2 class="text-center">All Tasks Here</h2>
 
    <div class="card-header d-flex justify-content-between">
        <form id="createForm" method="POST" action="{{ route('tasks.store') }}" class="d-flex gap-2">
            @csrf
           <div class="flex-grow-1 position-relative">
                <input 
                    type="text" 
                    name="title" 
                    id="taskTitle" 
                    class="form-control @error('title') is-invalid @enderror" 
                    placeholder="Enter task"
                    value="{{ old('title') }}"
                >
            </div>
            <button type="submit" class="btn btn-success">Add</button>
        </form>
 
        <a href="{{ route('tasks.all') }}" class="btn btn-primary">Show All Tasks</a>
    </div>
 
    <div class="card-body">
        @if ($tasks->isEmpty())
            <p>No tasks yet.</p>
        @else
            <ul class="list-group" id="taskList">
                @foreach ($tasks as $task)
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $task->id }}">
                        <div>
                            <input type="checkbox" class="complete-task me-2"> {{ $task->title }}
                        </div>
                        <div>
                            <a href="{{ route('tasks.edit', base64_encode($task->id)) }}" class="btn btn-sm btn-warning">Edit</a>
                            <button class="btn btn-sm btn-danger delete-task">Delete</button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Complete Task
$('.complete-task').on('change', function() {
    var li = $(this).closest('li');
    var id = li.data('id');
 
    $.post('/tasks/complete/' + id, {
        _token: '{{ csrf_token() }}'
    }, function(response) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: response.message,
            showConfirmButton: false,
            timer: 3000
        }).then(() => {
            li.remove();
        });
    }).fail(function(xhr) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: xhr.responseJSON?.message || 'Failed to complete task'
        });
    });
});
 
        // Delete Task
        $('.delete-task').on('click', function() {
            var li = $(this).closest('li');
            var id = li.data('id');
 
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this task?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                width: "auto"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/tasks/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            li.remove();
                            if ($('#taskList li').length === 0) {
                                $('#taskList').html('<p>No tasks yet.</p>');
                            }
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Task deleted successfully',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Delete Failed',
                                text: xhr.responseJSON?.message || 'Failed to delete the task'
                            });
                        }
                    });
                }
            });
        });
        // Success Message
        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif
 
        // Validation Error Message
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                `
            });
        @endif
    });
</script>
 