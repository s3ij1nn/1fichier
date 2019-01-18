<?php

namespace onefichier;

class myscript extends fichier
{
    public function __construct($token)
    {
        parent::__construct($token);
    }

    public function duplicate_delete($folder_id, $batch_mode = false)
    {
        foreach(parent::file_ls($folder_id) ["items"] as $video)
        {
            foreach(parent::file_ls($folder_id) ["items"] as $s_video)
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
                    parent::file_rm($video["url"]);
                }
            }
        }
    }
}
