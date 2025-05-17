<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Checkpoint; // Use your activity model
use App\Models\User;

class ReportController extends Controller
{
    // Show the report page
    public function index(Request $request)
{
    $date = $request->input('date');
    $name = $request->input('name');
    $checkpoint = $request->input('checkpoint');

    $query = Checkpoint::latest();

    if ($date) {
        $query->whereDate('last_tap_in', $date);
    }
    if ($name) {
        $query->where('owner_name', 'like', '%' . $name . '%');
    }
    if ($checkpoint) {
        $query->where('checkpoint', 'like', '%' . $checkpoint . '%');
    }

    $activities = $query->get();
    $checkpoints = Checkpoint::select('checkpoint')->distinct()->pluck('checkpoint');
    return view('reports.index', compact('activities', 'date', 'name', 'checkpoint', 'checkpoints'));
}

public function downloadPdf(Request $request)
{
    $date = $request->input('date');
    $name = $request->input('name');
    $checkpoint = $request->input('checkpoint');

    $query = Checkpoint::latest();

    if ($date) {
        $query->whereDate('last_tap_in', $date);
    }
    if ($name) {
        $query->where('owner_name', 'like', '%' . $name . '%');
    }
    if ($checkpoint) {
        $query->where('checkpoint', 'like', '%' . $checkpoint . '%');
    }

    $activities = $query->get();
    $pdf = Pdf::loadView('reports.pdf', compact('activities', 'date', 'name', 'checkpoint'));
    return $pdf->download('worker-activity-report.pdf');
}

    
}