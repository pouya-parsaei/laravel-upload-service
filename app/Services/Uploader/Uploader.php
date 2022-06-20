<?php

namespace App\Services\Uploader;

use App\Exceptions\FileExistsException;
use App\Models\File;
use Illuminate\Http\Request;

class Uploader
{
    private $file;

    public function __construct(private Request $request, private StorageManager $storageManager, private FFMpegService $ffmpeg)
    {
        $this->file = $this->request->file;
    }

    public function upload()
    {
        if($this->isFileExists()) throw new FileExistsException('File already exists');

        $this->putFileIntoStorage();

        return $this->saveFileIntoDatabase();
    }

    private function saveFileIntoDatabase()
    {
        $file = new File([
            'name' => $this->file->getClientOriginalName(),
            'size' => $this->file->getSize(),
            'type' => $this->getType(),
            'is_private' => $this->isPrivate()
        ]);
        $file->time = $this->getTime($file);
        $file->save();
    }

    private function getTime(File $file)
    {
        if (!$file->isMedia()) return null;

        return $this->ffmpeg->durationOf($file->absolutePath());
    }

    private function putFileIntoStorage()
    {
        $method = $this->isPrivate() ? 'putFileAsPrivate' : 'putFileAsPublic';

        $this->storageManager->$method($this->file->getClientOriginalName(), $this->file, $this->getType());
    }

    private function isPrivate()
    {
        return $this->request->has('is-private');
    }

    private function getType()
    {
        return [
            'image/jpeg' => 'image',
            'video/mp4' => 'video',
            'application/x-zip-compressed' => 'archive'
        ][$this->file->getClientMimeType()];
    }

    private function isFileExists()
    {
        return $this->storageManager->isFileExists($this->file->getClientOriginalName(),$this->getType(),$this->isPrivate());
    }
}
