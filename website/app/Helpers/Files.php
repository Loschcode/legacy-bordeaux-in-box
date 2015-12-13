<?php

/**
 * Delete a file
 * @param  string $file  
 * @param  string $folder 
 * @return void  
 */
function delete_file($file, $folder) {

  $file_path = public_path().'/uploads/'.$folder.$file;
  if (File::exists($file_path)) File::delete($file_path);

}

function upload_image($file, $folder, $table_class, $name, $attributes) {

  $destinationPath = 'public/uploads/'.$folder.'/';

  $filename = value(function() use ($file, $name) {

    $filename = uniqid() . Str::slug($name) . '.' . $file->getClientOriginalExtension();
    return $filename;

  });

  $file->move($destinationPath, $filename);

  $table_class->folder = $folder;
  $table_class->filename = $filename;

  foreach ($attributes as $attribute => $value) {

    $table_class->$attribute = $value;

  }
  
  return $table_class->save();

}

/**
 * Make the folder if not already existing
 * @param  string $path to the folder
 * @return void     
 */
function make_folder($path) {

  if(!is_dir($path)) mkdir($path, 0777);

}