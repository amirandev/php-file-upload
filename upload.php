<?php
class Storage
{
    public static $data;

    public static function generateRandomName($extension = null){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $n = 15;
        $str = '';
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $str .= $characters[$index];
        }
        
        return $str.'_'.time().'.'.$extension;
    }
    
    public static function urlpath($path = null, $filename = null){
        $realDocRoot = realpath($_SERVER['DOCUMENT_ROOT']);
        $realDirPath = realpath(__DIR__);
        $suffix = str_replace($realDocRoot, '', $realDirPath);
        $prefix = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $folderUrl = $prefix . $_SERVER['HTTP_HOST'] . $suffix;
        $solveURL = str_replace('\\','/', $folderUrl);
        $solveURL = rtrim($solveURL, '/');
        $solvePath = ltrim($path, '/');
        $combinated = $solveURL.'/'.$solvePath;
        return rtrim($combinated,'/').'/'.ltrim($filename,'/');
    }
    
    public static function saveAsToPath($path = null, $filename = null){
        return ltrim($path, '/').'/'.ltrim($filename,'/');
    }
    
    public static function upload($input_name, $smth = null)
    {   
        $file = $_FILES[trim($input_name)] ?? null;
        $original_name = $file['name'] ? trim($file['name']) : null;
        $exploded = explode(".", $original_name);
        $ext = end($exploded);
        $originalName = str_replace('.'.$ext, '', $original_name) ?? null;
        $mime = $file['type'] ?? null;
        $type = $file['type'] ? explode('/',$mime)[0] : null;
        $error = $file['error'] ?? null;
        $size = $file['size'] ?? null;
        $randomName = self::generateRandomName($ext);
        
        self::$data = [
            'input' => $input_name ? trim($input_name) : null,
            'name' => $original_name,
            'extension' => $ext,
            'name_only' => $originalName,
            'mime' => $mime,
            'type' => $type,
            'errors' => $error,
            'size' => $size,
            'newName' => $randomName,
            'save_as_to' => self::saveAsToPath($smth, $randomName),
            'url' => self::urlpath($smth, $randomName)
        ];
        
        if(getimagesize($file['tmp_name'])){
            $img_info = getimagesize($file['tmp_name']);
            self::$data['width'] = $img_info[0] ?? 0;
            self::$data['height'] = $img_info[1] ?? 0;
        }
        else
        {
            self::$data['width'] = 0;
            self::$data['height'] = 0;
        }
        
        if($file['tmp_name']){
            self::$data['uploaded'] = move_uploaded_file($file['tmp_name'], self::saveAsToPath($smth, $randomName));
        }
        else{
            self::$data['uploaded'] = false;
        }
        
        return (object) self::$data;
    }
}


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //                     'input name'   'save to - where?'
    $run = Storage::upload('upload_file', 'uploads/files');
    echo 'input name: '.$run->input.'<br>';
    echo 'File original name: '.$run->name.'<br>';
    echo 'File extension: '.$run->extension.'<br>';
    echo 'File original name without extension: '.$run->name_only.'<br>';
    echo 'MimeType: '.$run->mime.'<br>';
    echo 'Media Type: '.$run->type.'<br>';
    echo 'Errors: '.$run->errors.'<br>';
    echo 'File size: '.$run->size.'<br>';
    echo 'File new name (after upload): '.$run->newName.'<br>';
    echo 'Path where it be saved as to: '.$run->save_as_to.'<br>';
    echo 'File URL: '.$run->url.'<br>';
    echo 'Uploaded image width: '.$run->width.'<br>';
    echo 'Uploaded image height: '.$run->height.'<br>';
    echo 'Upload satatus: '.$run->uploaded.'<br>';
    
    if($run->uploaded){
        echo 'The file has been uploaded';
    }
    
    exit;
}


include_once 'uploadform.php';

