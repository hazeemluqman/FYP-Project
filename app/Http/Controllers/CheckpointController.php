<?php

namespace App\Http\Controllers;

use App\Models\Checkpoint;
use App\Models\Rfid;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CheckpointController extends Controller
{
    public function index()
    {
        // Get all checkpoints sorted by the most recent
        $checkpoints = Checkpoint::latest()->get();
        
        // Return the checkpoints index view
        return view('checkpoints.index', compact('checkpoints'));
    }

    public function store(Request $request): JsonResponse
    {
        // Validate the incoming data
        $request->validate([
            'uid' => 'required|exists:rfids,uid',  // Ensure the uid exists in the rfids table
            'checkpoint' => 'required|string',    // Check if checkpoint is a valid string
        ]);

        try {
            // Retrieve the RFID entry based on the UID
            $rfid = Rfid::where('uid', $request->uid)->firstOrFail();

            // Check if the user has already tapped in at the same checkpoint
            $existing = Checkpoint::where('uid', $rfid->uid)
                                  ->where('checkpoint', $request->checkpoint)
                                  ->first();

            if ($existing) {
                // If the record exists, update the last tap-in timestamp
                $existing->update([
                    'last_tap_in' => now(),
                ]);
                return response()->json([
                    'status' => 'success',
                    'data' => $existing
                ]);
            }

            // If no existing checkpoint found, create a new one
            $checkpoint = Checkpoint::create([
                'uid' => $rfid->uid,
                'owner_name' => $rfid->owner_name,
                'checkpoint' => $request->checkpoint,
                'last_tap_in' => now(),
            ]);

            // Return the newly created checkpoint data
            return response()->json([
                'status' => 'success',
                'data' => $checkpoint
            ]);
        } catch (\Exception $e) {
            // Handle any potential exceptions and return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing the request.',
                'error' => $e->getMessage()
            ], 500);  // Return a 500 internal server error if something goes wrong
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            // Find and delete the checkpoint by its ID
            $checkpoint = Checkpoint::findOrFail($id);
            $checkpoint->delete();

            // Return a success message upon successful deletion
            return response()->json([
                'status' => 'success',
                'message' => 'Checkpoint deleted successfully'
            ]);
        } catch (\Exception $e) {
            // Handle potential errors in the delete operation
            return response()->json([
                'status' => 'error',
                'message' => 'Checkpoint deletion failed.',
                'error' => $e->getMessage()
            ], 500);  // Return a 500 internal server error
        }
    }
}