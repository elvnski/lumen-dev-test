<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{

    public function index(Request $request){

        $query = Task::query();

        //Filtering Tasks by Status
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        // FilteringTasks by due_date
        if ($request->has('due_date')) {
            $query->where('due_date', $request->get('due_date'));
        }

        // Search for a Task by title (case-insensitive)
        if ($request->has('search')) {
            $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($request->get('search')) . '%']);
        }

        // Adding Task Pagination with per_page user customization
        $tasks = $query->paginate($request->per_page ?? 10);

        return response()->json($tasks);
    }

    public function show($id)
    {
        try {

            $task = Task::findOrFail($id);
            return response()->json($task);
        }
        catch (ModelNotFoundException $e) {

            return response()->json([
                'message' => 'The task you are trying to access does not exist.',
                'error' => 'Task not found.'
            ], 404);
        }
        catch (Exception $e) {

            return response()->json([
                'message' => 'Task could not be retrieved.',
                'error' => 'An unexpected error occurred.'
            ], 500);
        }

    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', 'string', 'unique:tasks,title', 'max:255'],
            'due_date' => ['required', 'date', 'after:today'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:pending,completed']
        ]);

        try{

            $task = Task::create($request->all());
        }
        catch(Exception $e){

            return response()->json([
                'message' => 'Task could not be created.',
                'error' => 'An unexpected error occurred.'
            ], 500);
        }

        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $this->validate($request, [
            'title' => ['nullable', 'max:255', Rule::unique('tasks')->ignore($id)],
            'due_date' => ['nullable', 'date', 'after:today'],
            'description' => ['nullable', 'string'],
            'status' => ['string', 'nullable', 'in:pending,completed'],
        ]);

        try {

            $task = Task::findOrFail($id);
            $task->update($request->all());

            // Returns the updated task
            return response()->json($task, 200);

        }
        catch (ModelNotFoundException $e) {

            // Return a specific error message to the user if the task is not found
            return response()->json([
                'message' => 'Task not found.',
                'error' => 'The task you are trying to update does not exist.'
            ], 404);
        }
        catch (Exception $e) {

            return response()->json([
                'message' => 'Task could not be updated.',
                'error' => 'An unexpected error occurred.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {

            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json("Task ID " . $id . " Deleted Successfully.", 200);
        }
        catch (ModelNotFoundException $e) {

            return response()->json([
                'message' => 'Task not found.',
                'error' => 'The task you are trying to delete does not exist.'
            ], 404);
        }
        catch (Exception $e){

            return response()->json([
                'message' => 'Task could not be deleted.',
                'error' => 'An unexpected error occurred.'
            ], 500);
        }
    }
}
