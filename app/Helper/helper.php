<?php
 
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('flashDanger')) {
    function flashDanger($count, $message)
    {
        return $count == 0 ? Session::flash('flash_danger', $message) : Session::forget('flash_danger');
    }
}

if (!function_exists('exceptionError')) {
    function exceptionError(\ValidationException $e, $path)
    {
        return Redirect::to($path)
            ->withErrors($e->getErrors())
            ->withInput();
    }
}

if (! function_exists('get_upload_url')) {
    function getUploadUrl($path, $config)
    {
        if (Storage::disk($config)->exists($path)) {
            return Storage::disk($config)->url($path);
        }
    }
}

if (! function_exists('uploadImage')) {
    function uploadImage($imageBase64,$path){
        //decode base64 string
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageBase64));
        $ext = ".jpg";
        $pos  = strpos($imageBase64, ';');
        $type = explode(':', substr($imageBase64, 0, $pos))[1];
        $ext = explode('/', $type)[1];
        if($ext == "jpeg") $ext = "jpg";
        $imageName = uniqid() . '.' . $ext;
        \Storage::disk($path)->put($imageName, $image);
        // $urlImage = "storage/image/{$path}/{$imageName}";
        // make thumnail
        // uploadThumbnail($urlImage, 500);
        return $imageName;
    }
}

if(! function_exists('uploadFile')){
  function uploadFile($file, $config)
  {
    if ($file == null) return;
    $ext = $file->getClientOriginalExtension();
    $imageName = uniqid().'.'.$ext;
    \Storage::disk($config)->put($imageName, file_get_contents($file));
    // $urlImage = Storage::disk($config)->path($imageName);
    // make thumnail
    // uploadThumbnail($urlImage, 500);
    return $imageName;
  }
}

if(! function_exists('deleteFile')){
    function deleteFile($file, $config)
    {
      Storage::disk($config)->delete($file);
    }
}

/**
 * @param   string  $str
 * @return  bool
 */
if (! function_exists('isBase64')) {
    function isBase64($str)
    {
        return (bool)preg_match('#^data:image/\w+;base64,#i', $str);
    }
}

/**
 * @param int $width
 * @param int $height
 */
if (!function_exists('uploadThumbnail')) {
    function uploadThumbnail($urlImage, $setWidth = 500)
    {
        $info = getimagesize($urlImage);
        list($sourceWidth, $sourceHeight) =  $info;
        if($sourceWidth > $setWidth) {
            $newWidth = $setWidth ;
            $ratio = $sourceHeight / $sourceWidth;
            $newHeight = $ratio * $newWidth;
            $destination = imagecreatetruecolor($newWidth, $newHeight);
            // check extention of image
            $type = $info['mime'];
            switch ($type) {
                case 'image/jpeg':
                    $source = imagecreatefromjpeg($urlImage);
                    break;
                case 'image/png':
                    $source = imagecreatefrompng($urlImage);
                    break;
                case 'image/gif':
                    $source = imagecreatefromgif($urlImage);
                    break;
            }
            imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
            // out image
            imagejpeg($destination, $urlImage, 100);
        }
    }
}

if (!function_exists('currencyFormat')) {
    function currencyFormat($price)
    {
        return $price > 0 ? number_format((double)$price, 0, ',', ',') : '';
    }
}

if (!function_exists('strSlug')) {
    function strSlug($str)
    {
        return Str::slug($str, '-');
    }
}

if (!function_exists('str_limit')) {
    function str_limit($str, $lenght = 20)
    {
        return Str::limit($str, $lenght);
    }
}

/**
 * checkImageSize
 */
if (!function_exists('checkImageSize')) {
    function checkImageSize($imageBase64)
    {
        $size = strlen(base64_decode($imageBase64));
        $size_mb = $size / 1024000;
        return ($size_mb > 10);
    }
}