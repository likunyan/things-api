<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public string $folderType = 'images';

    /**
     * @param  Request  $request
     * @return array
     */
    public function store(Request $request): array
    {
        $key = $request->input('key');

        if (!$request->hasFile($key) || is_null($file = $request->file($key))) {
            return [];
        }

        // 获取文件名和扩展名
        $name = $file->getClientOriginalName();

        $fullFolder = "$this->folderType/$key";
        $filenameWithPath = "$fullFolder/$name";

        $file->storeAs($fullFolder, $name, 'oss');

        return [
            'url' => config('services.oss_endpoint').'/'.$filenameWithPath,
            'extra' => '',
            'key' => sha1($fullFolder.$name),
            'thumbnailUrl' => config('services.oss_endpoint').'/'.$filenameWithPath
        ];
    }
}
