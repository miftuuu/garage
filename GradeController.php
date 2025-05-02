<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;  
 


class GradeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $grades = $user->grades()->get();
        $gpa = $this->calculateGPA($grades);
    
        return view('dashboard', compact('grades', 'gpa'));
    }

    public function dashboard()
    {
        $grades = auth()->user()->grades;
        $gpa = $grades->avg('grade');

        return view('dashboard', compact('grades', 'gpa'));
    }

    private function calculateGPA($grades)
    {
        if (!$grades || $grades->isEmpty()) return 0;

        $total = $grades->sum('grade');
        return round($total / count($grades), 2);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'grade' => 'required|numeric|min:0|max:4'
        ]);

        Grade::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'grade' => strtoupper($request->grade)
        ]);

        return redirect()->route('dashboard')->with('success', 'Grade added successfully!');
    }

    public function retakes()
    {
        $retakeSubjects = auth()->user()->grades->filter(function ($grade) {
            return $grade->grade < 2;
        });

        return view('retakes', compact('retakeSubjects'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'grade' => 'required|numeric|min:0|max:4',
        ]);

        $grade = Grade::findOrFail($id);

        if ($grade->user_id !== auth()->id()) {
            abort(403);
        }

        $grade->grade = $request->grade;
        $grade->save();

        return redirect()->route('dashboard')->with('success', 'Grade updated successfully!');
    }

    public function destroy($id)
    {
        $grade = Grade::findOrFail($id);

        if ($grade->user_id !== auth()->id()) {
            abort(403);
        }

        $grade->delete();

        return redirect()->route('dashboard')->with('success', 'Grade deleted successfully!');
    }

    public function approve($id)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $grade = Grade::findOrFail($id);
        $grade->is_approved = true; // Make sure your 'grades' table has 'is_approved' column if needed
        $grade->save();

        return redirect()->route('grades.retakes')->with('success', 'Grade retake approved successfully.');
    }

    public function downloadPDF()
    {
    $grades = auth()->user()->grades;
    $gpa = $grades->avg('grade');

    $pdf = Pdf::loadView('dashboard-pdf', compact('grades', 'gpa'));

    return $pdf->download('dashboard_summary.pdf');
    }

    }
    
