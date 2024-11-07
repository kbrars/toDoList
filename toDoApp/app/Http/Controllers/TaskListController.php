<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;



class TaskListController extends Controller
{
    public function getTasks()
    {
        $tasks = TaskList::orderBy('updated_at', 'DESC')->get();
        return response()->json([
            'tasks' => $tasks
        ],200);
    }

    public function updateTask(Request $request)
    {
        $task_id = $request ->get('id');
        $task['title'] = $request ->get('title');
        $task['context'] = $request ->get('context');
        $task['date'] = $request ->get('date');
        $task['due_date'] = $request ->get('due_date');


        $updated =TaskList::where('id', $task_id)
            ->update(['title' =>  $task['title'],
                'context'=> $task['context'],
                'date' => $task['date'],
                'due_date' => $task['due_date']]);

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


    public function addTask(Request $request)
    {


        $task['title'] = $request ->get('title');
        $task['context'] = $request ->get('context');
        $task['date'] = $request ->get('date');
        $task['due_date'] = $request ->get('due_date');

        $add = TaskList::create($task);

        if ($add) {
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

    public function deleteTask(Request $request){
        $task_id = $request ->get('id');
        $task = TaskList::find($task_id);
        $task->delete();
        return $task_id;
    }
}
