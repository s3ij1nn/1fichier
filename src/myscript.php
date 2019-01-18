<?php

namespace onefichier;

class myscript extends fichier
{
  public function __construct($token){
    $this->parent = new parent($token);

  }

  public function duplicate_delete($folder_id)
  {
    foreach($this->parent->file_ls($folder_id) ["items"] as $video)
    {
      $checksum = $video["checksum"];
      foreach($this->parent->file_ls($folder_id) ["items"] as $s_video)
      {
        if ($video["url"] === $s_video["url"])
        {
          continue;
        }

        if ($s_video["filename"] === $video["filename"] AND $s_video["checksum"] === $video["checksum"])
        {
          echo "duplicate found " . $video["filename"] . "\n";
          echo $s_video["url"] . "\n";
          echo $video["url"] . " .... deleting \n";
          $this->parent->file_rm($video["url"]);
        }
      }
    }
  }
}
