<?php

interface MediaProviderPlugin{
    public function list_objects();
    public function update_object($id, $file);
    public function delete_object($id);
    public function upload_object($file);
    public function get_object_info($id);
}