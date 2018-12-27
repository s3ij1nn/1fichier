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

// Get derect Download link (Premium, Access, CDN)
$o->download("https://1fichier.com/?IIIIIIIDDDDDDDDD")

```

### checksum checking

```shell
$ checksum ``openssl dgst -whirlpool * > all.checksum``
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
