<?php

namespace onefichier;

class myscript extends fichier
{
    /**
     * myscript constructor.
     * @param $token
     */
    public function __construct($token)
    {
        parent::__construct($token);
    }

    public function duplicate_delete($folder_id, $batch_mode = false)
    {
        foreach($this->file_ls($folder_id) ["items"] as $video)
        {
            foreach($this->file_ls($folder_id) ["items"] as $s_video)
            {
                if ($video["url"] === $s_video["url"])
                {
                    continue;
                }

                if ($s_video["filename"] === $video["filename"] AND $s_video["checksum"] === $video["checksum"])
                {
                    if(!$batch_mode){
                        echo "duplicate found " . $video["filename"] . "\n";
                        echo $s_video["url"] . "\n";
                        echo $video["url"] . " .... deleting \n";
                    }
                    $this->file_rm($video["url"]);
                }
            }
        }
    }

    public function upload_fail_delete($checksum_path, $folder_id, $batch_mode = false)
    {
        foreach($this->checksum_parser($checksum_path) as $checksum => $filename)
        {
            foreach($this->file_ls($folder_id) ["items"] as $file)
            {
                if ($file["filename"] === basename($filename))
                {
                    if ($file["checksum"] != $checksum)
                    {
                        if(!$batch_mode){
                            echo $file["url"] . "\n" . "local :" . $checksum . "\n" . "remote:" . $file["checksum"] . ".... delete\n";
                        }
                        $this->file_rm($file["url"]);
                    }
                }
            }
        }
    }

    public function upload_success_delete($checksum_path, $folder_id, $directory = false, $batch_mode = false)
    {
        foreach ($this->checksum_parser($checksum_path) as $checksum => $filename)
        {
            foreach($this->file_ls($folder_id)["items"] as $file)
            {
                if(basename($filename) === $file["filename"] AND $checksum === $file["checksum"])
                {
                    $notfound = false;
                    if(! is_file($filename)){
                        if(is_file($directory.basename($filename))){
                            $filename = $directory.basename($filename);
                        }elseif(is_file($directory.$filename)){
                            $filename = $directory.$filename;
                        }else{
                            $notfound = true;
                        }
                    }
                    if(!$batch_mode){
                        if($notfound){
                            echo $filename. " is not found. \n";
                        }else{
                            echo $filename. " .... delete \n";
                        }
                    }
                    if(!$notfound){
                        unlink($filename);
                    }
                }
            }
        }

    }
}
