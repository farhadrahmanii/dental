<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OfflineSync;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SyncController extends Controller
{
    /**
     * Sync offline data to the server
     */
    public function sync(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'data' => 'required|array',
                'data.*.model_type' => 'required|string',
                'data.*.model_id' => 'required|string',
                'data.*.action' => 'required|string|in:create,update,delete',
                'data.*.data' => 'required|array',
                'data.*.client_id' => 'required|string',
            ]);

            $syncedCount = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($validated['data'] as $syncItem) {
                try {
                    $result = $this->processSyncItem($syncItem);
                    if ($result['success']) {
                        $syncedCount++;
                    } else {
                        $errors[] = $result['error'];
                    }
                } catch (\Exception $e) {
                    $errors[] = [
                        'client_id' => $syncItem['client_id'],
                        'error' => $e->getMessage()
                    ];
                    Log::error('Sync error: ' . $e->getMessage(), [
                        'sync_item' => $syncItem
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'synced_count' => $syncedCount,
                'total_count' => count($validated['data']),
                'errors' => $errors,
                'message' => "Synced {$syncedCount} items successfully"
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sync failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process individual sync item
     */
    private function processSyncItem(array $syncItem): array
    {
        $modelType = $syncItem['model_type'];
        $action = $syncItem['action'];
        $data = $syncItem['data'];
        $clientId = $syncItem['client_id'];

        // Check if this item was already synced
        $existingSync = OfflineSync::where('client_id', $clientId)->first();
        if ($existingSync && $existingSync->is_synced) {
            return ['success' => true, 'message' => 'Already synced'];
        }

        switch ($modelType) {
            case 'Task':
                return $this->syncTask($action, $data, $clientId);
            default:
                return ['success' => false, 'error' => "Unknown model type: {$modelType}"];
        }
    }

    /**
     * Sync Task model
     */
    private function syncTask(string $action, array $data, string $clientId): array
    {
        try {
            switch ($action) {
                case 'create':
                    $task = Task::create($data);
                    break;
                    
                case 'update':
                    $task = Task::where('client_id', $clientId)->first();
                    if (!$task) {
                        return ['success' => false, 'error' => 'Task not found for update'];
                    }
                    $task->update($data);
                    break;
                    
                case 'delete':
                    $task = Task::where('client_id', $clientId)->first();
                    if (!$task) {
                        return ['success' => false, 'error' => 'Task not found for deletion'];
                    }
                    $task->delete();
                    break;
                    
                default:
                    return ['success' => false, 'error' => "Unknown action: {$action}"];
            }

            // Mark as synced
            OfflineSync::updateOrCreate(
                ['client_id' => $clientId],
                [
                    'model_type' => 'Task',
                    'model_id' => $task->id ?? null,
                    'action' => $action,
                    'data' => $data,
                    'is_synced' => true,
                    'synced_at' => now(),
                ]
            );

            return ['success' => true, 'message' => "Task {$action} synced successfully"];

        } catch (\Exception $e) {
            // Mark sync as failed
            OfflineSync::updateOrCreate(
                ['client_id' => $clientId],
                [
                    'model_type' => 'Task',
                    'model_id' => null,
                    'action' => $action,
                    'data' => $data,
                    'is_synced' => false,
                    'error_message' => $e->getMessage(),
                ]
            );

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get sync status
     */
    public function status(): JsonResponse
    {
        $unsyncedCount = OfflineSync::unsynced()->count();
        $totalCount = OfflineSync::count();
        $lastSync = OfflineSync::synced()->latest('synced_at')->first();

        return response()->json([
            'success' => true,
            'data' => [
                'unsynced_count' => $unsyncedCount,
                'total_count' => $totalCount,
                'last_sync' => $lastSync?->synced_at,
                'is_fully_synced' => $unsyncedCount === 0,
            ]
        ]);
    }
}
