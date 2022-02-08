<?php

namespace App\Http\Controllers;

use App\Models\Step;
//use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redirect;
use Mockery\Matcher\Not;
use function Symfony\Component\Translation\t;

class StepController
{
    public function getSteps(Request $request): JsonResponse // task_id
    {
       $steps = Step::where([
           ['task_id', $request->task_id]
       ])->orderBy('pinned', 'DESC')->orderBy('created_at', 'ASC')->get();
       return response()->json($steps);
   }

    public function addStep(Request $request) // task_id, step_id, title, step_content, deadline
    {
        $step = new Step();
        $step->task_id = $request->task_id;
        $step->step_id = $request->step_id;
        $step->content = $request->step_content;
        $step->deadline = $request->deadline;
        $step->completed = false;
        $step->save();
    }

    public function editStepTitle(Request $request)  // task_id, step_id, title
    {
        // $user_id = auth()->user()->user_id;
        $step = Step::where([
            ['task_id', $request->task_id],
            ['step_id', $request->step_id]
        ])->first();
        $step->title = $request->title;
        $step->save();
    }

    public function editStepDeadline(Request $request)  // task_id, step_id, deadline
    {
        // $user_id = auth()->user()->user_id;
        $step = Step::where([
            ['task_id', $request->task_id],
            ['step_id', $request->step_id]
        ])->first();
        $step->deadline = $request->deadline;
        $step->save();
    }

    public function editStepContent(Request $request)  // task_id, step_id, step_content
    {
        // $user_id = auth()->user()->user_id;
        $step = Step::where([
            ['task_id', $request->task_id],
            ['step_id', $request->step_id]
        ])->first();
        $step->content = $request->step_content;
        $step->save();
    }

    public function markAsCompleted(Request $request)  // task_id, step_id, completed
    {
        // $user_id = auth()->user()->user_id;
        $step = Step::where([
            ['task_id', $request->task_id],
            ['step_id', $request->step_id]
        ])->first();
        $step->completed = $request->completed;
        $step->save();
    }

    public function markAllStepsAsCompleted(Request $request)  // task_id, completed
    {
        // $user_id = auth()->user()->user_id;
        $steps = Step::where([
            ['task_id', $request->task_id],
        ])->get();
        foreach ($steps as $i) {
            $i->completed = $request->completed;
            $i->save();
        }
    }

    public function editStep()
    {

    }

    public function deleteStep(Request $request) // task_id, step_id
    {
        $step = Step::where([
            ['task_id', $request->task_id],
            ['step_id', $request->step_id]
        ]);
        $step->delete();
    }
}
