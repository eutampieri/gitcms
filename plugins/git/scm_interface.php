<?php

interface SCMPlugin{
    public function add_file($file, $content, $name, $push);
    public function edit_file($file, $content, $name, $push);
    public function delete_file($file, $name, $push);
    public function is_updated($dir);
    public function update($dir);
}