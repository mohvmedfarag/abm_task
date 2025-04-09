<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class TaskController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except:['index'])
        ];
    }

    public function index(){
        return response()->json(Task::all());
    }

    public function store(Request $request){
        $data = $request->validate([ 
            'title' => ['required', 'string'], 
        ]);

        $task = $request->user()->tasks()->create($data);

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task){

        Gate::authorize('modify', $task);

        $data = $request->validate([
            'title' => ['required', 'string'],
            'status' => ['required', "in:pending,in-progress,completed"]
        ]);

        $task->update($data);

        return $task;
    }

    public function destroy(Task $task)
    {
        Gate::authorize('modify', $task);
        $task->delete();
        return ['message' => "task deleted"];
    }
}
