<?php

namespace App\Http\Controllers;

use App\Http\Requests\SheetMusicUploadRequest;
use App\Http\Resources\PieceResource;
use App\Models\Piece;
use App\Services\SheetMusicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SheetMusicController extends Controller
{
    public function __construct(private SheetMusicService $service) {}

    public function upload(SheetMusicUploadRequest $request, Piece $piece)
    {
        if ($piece->user_id !== $request->user()->id) {
            abort(403);
        }

        $this->service->upload($piece, $request->file('files'));

        return new PieceResource($piece->fresh());
    }

    public function show(Request $request, Piece $piece, string $filename)
    {
        if ($piece->user_id !== $request->user()->id) {
            abort(403);
        }

        $paths = $piece->sheet_music_paths ?? [];
        $match = collect($paths)->first(fn ($p) => basename($p) === $filename);

        if (!$match || !Storage::disk('local')->exists($match)) {
            abort(404);
        }

        return Storage::disk('local')->response($match);
    }

    public function destroy(Request $request, Piece $piece)
    {
        if ($piece->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate(['path' => 'required|string']);

        $this->service->delete($piece, $request->input('path'));

        return new PieceResource($piece->fresh());
    }
}
