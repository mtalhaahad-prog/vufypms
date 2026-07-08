<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\TeamMember;
use App\Models\Team;
use App\Models\Proposal;
use App\Models\Milestone;
use App\Models\Document;

class DocumentController extends Controller
{
    // Required documents ki list (Required Documents card ke liye)
    // DB enum ke exact values: proposal, SRS, design, progress_report, final_report, other
    const REQUIRED_DOCS = [
        'proposal'        => 'Proposal Document',
        'SRS'             => 'SRS Document',
        'design'          => 'Design Document',
        'progress_report' => 'Progress Report',
        'final_report'    => 'Final Report',
    ];

    // ================================================
    // PRIVATE HELPER - Student Ki Team Lao
    // ================================================
    private function getStudentTeam()
    {
        $user    = Auth::user();
        $student = Student::where('user_id', $user->user_id)->first();

        if (!$student) {
            return null;
        }

        $teamMember = TeamMember::where('student_id', $student->student_id)->first();

        if (!$teamMember) {
            return null;
        }

        return Team::find($teamMember->team_id);
    }

    // ================================================
    // DOCUMENTS PAGE SHOW KARO
    // ================================================
    public function index()
    {
        $team = $this->getStudentTeam();

        $documents  = collect();
        $milestones = collect();

        if ($team) {
            // Team ke saare documents (naye pehle)
            $documents = Document::where('team_id', $team->team_id)
                            ->orderBy('uploaded_at', 'desc')
                            ->get();

            // Milestone dropdown ke liye
            $proposal = Proposal::where('team_id', $team->team_id)->first();
            if ($proposal) {
                $milestones = Milestone::where('proposal_id', $proposal->proposal_id)
                                ->orderBy('due_date', 'asc')
                                ->get();
            }
        }

        // Required Documents card ke liye - kaunse types upload ho chuke hain
        $uploadedTypes = $documents->pluck('doc_type')->unique();

        return view('student.documents', [
            'team'          => $team,
            'documents'     => $documents,
            'milestones'    => $milestones,
            'requiredDocs'  => self::REQUIRED_DOCS,
            'uploadedTypes' => $uploadedTypes,
        ]);
    }

    // ================================================
    // DOCUMENT UPLOAD KARO
    // ================================================
    public function store(Request $request)
    {
        $team = $this->getStudentTeam();

        if (!$team) {
            return redirect()->route('student.documents')
                ->with('error', 'You must be part of a team to upload documents.');
        }

        // Validation - file type aur size (20MB = 20480 KB)
        $validated = $request->validate([
            'doc_type'     => 'required|in:proposal,SRS,design,progress_report,final_report,other',
            'milestone_id' => 'nullable|exists:milestones,milestone_id',
            'file'         => 'required|file|mimes:pdf,doc,docx,zip|max:20480',
        ]);

        // File ka asal naam lo aur safe banao
        $file         = $request->file('file');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName     = preg_replace('/[^A-Za-z0-9_-]/', '_', $originalName);
        $extension    = $file->getClientOriginalExtension();

        // Unique filename banao: teamID_docType_timestamp_naam.ext
        $fileName = $team->team_id . '_' . $validated['doc_type'] . '_' . time() . '_' . $safeName . '.' . $extension;

        // File ko storage/app/public/documents mein save karo
        $path = $file->storeAs('documents', $fileName, 'public');

        // Database mein record banao
        Document::create([
            'team_id'      => $team->team_id,
            'milestone_id' => $validated['milestone_id'] ?? null,
            'doc_type'     => $validated['doc_type'],
            'file_path'    => $path,
        ]);

        return redirect()->route('student.documents')
            ->with('success', 'Document uploaded successfully!');
    }

    // ================================================
    // DOCUMENT DOWNLOAD KARO
    // ================================================
    public function download($id)
    {
        $team = $this->getStudentTeam();

        // Sirf apni team ka document download ho sakta hai (security)
        $document = Document::where('document_id', $id)
                        ->where('team_id', $team ? $team->team_id : 0)
                        ->first();

        if (!$document || !Storage::disk('public')->exists($document->file_path)) {
            return redirect()->route('student.documents')
                ->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($document->file_path);
    }

    // ================================================
    // DOCUMENT DELETE KARO
    // ================================================
    public function destroy($id)
    {
        $team = $this->getStudentTeam();

        $document = Document::where('document_id', $id)
                        ->where('team_id', $team ? $team->team_id : 0)
                        ->first();

        if (!$document) {
            return redirect()->route('student.documents')
                ->with('error', 'Document not found.');
        }

        // Pehle file ko disk se delete karo
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Phir database record delete karo
        $document->delete();

        return redirect()->route('student.documents')
            ->with('success', 'Document deleted successfully.');
    }
}