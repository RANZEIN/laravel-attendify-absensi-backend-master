<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskControllerApi extends Controller
{
    /**
     * Get all tasks for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks
        ]);
    }

    /**
     * Get tasks by status for the authenticated user.
     *
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasksByStatus($status)
    {
        // Validate status
        if (!in_array($status, ['todo', 'in_progress', 'done'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid status provided'
            ], 400);
        }

        $tasks = Task::where('user_id', Auth::id())
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks
        ]);
    }

    /**
     * Get task summary for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary()
    {
        $userId = Auth::id();

        $todoCount = Task::where('user_id', $userId)
            ->where('status', 'todo')
            ->count();

        $inProgressCount = Task::where('user_id', $userId)
            ->where('status', 'in_progress')
            ->count();

        $doneCount = Task::where('user_id', $userId)
            ->where('status', 'done')
            ->count();

        return response()->json([
            'status' => 'success',
            'message' => 'Task summary retrieved successfully',
            'data' => [
                'todo' => $todoCount,
                'in_progress' => $inProgressCount,
                'done' => $doneCount
            ]
        ]);
    }

    /**
     * Get today's tasks for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTodayTasks()
    {
        $today = now()->format('Y-m-d');

        $tasks = Task::where('user_id', Auth::id())
            ->whereDate('due_date', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Today\'s tasks retrieved successfully',
            'data' => $tasks
        ]);
    }

    /**
     * Store a newly created task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'nullable|in:todo,in_progress,done',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // If user_id is not provided, assign to the authenticated user
        if (!$request->has('user_id')) {
            $request->merge(['user_id' => Auth::id()]);
        }

        // Set the assigned_by field to the authenticated user
        $request->merge(['assigned_by' => Auth::id()]);

        $task = Task::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }

    /**
     * Display the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found'
            ], 404);
        }

        // Check if the authenticated user is authorized to view this task
        if ($task->user_id !== Auth::id() && $task->assigned_by !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Task retrieved successfully',
            'data' => $task
        ]);
    }

    /**
     * Update the specified task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found'
            ], 404);
        }

        // Check if the authenticated user is authorized to update this task
        if ($task->user_id !== Auth::id() && $task->assigned_by !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high',
            'status' => 'nullable|in:todo,in_progress,done',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $task->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Task updated successfully',
            'data' => $task
        ]);
    }

    /**
     * Update the status of a task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found'
            ], 404);
        }

        // Check if the authenticated user is authorized to update this task
        if ($task->user_id !== Auth::id() && $task->assigned_by !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:todo,in_progress,done',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $task->status = $request->status;
        $task->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Task status updated successfully',
            'data' => $task
        ]);
    }

    /**
     * Remove the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found'
            ], 404);
        }

        // Check if the authenticated user is authorized to delete this task
        if ($task->user_id !== Auth::id() && $task->assigned_by !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $task->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Task deleted successfully'
        ]);
    }
}
