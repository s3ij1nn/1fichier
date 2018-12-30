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
```
