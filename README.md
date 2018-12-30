# 1fichier
1fichier api for php

initialize

get from https://1fichier.com/console/params.pl and generate API KEY

```php
require 'vendor/autoload.php';
$o = new \onefichier\fichier(__API__KEY__);

// Root folder files's list
$o->file_ls(0)

// Root folder folder's list
$o->folder_ls(0)

// Get direct download link (Premium, Access, CDN)
$o->download("https://1fichier.com/?IIIIIIIDDDDDDDDD")

```

### checksum check

```shell
$ openssl dgst -whirlpool * > all.checksum
```

```php
$o->checksum_parser("all.checksum") fullpath or __DIR__ / all.checksum
[
  ["filename", "hash"],
  ["filename", "hash"],
    ...
]

$o->checksum_check("/tmp/all.checksum", 114514, true);
OK  filename.txt
OK  .........txt
NOT upload_after_create_file.txt

$o->checksum_check("/tmp/all.checksum", 114514);
[
  ["upload_after_create_file.txt", "whirlpoolHASH"]
]
```

### Run on CLI
```shell
$ curl -sS https://getcomposer.org/installer | php

$ php composer.phar require psy/psysh
$ php composer.phar install

$ vendor/bin/psysh vendor/autoload.php

Psy Shell v?.?.? (PHP ?.?.? — cli) by Justin Hileman
>>> $o = new \onefichier\fichier("API_KEY");
=> onefichier\fichier {#2313
     +error: null,
   }

>>> $o->file_ls(0)

>>> $o->file_mv("https://1fichier.com/?0x0000000000000", 114514);

>>> ls $o
Class Properties: $error
Class Methods: av_scan, checksum_check, checksum_parser, download, file_cp, file_info, file_ls, file_mv, file_rm, folder_ls, folder_mv, folder_rm, folder_share, folder_share_gen, mkdir, request, __construct

// doc
>>> doc $o->checksum_check
public function checksum_check($filename, $folder_id, $verbose = false)

Description:
  checksum_check
  
  checksum file path and how to make checksum view checksum_parse doc
  find checksum from folder
  if find all checksum to return true
  $verbose true to show OK or NOT
  
  When not find checksum return not fond file and checksum
  return array format is same as checksum_parser

Param:
  $filename            
  $folder_id           
  bool        $verbose 

Return:
  array|bool 

```
