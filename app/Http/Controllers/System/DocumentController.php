<?php

namespace App\Http\Controllers\System;

use Log;
use Storage;
use Str;
use File as Files;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Modules\Core\Entities\SintagFile;

class DocumentController extends Controller
{
    protected $disk;

    public function __construct()
    {
        $this->disk = 'konseling';
    }

    public function editor($request, $id, $mode = null, $type = null)
    {
        $mode = $mode ?? 'view';
        $type = $type ?? $_GET['type'] ?? '';
        $author = (object) [
            'id' => auth()->user()->email,
            'name' => auth()->user()->name,
        ];

        $document = (object) [
            'title' => Str::title($id),
            'createdAt' => '',
            'lastModified' => Storage::disk($this->disk)->lastModified($id),
            'filename' => $id,
            'key' => sprintf(
                '%s.%s',
                config('docserver.app_id'),
                substr(sha1(Storage::disk($this->disk)->lastModified($id) . $id), 0, 16)
            ),
            'url' => route('doc.get', $id),
            'returnUrl' => url('/'),
            'permission' => [
                'download' => true,
                'edit' => $mode == 'edit',
                'print' => true,
                'review' => true
            ],

            'logo' => [
                'image' => url("/assets/app/media/img/logos/logo-1.png"),
                // 'url' => url('master/tagihan/disposisi/1054DF20-72C0-11E9-804E-2D333CDE6C7D'),
                'url' => url('master/tagihan'),

            ],

            'plugins' => [
                // "autostart" => [
                //     "asc.{81E022EA-AD92-45FC-B221-49DF39746DB4}"
                // ],
                "pluginsData" => [
                    url("/ds-assets/plugins/reference/config.json")

                ]
            ],
        ];

        // logActivity('default', 'Dokumen')->log('Show Dokumen ' . $id);
        return view('doc', compact('id', 'mode', 'type', 'author', 'document'));
    }

    public function view(Request $request, $id)
    {
        return $this->editor($request, $id);
    }

    public function edit(Request $request, $id)
    {
        return $this->editor($request, $id, 'edit');
    }

    public function full($id)
    {
        $file = SintagFile::where('NmFile', $id)->first();
        $otherFile = ['SQL', 'RAR', 'ZIP', 'EXE', 'MP4', 'MP3'];
        if(in_array(strtoupper($file->extasli), $otherFile)) {
            $path = storage_path('app/tagihan/' . $file->NmFile);

            if (file_exists($path)) {
                return Response::download($path, $file->OriginalNmFile);
            }
            abort(404, 'File tidak ditemukan');

        }
        return view('core::doc_fullscreen', compact('file'));
    }

    public function get(Request $request, $id)
    // public function get(Request $request, $id, $userid)
    {
        Log::info('Masuk ke get');
        if (!$request->hasValidSignature()) {
            // abort(401);
        }
        $files = Storage::disk($this->disk)->get($id);
        $response = Response::make($files, 200);
        return $response;

        // TODO permission checking here
        // Assume userid is the currently logged-in user while the signature hasnt expired
        // return Storage::disk($this->disk)->get($id);
    }

    public function downloadFile($file)
    {
        # code...
    }
}
