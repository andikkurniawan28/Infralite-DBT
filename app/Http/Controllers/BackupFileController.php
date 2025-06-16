<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class BackupFileController extends Controller
{
    public function index()
    {
        return view('backup_file.index');
    }

    public function data()
    {
        $files = Storage::disk('public')->files('tmp');

        $fileData = collect($files)->map(function ($file) {
            return [
                'name' => basename($file),
                'url' => asset('storage/' . $file),
                'size' => Storage::disk('public')->size($file),
                'last_modified' => Storage::disk('public')->lastModified($file),
            ];
        })->sortByDesc('last_modified')->values();

        return response()->json($fileData);
    }

    public function bulkDelete()
    {
        $files = request()->input('files', []);
        $deleted = [];

        foreach ($files as $file) {
            $path = 'tmp/' . $file;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                $deleted[] = $file;
            }
        }

        return response()->json([
            'message' => count($deleted) . ' file(s) deleted.',
            'deleted' => $deleted,
        ]);
    }

    public function deleteAll()
    {
        $files = Storage::disk('public')->files('tmp');
        Storage::disk('public')->delete($files);

        return response()->json([
            'message' => 'All backup files deleted.',
        ]);
    }
}
