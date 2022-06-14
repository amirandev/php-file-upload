# How to use?


**Put the storage class to somewhere and use it like that:**

```
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
```


