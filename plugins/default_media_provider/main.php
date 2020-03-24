<?php
require_once("interface.php");

// The plugin stores the files in the assets directory. The filename is uuid_original-filename.ext

if(!is_dir(dirname(dirname(__FILE__)))."/assets"){
    mkdir(dirname(dirname(__FILE__))."/assets");
}

class MediaProvider implements MediaProviderPlugin
{
    private function get_object_path($id){
        return glob(dirname(dirname(__FILE__))."/assets/$id*")[0];
    }
    private function get_object_name($id){
        return str_replace(dirname(dirname(__FILE__))."/assets/",'',$this->get_object_path($id));
    }
    private function get_file_name($id){
        return implode("_", array_slice(explode("_", $this->get_object_name($id), 1)));
    }

    public function list_objects(){
        $files = glob(dirname(dirname(__FILE__))."/assets/*");
        usort($files, create_function('$a,$b', 'return fileatime($a) - fileatime($b;'));
        $ids = [];
        foreach($files as $file){
            array_push($ids, explode("_", str_replace(dirname(dirname(__FILE__))."/assets/",
            '', $file))[0]);
        }
        return $ids;
    }
    public function update_object($id, $file){
        move_uploaded_file($file['tmp_name'], $this->get_object_path($id));
    }
    public function delete_object($id){
        unlink($this->get_object_path($id));
    }
    public function upload_object($file){
        move_uploaded_file($file['tmp_name'], dirname(dirname(__FILE__))."/assets/".uniqid().'_'.basename($file['name']));
    }
    public function get_object_info($id){
        $filename = $this->get_file_name($id);
        return [
            "uploader" => "gitCMS",
            "url" => 'assets/'.$this->get_object_name($id),
            "absolute_url" => false, // Wether the URL is absolute or not
            "filename" => $filename,
            "extension" => array_slice(explode('.', $filename), -1)
        ];
    }
}
