<?php

namespace App\Http\Controllers;

use App\Models\Step;
use App\Models\Task;
use App\Models\Task_category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController
{
    public function getTasks(): JsonResponse
    {
        $user_id = auth()->user()->user_id;
        $tasks = Task::where([
            ['user_id', $user_id]
        ])->orderBy('pinned', 'DESC')->orderBy('updated_at', 'DESC')->get();
        return response()->json($tasks);
    }

    public function getCategoryTasks(Request $request): JsonResponse // category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;
        $tasks = Task::where([
            ['user_id', $user_id],
            ['category', $category]
        ])->orderBy('pinned', 'DESC')->orderBy('updated_at', 'DESC')->get();
        return response()->json('tasks', $tasks);
    }

    public function getCategories(): JsonResponse
    {
        $user_id = auth()->user()->user_id;
        $categories = Task_category::where([
            ['user_id', $user_id]
        ])->get();
        return response()->json($categories);
    }

    public function sortByTitle(Request $request): JsonResponse // category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;

        if ($category == 'all') {
            $retrieved_tasks = Task::where([
                ['user_id', $user_id]
            ])->orderBy('pinned', 'DESC')->orderBy('title', 'ASC')->get();
        } else {
            $retrieved_tasks = Task::where([
                ['user_id', $user_id],
                ['category', $category]
            ])->orderBy('pinned', 'DESC')->orderBy('title', 'ASC')->get();
        }
        return response()->json($retrieved_tasks);
    }

    public function sortByDeadline(Request $request): JsonResponse // category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;

        if ($category == 'all') {
            $retrieved_tasks = Task::where([
                ['user_id', $user_id]
            ])->orderBy('pinned', 'DESC')->orderBy('deadline', 'ASC')->get();
        } else {
            $retrieved_tasks = Task::where([
                ['user_id', $user_id],
                ['category', $category]
            ])->orderBy('pinned', 'DESC')->orderBy('deadline', 'ASC')->get();
        }
        return response()->json($retrieved_tasks);
    }

    public function createCategory(Request $request) // category
    {
        $user_id = auth()->user()->user_id;
        $category = $request->category;
        $is_category_found = Task_category::where([
            ['user_id', $user_id],
            ['category', $category]
        ])->first();

        if ($is_category_found == null) {
            $new_category = new Task_category();
            $new_category->user_id = $user_id;
            $new_category->category = $category;
            $new_category->save();
        }
    }

    public function addTask(Request $request) // title, category, description, deadline, pinned, completed
    {
        $user_id = auth()->user()->user_id;
        if($request->title != null){
            $task = new Task();
            $task->user_id = $user_id;
            $task->title = $request->title;
            $task->category = $request->category;
            $task->description = $request->description;
            $task->deadline = $request->deadline;
            $task->pinned = $request->pinned;
            $task->completed = $request->completed;
            $last_task = Task::where('user_id', $user_id)->latest('task_id')->first();
            $task_id = ($last_task != null) ? ($last_task->task_id + 1) : 1;
            $task->task_id =$task_id;
            $task->save();
        }
    }

    public function editTask(Request $request) // task_id, title, category, description, deadline, pinned, completed
    {
        $user_id = auth()->user()->user_id;
        $task_id = $request->task_id;
        $title = $request->title;
        $category = $request->category;
        $description = $request->description;
        $deadline = $request->deadline;
        $pinned = $request->pinned;
        $completed = $request->completed;

        if ($title == null || $task_id == null) {
            return redirect()->back()->withErrors('msg', 'ERROR: null content');
        }
        $task = Task::where([
            ['user_id', $user_id],
            ['task_id', $task_id]
        ])->first();
        $table_empty = Task_category::count();
        $is_category_found = Task_category::where([
            ['user_id', $user_id],
            ['category', $category]
        ])->first();
        if ($is_category_found == null || $table_empty == 0) {
            $new_category = new Task_category();
            $new_category->category = $category;
            $new_category->user_id = $user_id;
            $new_category->save();
        }

        $task->title = $title;
        $task->category = $category;
        $task->description = $description;
        $task->deadline = $deadline;
        $task->pinned = !(($pinned == null) || ($pinned == false));
        $task->completed = !(($completed == null) || ($completed == false));
        $task->save();
    }

    public function editTitle(Request $request) // task_id, title
    {
        $user_id = auth()->user()->user_id;
        $task = Task::where([
            ['task_id', $request->task_id],
            ['user_id', $user_id],
        ])->first();
        $task->title = $request->title;
        $task->save();
    }

    public function editDeadline(Request $request) // task_id, deadline
    {
        $user_id = auth()->user()->user_id;
        $task = Task::where([
            ['task_id', $request->task_id],
            ['user_id', $user_id],
        ])->first();
        $task->deadline = $request->deadline;
        $task->save();
    }

    public function editDescription(Request $request) // task_id, description
    {
        $user_id = auth()->user()->user_id;
        $task = Task::where([
            ['task_id', $request->task_id],
            ['user_id', $user_id],
        ])->first();
        $task->description = $request->description;
        $task->save();
    }

    public function shareAsCopy(Request $request) //task_id, collaborator_username
    {
        $user_id = auth()->user()->user_id;
        $task_id = $request->task_id;
        $collaborator = User::where([
            ['user_id', $request->collaborator_username]
        ])->first();
        if ($collaborator != null) {
            $task = Task::where([
                ['user_id', $user_id],
                ['task_id', $task_id]
            ])->first();
            $new_task = new Task();
            //may generate error
            $new_task->user_id = $request->collaborator_username;
            $new_task->title = $task->title;
            $last_task = Task::where('user_id', $request->collaborator_username)->latest('task_id')->first();
            $new_task->task_id = ($last_task != null) ? ($last_task->task_id + 1) : 1;
            $new_task->category = 'Shared with me';
            //to be deleted
            $new_task->description = $task->description;
            $new_task->deadline = $task->deadline;
            $new_task->pinned = false;
            $new_task->completed = false;
            echo $new_task;
            $new_task->save();
        }
    }

    public function shareAsCollaborator(Request $request)
    {

    }

    public function setAsPinned(Request $request) // task_id, pinned
    {
        $user_id = auth()->user()->user_id;
        $task = Task::where([
            ['task_id', $request->task_id],
            ['user_id', $user_id],
        ])->first();
        $task->pinned = $request->pinned;
        $task->save();
    }

    public function markAsCompleted(Request $request) // task_id, is_completed
    {
        $user_id = auth()->user()->user_id;
        $task = Task::where([
            ['task_id', $request->task_id],
            ['user_id', $user_id]
        ])->first();
        $task->completed = $request->completed;
        $task->save();
        $this->markAllStepsAsCompleted($request);
    }

    public function deleteTask(Request $request)
    {
        $user_id = auth()->user()->user_id;
        $task = Task::where([
            ['user_id', $user_id],
            ['task_id', $request->task_id]
        ]);
        $task->delete();
    }

    public function editCategory(Request $request) // task_id, category
    {
        $user_id = auth()->user()->user_id;
        $task = Task::where([
            ['task_id', $request->task_id],
            ['user_id', $user_id]
        ])->first();
        $task->category = $request->category;
        $task->save();
    }

    public function calculatePerformance(): JsonResponse
    {
        $user_id = auth()->user()->user_id;
        $total_tasks = Task::where([
            ['user_id', $user_id]
        ])->count();
        $completed_tasks = Task::where([
            ['user_id', $user_id],
            ['completed', true]
        ])->count();
        $performance = ($completed_tasks / $total_tasks) * 100;
        return response()->json(ceil($performance));
    }

    public function getSteps(Request $request): JsonResponse // task_id
    {
        $user_id = auth()->user()->user_id;
        $steps = Step::where([
            ['user_id', $user_id],
            ['task_id', $request->task_id]
        ])->orderBy('pinned', 'DESC')->orderBy('created_at', 'ASC')->get();
        return response()->json($steps);
    }

    public function addStep(Request $request) // task_id, step_id, title, step_content, deadline
    {
        $user_id = auth()->user()->user_id;
        $step = new Step();
        $step->user_id = $user_id;
        $step->task_id = $request->task_id;
        $step->step_id = $request->step_id;
        $step->content = $request->step_content;
        $step->deadline = $request->deadline;
        $step->completed = false;
        $step->save();
    }

    public function editStepTitle(Request $request)  // task_id, step_id, title
    {
        $user_id = auth()->user()->user_id;
        $step = Step::where([
            ['user_id', $user_id],
            ['task_id', $request->task_id],
            ['step_id', $request->step_id]
        ])->first();
        $step->title = $request->title;
        $step->save();
    }

    public function editStepDeadline(Request $request)  // task_id, step_id, deadline
    {
        $user_id = auth()->user()->user_id;
        $step = Step::where([
            ['user_id', $user_id],
            ['task_id', $request->task_id],
            ['step_id', $request->step_id]
        ])->first();
        $step->deadline = $request->deadline;
        $step->save();
    }

    public function editStepContent(Request $request)  // task_id, step_id, step_content
    {
        $user_id = auth()->user()->user_id;
        $step = Step::where([
            ['user_id', $user_id],
            ['task_id', $request->task_id],
            ['step_id', $request->step_id]
        ])->first();
        $step->content = $request->step_content;
        $step->save();
    }

    public function markStepCompleted(Request $request)  // task_id, step_id, completed
    {
        $user_id = auth()->user()->user_id;
        $step = Step::where([
            ['user_id', $user_id],
            ['task_id', $request->task_id],
            ['step_id', $request->step_id]
        ])->first();
        $step->completed = $request->completed;
        $step->save();
    }

    public function markAllStepsAsCompleted(Request $request)  // task_id, completed
    {
        $user_id = auth()->user()->user_id;
        $steps = Step::where([
            ['user_id', $user_id],
            ['task_id', $request->task_id],
        ])->get();
        foreach ($steps as $i) {
            $i->completed = $request->completed;
            $i->save();
        }
    }

    public function editStep(Request $request)
    {
        $user_id = auth()->user()->user_id;
        $task_id = $request->task_id;
        $step_id = $request->step_id;
        $title = $request->title;
        $step_content = $request->step_content;
        $deadline = $request->deadline;
        $completed = $request->completed;

        if ($title == null || $task_id == null) {
            return redirect()->back()->withErrors('msg', 'ERROR: null content');
        }
        $step = Step::where([
            ['user_id', $user_id],
            ['task_id', $task_id],
            ['step_id', $step_id]
        ])->first();
        if ($step != null) {
            $step->title = $title;
            $step->content = $step_content;
            $step->deadline = $deadline;
            $step->completed = !(($completed == null) || ($completed == false));
            $step->save();
        }
    }

    public function deleteStep(Request $request) // task_id, step_id
    {
        $user_id = auth()->user()->user_id;

        $step = Step::where([
            ['user_id', $user_id],
            ['task_id', $request->task_id],
            ['step_id', $request->step_id]
        ])->first();
        $step->delete();
    }

}
