<?php

namespace App\Services;

class FilesystemService {
    protected $rootPath;
    protected $publicDirectory = "/public/";

    public function __construct(string $rootPath) {
        $this->rootPath = $rootPath;
    }


    public static function fromAbsolutePath(string $path) 
    {
        return new self($path);
    }

    public static function fromRelativePath(string $path) 
    {
    
        return new self(realPath($path));
    }

    public function basePath(string $path) 
    {
        return $this->rootPath . $path;
    }

    public function publicPath(string $path)
    {
        return $this->basePath($this->publicDirectory) . $path;
    }

    public function setPublicDirectory(string $fromRoot) 
    {
        $this->$publicDirectory = $fromRoot;
    }
}