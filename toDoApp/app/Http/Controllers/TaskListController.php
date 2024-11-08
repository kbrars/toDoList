<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{

    public function index()
    {
        $tasks = TaskList::orderBy('updated_at', 'DESC')->get();
        return response()->json([
            'tasks' => $tasks
        ], 200);
    }

    public function store(Request $request)
    {
        $task = [
            'title' => $request->get('title'),
            'context' => $request->get('context'),
            'date' => $request->get('date'),
            'due_date' => $request->get('due_date')
        ];

        $add = TaskList::create($task);

        if ($add) {
            return response()->json([
                'status' => 'success',
                'message' => 'Task created successfully!'
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create task!'
        ], 400);
    }


    public function show(TaskList $task)
    {
        return response()->json([
            'task' => $task
        ], 200);
    }


    public function update(Request $request, TaskList $task)
    {
        $updated = $task->update([
            'title' => $request->get('title'),
            'context' => $request->get('context'),
            'date' => $request->get('date'),
            'due_date' => $request->get('due_date')
        ]);

        if ($updated) {
            return response()->json([
                'status' => 'success',
                'message' => 'Task updated successfully!'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update task!'
        ], 400);
    }


    public function destroy(TaskList $task)
    {
        $task->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully!',
            'id' => $task->id
        ], 200);
    }
}