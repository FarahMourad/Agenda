<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Task_category;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redirect;
use Mockery\Matcher\Not;
use function Symfony\Component\Translation\t;

class TaskController
{
    public function getTasks(Request $request) // user_id
    {
        $user_id = auth()->user()->user_id;
        $tasks = Task::where([
            ['user_id', $user_id]
        ])->orderBy('pinned', 'DESC')->orderBy('updated_at', 'DESC')->get();
        return response()->json($tasks);
    }

    public function getCategoryTasks(Request $request): JsonResponse // user_id, category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;
        $tasks = Task::where([
            ['user_id', $user_id],
            ['category', $category]
        ])->get();
        return response()->json('tasks', $tasks);
    }

    public function addTask(Request $request)
    {

    }

    public function editTitle(Request $request)
    {

    }

    public function editDeadline(Request $request)
    {

    }

    public function editDescription(Request $request)
    {

    }

    public function setAsPinned(Request $request)
    {

    }

    public function editCategory(Request $request)
    {

    }

    public function markAsCompleted(Request $request)
    {
        // call markAsCompleted for all task steps
    }

    public function sortByTitle(Request $request)
    {

    }

    public function sortByDeadline(Request $request)
    {

    }

    public function calculatePerformance(Request $request)
    {

    }

    public function editTask(Request $request)
    {

    }

    public function shareAsCopy(Request $request)
    {

    }

    public function shareAsCollaborator(Request $request)
    {

    }

    public function deleteTask(Request $request)
    {

    }

}
