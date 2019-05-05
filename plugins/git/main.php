<?php
require_once("scm_interface");
class SCM implements SCMProtocol
{
    private function filename($path){
        $components = explode($path);
        return $components[count($components)-1];
    }
    private function commit($dir, $msg, $author){
        //
    }

    private function add($file){
        exec("cd \"".dirname(dirname(__FILE__))."/".dirname($file)."\" && git add \"".$this->filename($file)."\"");
    }

    private function rm($file){
        exec("cd \"".dirname(dirname(__FILE__))."/".dirname($file)."\" && git rm \"".$this->filename($file)."\"");
    }

    private function push($dir){
        exec("cd \"".dirname(dirname(__FILE__))."/".dirname($file)."\" && git push origin master");
    }

    private function pull($dir){
        exec("cd \"".dirname(dirname(__FILE__))."/".dirname($file)."\" && git pull");
    }

    public function add_file($file, $content, $name, $push){
        file_put_contents($file, $content);
        $this->add($file);
        $this->commit(dirname($file), $name." created ".$file, $name);
        if($push) $this->push(dirname($file));
    }

    public function edit_file($file, $content, $name, $push){
        file_put_contents($file, $content);
        $this->add($file);
        $this->commit(dirname($file), $name." updated ".$file, $name);
        if($push) $this->push(dirname($file));
    }
    public function delete_file($file, $name){
        unlink($file);
        $this->rm($file);
        $this->commit(dirname($file), $name." deleted ".$file, $name);
        if($push) $this->push(dirname($file));
    }
    public function is_updated($dir){
        $status = exec("cd ".dirname(dirname(__FILE__))."/\"$dir\" && bash ".dirname(dirname(__FILE__))."/tools/cli/check_updates.sh");
        return $status == "0";
    }
    public function update($dir){
        $status = exec("cd ".dirname(dirname(__FILE__))."/\"$dir\" && bash ".dirname(dirname(__FILE__))."/tools/cli/check_updates.sh");
        if($status == 1) $this->pull($dir);
        elseif($status == 2) $this->push($dir);
    }
}
