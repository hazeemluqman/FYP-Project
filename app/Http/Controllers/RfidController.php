<?php

namespace App\Http\Controllers;

use App\Models\Checkpoint;
use App\Models\Rfid;
use Illuminate\Http\Request;

class RfidController extends Controller
{
    // ============================
    // 1. Show All Workers (List)
    // ============================
    public function index()
    {
        $rfids = Rfid::all();  // Get all RFID records
        return view('rfids.index', compact('rfids'));  // Pass to view
    }

    // ============================
    // 2. Show Add Worker Form
    // ============================
    public function create()
    {
        return view('rfids.create');
    }

    // ============================
    // 3. Store New Worker (Add)
    // ============================
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'owner_name' => 'required|string',
            'uid' => 'required|string|unique:rfids,uid',
            'phone_number' => 'required|string',
        ]);

        // Create a new RFID record
        Rfid::create($request->all());

        // Redirect back to the RFID index with a success message
        return redirect()->route('rfids.index')->with('success', 'Worker added successfully');
    }

    // ============================
    // 4. Show Edit Form
    // ============================
    public function edit($id)
    {
        $rfid = Rfid::findOrFail($id);  // Find worker by ID
        return view('rfids.edit', compact('rfid'));
    }

    // ============================
    // 5. Update Worker
    // ============================
    public function update(Request $request, $id)
    {
        // Find the RFID record and update it
        $rfid = Rfid::findOrFail($id);
        $rfid->update($request->all());

        // Update matching checkpoint's owner_name using the same UID
        Checkpoint::where('uid', $rfid->uid)->update(['owner_name' => $rfid->owner_name]);

        // Redirect back to the RFID index with a success message
        return redirect()->route('rfids.index')->with('success', 'RFID updated successfully.');
    }

    // ============================
    // 6. Delete Worker
    // ============================
    public function destroy($id)
    {
        // Find the RFID record by ID
        $rfid = Rfid::findOrFail($id);
        $rfid->delete();

        // Redirect back to the RFID index with a success message
        return redirect()->route('rfids.index')->with('success', 'Worker deleted successfully');
    }

    // ============================
    // 7. Show Worker Details
    // ============================
    public function show($id)
    {
        $rfid = Rfid::findOrFail($id);
        return view('rfids.show', compact('rfid'));
    }
}