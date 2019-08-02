<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    CONST IMG_ENV = 'public';

    public function getImageEnv() {
        return $this::IMG_ENV;
    }

    public function setFileUpload($file, $model) {
        $extension = $file->getClientOriginalExtension();
        Storage::disk($this->getImageEnv())->put($file->getFilename().'.'.$extension, File::get($file));

        $model->mime = $file->getClientMimeType();
        $model->original_filename = $file->getClientOriginalName();
        $model->filename = $file->getFilename().'.'.$extension;
    }

}
