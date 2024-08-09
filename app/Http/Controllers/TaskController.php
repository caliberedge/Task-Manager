<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $task = Task::all();
        return response()->json($task);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|unique:tasks,title',
                'description' => 'required|string',
                'priority' => 'required|in:low,medium,high',
                'status' => 'in:active,inactive'
            ]);
        $task = Task::create($request->all());
        return response()->json(['message' => 'Task Created Successfully', 'data'=> $task], 201);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $errorMessage = '';
            foreach ($errors as $fieldErrors) {
                $errorMessage = $fieldErrors[0];
                break;
            }
            return response()->json(['status' => false, 'error' => 'Validation error', 'message' => $errorMessage], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['status' => false, 'message' => 'Task Not Found'], 404);
        }
        return response()->json($task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['status' => false, 'message' => 'Task Not Found'], 404);
        }
        $task->update($request->all());
        return response()->json(['status' => true, 'message' => 'Task Updated Successfully', 'data'=> $task], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['status' => false, 'message' => 'Task Not Found'], 404);
        }
        $task->delete();
        return response()->json(['status' => true, 'message'=>'Task Deleted Successfully'], 200);
    }
}
