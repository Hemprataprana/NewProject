@extends('layouts.app')
 
@section('content')
<div class="card">
    <div class="card-header">Edit Task</div>
    <div class="card-body">
        <form method="POST" action="{{ route('tasks.update', base64_encode($task->id)) }}">
            @csrf
 
            <div class="mb-3 position-relative">
                <input
                    type="text"
                    name="title"
                    class="form-control @error('title') is-invalid @enderror"
                    value="{{ old('title', $task->title) }}"
                    placeholder="Enter task title">
                @error('title')
                <div class="invalid-feedback position-absolute">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Update Task</button>
            </div>
        </form>
    </div>
</div>
<script>
    //success message
    $(document).ready(function() {
        @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session('
            success ') }}',
            showConfirmButton: false,
            timer: 3000
        });
        @endif
 
        @if($errors -> any())
        // Prevent SweetAlert if there are input errors
        @if(!$errors -> has('title'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            html: `
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    `
        });
        @endif
        @endif
    });
</script>
 
@endsection