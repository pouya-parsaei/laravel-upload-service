<?php

namespace App\Http\Controllers;

use App\Http\Requests\File\StoreRequest;
use App\Models\File;
use App\Services\Uploader\Uploader;

class FileController extends Controller
{
    public function __construct(private Uploader $uploader)
    {

    }

    public function show(File $file)
    {
        return $file->download();
    }

    public function index()
    {
        $files = File::alL();

        return view('files.index', compact('files'));
    }

    public function create()
    {
        return view('files.create');
    }

    public function store(StoreRequest $request)
    {
        try {

            $this->uploader->upload();

            return redirect()->back()->withSuccess('file has uploaded successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function destroy(File $file)
    {
        $file->delete();

        return back();
    }
}
