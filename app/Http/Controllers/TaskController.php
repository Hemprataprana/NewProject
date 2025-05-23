<?php
 
namespace App\Http\Controllers;
 
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
 
class TaskController extends Controller
{
    //displays all the tasks 
    public function index()
    {
        $tasks = Task::where('is_completed', false)->latest()->get();
        return view('tasks.index', compact('tasks'));
    }

 //store all tasks
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                'unique:tasks,title'
            ],
        ], [
            'title.required' => 'The task title is required.',
            'title.unique' => 'A task with this title already exists.',
            'title.max' => 'The task title must not exceed 255 characters.'
        ]);
        $task = Task::create($validatedData);
        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

 //display success msg once we fill the checkbox of any task
public function complete($id)
{
    $task = Task::findOrFail($id);
    $task->update(['is_completed' => true]);
 
    return response()->json([
        'success' => true, 
        'message' => 'Task completed successfully!'
    ]);
}

 //displays all tasks(completed and non-completed)
   public function showAll()
{
    $incompleteTasks = Task::where('is_completed', false)->latest()->get();
    $completedTasks = Task::where('is_completed', true)->latest()->get();
    return view('tasks.all', compact('incompleteTasks', 'completedTasks'));
}
 //delete the tasks
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]); 
    }
 
    //to edit tasks
    public function edit($encodedId)
    {
        $id = base64_decode($encodedId);
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    //to update tasks
    public function update(Request $request, $id)
    {
        $decodedId = base64_decode($id);
        $task = Task::findOrFail($decodedId);
        $validatedData = $request->validate([
            'title' => [
                'required',
                'max:255',
                Rule::unique('tasks', 'title')->ignore($decodedId)
            ]
        ], [
            'title.unique' => 'This task title is already in use.',
            'title.required' => 'Task title is required.',
            'title.max' => 'Task title must not exceed 255 characters.'
        ]);
        $task->update($validatedData);
        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }
}