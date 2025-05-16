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
        $rfids = Rfid::all();
        return view('rfids.index', compact('rfids'));
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
        // Validate the incoming request with all fields from your form
        $validatedData = $request->validate([
            'owner_name' => 'required|string|max:255',
            'uid' => 'required|string|unique:rfids,uid|max:255',
            'phone_number' => 'required|string|max:20',
            'gender' => 'nullable|string|in:Male,Female',
            'address' => 'nullable|string|max:500',
            'birthday' => 'nullable|date',
            'emergency_contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:rfids,email',
        ]);

        // Create a new RFID record with all fields
        Rfid::create($validatedData);

        return redirect()->route('rfids.index')->with('success', 'Worker added successfully');
    }

    // ============================
    // 4. Show Edit Form
    // ============================
    public function edit($id)
    {
        $rfid = Rfid::findOrFail($id);
        return view('rfids.edit', compact('rfid'));
    }

    // ============================
    // 5. Update Worker
    // ============================
    public function update(Request $request, $id)
    {
        $rfid = Rfid::findOrFail($id);
        
        // Validate with unique rules ignoring current record
        $validatedData = $request->validate([
            'owner_name' => 'required|string|max:255',
            'uid' => 'required|string|max:255|unique:rfids,uid,'.$rfid->id,
            'phone_number' => 'required|string|max:20',
            'gender' => 'nullable|string|in:Male,Female',
            'address' => 'nullable|string|max:500',
            'birthday' => 'nullable|date',
            'emergency_contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:rfids,email,'.$rfid->id,
        ]);

        // Update the RFID record
        $rfid->update($validatedData);

        // Update matching checkpoint's owner_name using the same UID
        Checkpoint::where('uid', $rfid->uid)->update(['owner_name' => $rfid->owner_name]);

        return redirect()->route('rfids.index')->with('success', 'Worker updated successfully');
    }

    // ============================
    // 6. Delete Worker
    // ============================
    public function destroy($id)
    {
        $rfid = Rfid::findOrFail($id);
        $rfid->delete();

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