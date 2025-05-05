<?php

namespace App\Http\Controllers;

use App\Models\Checkpoint;
use App\Models\Rfid;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CheckpointController extends Controller
{
    public function index()
    {
        try {
            $uid = request('uid');
            $date = request('date', now()->format('Y-m-d'));
            
            $checkpoints = Checkpoint::with('rfid')
                ->when($uid, function($query, $uid) {
                    return $query->where('uid', $uid);
                })
                ->whereDate('tap_date', $date)
                ->latest()
                ->get();

            $activeWorkers = Checkpoint::whereDate('tap_date', $date)
                ->distinct('uid')
                ->count('uid');
                
            $totalCheckpoints = Checkpoint::whereDate('tap_date', $date)
                ->distinct('checkpoint')
                ->count('checkpoint');

            return view('checkpoints.index', compact(
                'checkpoints',
                'activeWorkers',
                'totalCheckpoints',
                'date'
            ));

        } catch (\Exception $e) {
            Log::error('Error fetching checkpoints: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load checkpoint data');
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'uid' => 'required|exists:rfids,uid',
            'checkpoint' => 'required|string|in:Checkpoint 1,Checkpoint 2,Checkpoint 3',
        ]);

        try {
            $rfid = Rfid::where('uid', $validated['uid'])->firstOrFail();
            $today = now()->format('Y-m-d');

            $existing = Checkpoint::where('uid', $rfid->uid)
                ->where('checkpoint', $validated['checkpoint'])
                ->whereDate('tap_date', $today)
                ->first();

            if ($existing) {
                $existing->update([
                    'last_tap_in' => now(),
                    'tap_date' => $today
                ]);
                
                Log::info("Updated checkpoint record for {$rfid->uid} at {$validated['checkpoint']}");
                
                return response()->json([
                    'status' => 'success',
                    'action' => 'updated',
                    'data' => $existing
                ]);
            }

            $checkpoint = Checkpoint::create([
                'uid' => $rfid->uid,
                'owner_name' => $rfid->owner_name,
                'checkpoint' => $validated['checkpoint'],
                'last_tap_in' => now(),
                'tap_date' => $today
            ]);

            Log::info("Created new checkpoint record for {$rfid->uid} at {$validated['checkpoint']}");

            return response()->json([
                'status' => 'success',
                'action' => 'created',
                'data' => $checkpoint
            ]);

        } catch (\Exception $e) {
            Log::error('Checkpoint store error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing the request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $checkpoint = Checkpoint::findOrFail($id);
            $checkpointData = $checkpoint->toArray(); // For logging before deletion
            $checkpoint->delete();

            Log::info("Deleted checkpoint record", $checkpointData);

            return response()->json([
                'status' => 'success',
                'message' => 'Checkpoint deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Checkpoint deletion failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Checkpoint deletion failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $checkpoint = Checkpoint::with('rfid')->findOrFail($id);
            $availableCheckpoints = ['Checkpoint 1', 'Checkpoint 2', 'Checkpoint 3'];

            return view('checkpoints.edit', compact(
                'checkpoint',
                'availableCheckpoints'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading edit page: ' . $e->getMessage());
            return redirect()->route('checkpoints.index')
                ->with('error', 'Checkpoint not found');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'last_tap_in' => 'required|date',
        ]);

        try {
            $checkpoint = Checkpoint::findOrFail($id);
            $originalTime = $checkpoint->last_tap_in;
            
            $checkpoint->update([
                'last_tap_in' => $validated['last_tap_in'],
                'tap_date' => date('Y-m-d', strtotime($validated['last_tap_in']))
            ]);

            Log::info("Updated checkpoint time", [
                'id' => $id,
                'from' => $originalTime,
                'to' => $validated['last_tap_in']
            ]);

            return redirect()->route('checkpoints.index', ['date' => $checkpoint->tap_date])
                   ->with('success', 'Tap-in time updated successfully.');

        } catch (\Exception $e) {
            Log::error('Error updating checkpoint: ' . $e->getMessage());
            return redirect()->back()
                   ->with('error', 'Failed to update time: '.$e->getMessage());
        }
    }
}