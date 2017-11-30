<?php

namespace App\Services;

use \Intervention\Image\ImageManager;
use \Slim\Http\UploadedFile;

class UploadService {
    protected $imageManager;
    protected $container;
    protected $filesystem;

    public function __construct(ImageManager $manager, $container)
    {   
        $this->imageManager = $manager;
        $this->container = $container;
        $this->filesystem = $container['filesystem'];
    }

    public function uploadImages(array $images = [])
    {
        $uploadData = [];

        foreach($images as $image) {
            $imageData = $this->uploadImage($image);
            array_push($uploadData, $imageData);
        }
    }

    public function uploadImage($uploaded, string $affix = "") 
    {
        $file = $this->imageManager->make($uploaded->file);
        $filedata = new \App\Models\Image();
        $filename = date("dmYhis") . $affix;
        $filepath = $this->filesystem->basePath($this->container['settings']['images']['uploadPath']) . $filename . ".png";

        $filedata->name = $filename;
        $filedata->extension = "png";
        $filedata->real_name = $uploaded->getClientFileName();
        $filedata->path = $filepath;
        $filedata->save();

        $file->save($filepath);         
        
        return [
            "file" => $file,
            "data" => $filedata
        ];
    }
}