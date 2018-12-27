<?php


namespace onefichier;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class fichier
{
    private $client;
    public $error;

    /**
     * fichier constructor.
     * @param $token
     */
    public function __construct($token)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.1fichier.com/v1/',
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ]
        ]);
    }

    public function request($json, $uri)
    {
        try {
            $response = $this->client->request('POST', $uri, [
                'json'  =>   $json
            ]);
        } catch (GuzzleException $e) {
            $this->error = $e;
            return false;
        }
        return json_decode($response->getBody()->getContents(), true);
    }

    public function download($uri)
    {
        $json = [
            "url"       =>  $uri,
            "pretty"    =>  1
        ];
        return $this->request($json,"download/get_token.cgi");
    }

    public function file_ls($folder_id)
    {
        $json = [
            "folder_id"     =>  $folder_id,
            "pretty"        =>  1
        ];
        return $this->request($json, "file/ls.cgi");
    }

    public function file_info($url)
    {
        $json = [
            "url"       =>  $url,
            "pretty"    =>  1
        ];
        return $this->request($json, "file/info.cgi");
    }

    public function av_scan($url)
    {
        $json = [
            "url"       =>  $url,
            "pretty"    =>  1
        ];
        return $this->request($json, "file/scan.cgi");
    }

    public function file_rm($url)
    {
        $json = [
            "url"   =>  $url
        ];
        return $this->request($json, "file/rm.cgi");
    }

    public function file_mv($urls, $folder_id)
    {
        if(!is_array($urls)){
            $urls = [$urls];
        }
        $json = [
            "pretty"                    =>  1,
            "destination_folder_id"     =>  $folder_id,
            "urls"                      =>  $urls
        ];

        return $this->request($json, "file/mv.cgi");
    }

    public function file_cp($urls, $folder_id)
    {
        if(!is_array($urls)){
            $urls = [$urls];
        }
        $json = [
            "pretty"                    =>  1,
            "destination_folder_id"     =>  $folder_id,
            "urls"                      =>  $urls
        ];

        return $this->request($json, "file/cp.cgi");
    }

    public function folder_ls($folder_id)
    {
        $json = [
            "folder_id"     =>  $folder_id,
            "pretty"        =>  1
        ];
        return $this->request($json, "folder/ls.cgi");
    }

    /**
     * checksum parser
     *
     * checksum ``openssl dgst -whirlpool * > all.checksum``
     * checksum_parser("all.checksum") fullpath or __DIR__ / all.checksum
     * [
     *   ["filename", "hash"],
     *   ["filename", "hash"],
     *   ...
     * ]
     *
     * @param $filename
     * @return bool | array
     */
    public function checksum_parser($filename){
        if(!file_exists($filename)){
            $filename = __DIR__ . "/" . $filename;
            if(!file_exists($filename)){
                return false;
            }
        }
        $body = file_get_contents($filename);
        $checksums = array_values(array_filter(explode("\n", $body), "strlen"));
        foreach($checksums as $id => $checksum){
            preg_match('|whirlpool\(([^)]*)\)= (.*)$|', $checksum, $match);
            $checksums[$id] = [$match[1], $match[2]];
        }

        return $checksums;
    }

    /**
     * checksum_check
     *
     * checksum file path and how to make checksum view checksum_parse doc
     * find checksum from folder
     * if find all checksum to return true
     * $verbose true to show OK or NOT
     *
     * When not find checksum return not fond file and checksum
     * return array format is same as checksum_parser
     *
     *
     * @param $filename
     * @param $folder_id
     * @param bool $verbose
     * @return array|bool
     */
    public function checksum_check($filename, $folder_id, $verbose = false){
        $files = $this->file_ls($folder_id)["items"];
        $checksums = $this->checksum_parser($filename);

        $errors = [];

        foreach($checksums as $checksum){

            $bool = false;
            foreach($files as $file){
                if($file["checksum"] === $checksum[1]){
                    $bool = true;
                }
            }
            if(!$bool){
                $errors []= $checksum;
                if($verbose){
                    echo "NOT " . $checksum[0] . "\n";
                }
            }else{
                if($verbose){
                    echo "OK  " . $checksum[0] . "\n";
                }
            }
        }
        if(empty($errors)){
            return true;
        }else{
            return $errors;
        }
    }
}