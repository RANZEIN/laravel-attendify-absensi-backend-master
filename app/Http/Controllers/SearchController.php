?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Broadcast;
use App\Models\Task;
use App\Models\Document;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $category = $request->input('category');

        if (empty($query)) {
            return view('search.index');
        }

        $results = [];

        // Filter by category if provided
        if ($category) {
            switch ($category) {
                case 'users':
                    $results['users'] = $this->searchUsers($query);
                    break;
                case 'broadcasts':
                    $results['broadcasts'] = $this->searchBroadcasts($query);
                    break;
                case 'tasks':
                    $results['tasks'] = $this->searchTasks($query);
                    break;
                case 'documents':
                    $results['documents'] = $this->searchDocuments($query);
                    break;
                default:
                    return redirect()->route('search');
            }
        } else {
            // Search across all categories
            $results['users'] = $this->searchUsers($query);
            $results['broadcasts'] = $this->searchBroadcasts($query);
            $results['tasks'] = $this->searchTasks($query);
            $results['documents'] = $this->searchDocuments($query);
        }

        return view('search.results', compact('results', 'query', 'category'));
    }

    public function apiSearch(Request $request)
    {
        $query = $request->input('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // Get top 3 results from each category
        $users = $this->searchUsers($query, 3);
        $broadcasts = $this->searchBroadcasts($query, 3);
        $tasks = $this->searchTasks($query, 3);
        $documents = $this->searchDocuments($query, 3);

        // Format results for the dropdown
        foreach ($users as $user) {
            $results[] = [
                'type' => 'user',
                'title' => $user->name,
                'subtitle' => $user->email,
                'url' => route('users.show', $user->id)
            ];
        }

        foreach ($broadcasts as $broadcast) {
            $results[] = [
                'type' => 'broadcast',
                'title' => $broadcast->title,
                'subtitle' => 'Broadcast · ' . $broadcast->created_at->format('M d, Y'),
                'url' => route('broadcasts.show', $broadcast->id)
            ];
        }

        foreach ($tasks as $task) {
            $results[] = [
                'type' => 'task',
                'title' => $task->title,
                'subtitle' => 'Task · ' . ucfirst($task->status),
                'url' => route('tasks.show', $task->id)
            ];
        }

        foreach ($documents as $document) {
            $results[] = [
                'type' => 'document',
                'title' => $document->title,
                'subtitle' => 'Document · ' . $document->file_type,
                'url' => route('documents.show', $document->id)
            ];
        }

        return response()->json(['results' => $results]);
    }

    private function searchUsers($query, $limit = 10)
    {
        return User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    private function searchBroadcasts($query, $limit = 10)
    {
        return Broadcast::where('title', 'like', "%{$query}%")
            ->orWhere('message', 'like', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    private function searchTasks($query, $limit = 10)
    {
        return Task::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    private function searchDocuments($query, $limit = 10)
    {
        return Document::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('file_name', 'like', "%{$query}%")
            ->limit($limit)
            ->get();
    }
}
