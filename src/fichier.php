<?php


namespace onefichier;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class fichier
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var
     */
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

    /**
     * @param $json
     * @param $uri
     * @return bool|mixed
     */
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

    /**
     * @param $uri
     * @return bool|mixed
     */
    public function download($uri)
    {
        $json = [
            "url"       =>  $uri,
            "pretty"    =>  1
        ];
        return $this->request($json,"download/get_token.cgi");
    }

    /**
     * @param $folder_id
     * @return bool|mixed
     */
    public function file_ls($folder_id = 0)
    {
        $json = [
            "folder_id"     =>  $folder_id,
            "pretty"        =>  1
        ];
        return $this->request($json, "file/ls.cgi");
    }

    /**
     * @param $url
     * @return bool|mixed
     */
    public function file_info($url)
    {
        $json = [
            "url"       =>  $url,
            "pretty"    =>  1
        ];
        return $this->request($json, "file/info.cgi");
    }

    /**
     * @param $url
     * @return bool|mixed
     */
    public function av_scan($url)
    {
        $json = [
            "url"       =>  $url,
            "pretty"    =>  1
        ];
        return $this->request($json, "file/scan.cgi");
    }

    /**
     * @param $url
     * @return bool|mixed
     */
    public function file_rm($url)
    {
        $json = [
            "url"   =>  $url
        ];
        return $this->request($json, "file/rm.cgi");
    }

    /**
     * @param $urls
     * @param $folder_id
     * @return bool|mixed
     */
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

    /**
     * @param $urls
     * @param $folder_id
     * @return bool|mixed
     */
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

    /**
     * @param $folder_id
     * @return bool|mixed
     */
    public function folder_ls($folder_id = 0)
    {
        $json = [
            "folder_id"     =>  $folder_id,
            "pretty"        =>  1
        ];
        return $this->request($json, "folder/ls.cgi");
    }

    /**
     * @param $name
     * @param bool $folder_id
     * @param bool $sharing_user
     * @return bool|mixed
     */
    public function mkdir($name, $folder_id = false, $sharing_user = false)
    {
        $json = [
           "name"                   =>  $name,
        ];
        if($folder_id){
            $json["folder_id"]      =   $folder_id;
        }
        if($sharing_user){
            $json["sharing_user"]   =   $sharing_user;
        }

        return $this->request($json, "folder/mkdir.cgi");
    }

    /**
     * @param $folder_id
     * @param int $share
     * @param bool $pass
     * @param bool $shares
     * @return bool|mixed
     */
    public function folder_share($folder_id, $share = 0, $pass = false, $shares = false)
    {
        $json = [
            "folder_id"     =>  $folder_id,
            "share"         =>  $share
        ];
        if($pass){
            $json["pass"]   =   $pass;
        }
        if($shares){
            $json["shares"] =   $shares;
        }

        return $this->request($json, "folder/share.cgi");
    }

    /**
     * @param $email
     * @param int $rw
     * @param int $hide_links
     * @param bool $add_array
     * @return array
     */
    public function folder_share_gen($email, $rw = 0, $hide_links = 0, $add_array = false)
    {
        $json = [
            "email"         =>  $email,
            "rw"            =>  $rw,
            "hide_links"    =>  $hide_links
        ];
        if(is_array($add_array)){
            $array[] = $add_array;
        }
        $array[] = $json;
        return $array;
    }

    /**
     * @param $folder_id
     * @param $destination_folder_id
     * @param bool $destination_user
     * @return bool|mixed
     */
    public function folder_mv($folder_id, $destination_folder_id, $destination_user = false)
    {
        $json = [
            "folder_id"                 =>  $folder_id,
            "destination_folder_id"     =>  $destination_folder_id
        ];
        if($destination_user){
            $json["destination_user"]   =   $destination_user;
        }
        return $this->request($json, "folder/mv.cgi");
    }

    /**
     * @param $folder_id
     * @return bool|mixed
     */
    public function folder_rm($folder_id)
    {
        $json = [
            "folder_id"     =>  $folder_id
        ];
        return $this->request($json, "folder/rm.cgi");
    }

    public function ftp_process()
    {
        $json = [
            "pretty"        =>  1
        ];
        return $this->request($json, "ftp/process.cgi");
    }

    public function ftp_user_ls()
    {
        $json = [
            "pretty"        =>  1
        ];
        return $this->request($json, "ftp/users/ls.cgi");
    }

    public function ftp_user_add($username, $password, $folder_id)
    {
        $json = [
            "user"          =>  $username,
            "pass"          =>  $password,
            "folder_id"     =>  $folder_id
        ];
        return $this->request($json, "ftp/users/add.cgi");
    }

    public function ftp_user_rm($username)
    {
        $json = [
            "user"          =>  $username
        ];
        return $this->request($json, "ftp/users/rm.cgi");
    }

    public function remote_ls()
    {
        $json = [
            "pretty"        =>  1
        ];
        return $this->request($json, "remote/ls.cgi");
    }

    public function remote_info($id)
    {
        $json = [
            "id"            =>  $id
        ];
        return $this->request($json, "remote/info.cgi");
    }

    public function remote_request($urls, $folder_id, $headers = false)
    {
        $json = [
            "urls"          =>  $urls,
            "folder_id"     =>  $folder_id,
        ];
        if($headers){
            $json["headers"] = $headers;
        }
        return $this->request($json, "remote/request.cgi");
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