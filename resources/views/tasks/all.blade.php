@extends('layouts.app')
 
@section('content')
<div class="card">
    <h2 class="text-center">All Tasks</h2>
 
    <div class="card-body">
        <h3>Incomplete Tasks</h3>
        @if ($incompleteTasks->isEmpty())
            <p>No incomplete tasks.</p>
        @else
            <ul class="list-group mb-4" id="incompleteTasks">
                @foreach ($incompleteTasks as $task)
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $task->id }}">
                        <div>
                            <input type="checkbox" class="complete-task me-2"> {{ $task->title }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
 
        <h3>Completed Tasks</h3>
        @if ($completedTasks->isEmpty())
            <p>No completed tasks.</p>
        @else
            <ul class="list-group" id="completedTasks">
                @foreach ($completedTasks as $task)
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $task->id }}">
                        <div>
                            <input type="checkbox" class="complete-task me-2" checked disabled> {{ $task->title }}
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
 
@push('scripts')
<script>
    $(document).ready(function() {
    //complete task once we hit the checkbox
        $('.complete-task:not(:disabled)').on('change', function() {
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
                    // Move the task to completed tasks
                    li.find('input[type="checkbox"]').prop('checked', true).prop('disabled', true);
                    $('#completedTasks').append(li);
 
                    // Remove from incomplete tasks
                    if ($('#incompleteTasks li').length === 0) {
                        $('#incompleteTasks').html('<p>No incomplete tasks.</p>');
                    }
                });
            }).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Failed to complete task'
                });
            });
        });
    });
</script>
@endpush
@endsection