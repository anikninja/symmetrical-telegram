<?php

namespace App\Http\Controllers\Api;

use App\Enums\ExportEnum;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Interfaces\NoteRepositoryInterface;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Helpers\ApiResponse;
use App\Http\Requests\SearchNoteRequest;

class NoteController extends Controller
{
    protected NoteRepositoryInterface $noteRepository;

    public function __construct(NoteRepositoryInterface $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function index(): JsonResponse
    {
        $notes = $this->noteRepository->getAllNotes();
        return ApiResponse::response(true, 'Notes retrieved successfully.', $notes);
    }

    public function store(StoreNoteRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $note = $this->noteRepository->createNote($validatedData);

        if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid()) {
            $note->addMediaFromRequest('audio_file')->toMediaCollection('audio_files');
            $note->load('media');
        }

        return ApiResponse::response(true, 'Note created successfully.', $note, null, 201);
    }

    public function show(Note $note): JsonResponse
    {
        $note->load(['type', 'library', 'user', 'language', 'folder', 'media']);

        return ApiResponse::response(true, 'Note retrieved successfully.', $note);
    }

    public function update(UpdateNoteRequest $request, Note $note): JsonResponse
    {
        $validatedData = $request->validated();
        $updatedNote = $this->noteRepository->updateNote($note, $validatedData);

        if ($request->hasFile('audio_file') && $request->file('audio_file')->isValid()) {
            $updatedNote->addMediaFromRequest('audio_file')->toMediaCollection('audio_files');
            $updatedNote->load('media');
        }

        return ApiResponse::response(true, 'Note updated successfully.', $updatedNote);
    }

    public function destroy(Note $note): JsonResponse
    {
        $this->noteRepository->deleteNote($note);

        return ApiResponse::response(true, 'Note deleted successfully.', null, null, 204);
    }

    public function search(SearchNoteRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $query = $validatedData['query'] ?? null;

        if (!$query) {
            return ApiResponse::response(false, 'Search query cannot be empty.', null, null, 400);
        }

        $notes = $this->noteRepository->searchNotes($query);

        return ApiResponse::response(true, 'Notes retrieved successfully.', $notes);
    }

    /**
     * Export a note in various formats (PDF, TXT, Markdown).
     *
     * @return JsonResponse
     */
    public function export(): JsonResponse
    {
        $noteId = request()->route('note');
        $note = $this->noteRepository->getNoteById($noteId);

        if (!$note) {
            return ApiResponse::response(false, 'Note not found.', null, null, 404);
        }

        $format = request()->query('format', ExportEnum::PDF->value); // Default to PDF if no format is specified

        if (!in_array($format, ExportEnum::toValues())) {
            return ApiResponse::response(false, 'Invalid export format.', null, null, 400);
        }

        $content = $this->generateNoteContent($note, $format);

        return match ($format) {
            ExportEnum::PDF->value => $this->exportAsPdf($content),
            ExportEnum::TXT->value => $this->exportAsText($content),
            ExportEnum::MD->value => $this->exportAsMarkdown($content),
            default => ApiResponse::response(false, 'Unsupported format.', null, null, 400),
        };
    }

    private function generateNoteContent(Note $note, string $format): string
    {
        $content = "Title: {$note->title}\n";

        $content .= "Date of Note: {$note->created_at}\n";
        $content .= "Duration: {$note->time_duration}\n";
        $content .= "Exported On: " . now()->toDateTimeString() . "\n";

        $content .= "**Transcript**\n";
        $content .= "{$note->transcript}\n";

        $content .= "**Summary**\n";
        $content .= "{$note->summary}\n";

        if ($format === 'md') {
            $content = "# {$note->title}\n\n";
            $content .= "## Date of Note\n\n{$note->created_at}\n\n";
            $content .= "## Duration\n\n{$note->time_duration}\n\n";
            $content .= "## Transcript\n\n{$note->transcript}\n\n";
            $content .= "## Summary\n\n{$note->summary}\n\n";
        }

        return $content;
    }

    private function exportAsPdf(string $content): JsonResponse
    {
        if (!app()->bound('dompdf.wrapper')) {
            return ApiResponse::response(false, 'PDF export service is not available.', null, null, 500);
        }

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return ApiResponse::response(false, 'PDF export service is not available.', null, null, 500);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML(nl2br(e($content)));
        $pdfContent = $pdf->output();

        return response()->json([
            'success' => true,
            'message' => 'PDF generated successfully.',
            'data' => $pdfContent,
        ]);
    }

    private function exportAsText(string $content): JsonResponse
    {
        $response = response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="note_export.txt"');

        return ApiResponse::response(true, 'Text file generated successfully.', [
            'content' => $response->getContent()
        ]);
    }

    private function exportAsMarkdown(string $content): JsonResponse
    {
        $utf8Content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
        $response = response($utf8Content)
            ->header('Content-Type', 'text/markdown')
            ->header('Content-Disposition', 'attachment; filename="note_export.md"');
        $utf8Content = mb_convert_encoding($content, 'UTF-8', 'auto');
        return ApiResponse::response(true, 'Markdown file generated successfully.', [
            'content' => $response->getContent()
        ]);
    }
    // End of the Note Export Feature
}
