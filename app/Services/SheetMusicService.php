<?php

namespace App\Services;

use App\Models\Piece;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SheetMusicService
{
    const MAX_FILES = 5;

    public function upload(Piece $piece, array $uploadedFiles): array
    {
        $existing = $piece->sheet_music_paths ?? [];
        $allowed = self::MAX_FILES - count($existing);

        if ($allowed <= 0) {
            abort(422, 'Maximum of ' . self::MAX_FILES . ' files per piece.');
        }

        $filesToStore = array_slice($uploadedFiles, 0, $allowed);
        $directory = "pdfs/{$piece->user_id}/{$piece->id}";

        foreach ($filesToStore as $file) {
            /** @var UploadedFile $file */
            $filename = Str::uuid() . '_' . $file->getClientOriginalName();
            $file->storeAs($directory, $filename, 'local');
            $existing[] = "{$directory}/{$filename}";
        }

        $piece->update(['sheet_music_paths' => $existing]);

        return $existing;
    }

    public function delete(Piece $piece, string $path): array
    {
        $paths = $piece->sheet_music_paths ?? [];

        if (!in_array($path, $paths)) {
            abort(404, 'File not found on this piece.');
        }

        Storage::disk('local')->delete($path);
        $paths = array_values(array_filter($paths, fn ($p) => $p !== $path));
        $piece->update(['sheet_music_paths' => $paths]);

        return $paths;
    }

    public function deleteAll(Piece $piece): void
    {
        $directory = "pdfs/{$piece->user_id}/{$piece->id}";

        if (Storage::disk('local')->exists($directory)) {
            Storage::disk('local')->deleteDirectory($directory);
        }
    }
}
