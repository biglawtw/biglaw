<?php

final class Cryptography
{
    static public $_keypattern = '/MeiGic/';

    /**
     * singleton pattern
     * private constructor
     */
    private function __construct()
    {
    }

    static public function setKeypattern($key){
        self::$_keypattern = $key;
    }

    /**
     * Use Blowfish Cryptography to hash password
     * @param $input password
     * @return string hash result
     */
    static public function blowfish_password($input)
    {
        // generate random string , length is 22
        $salt = substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22);
        $hash = crypt($input, '$2a$12$' . self::$_keypattern . $salt);
        return $hash;
    }

    /**
     * Verify password is correct
     * @param $input password
     * @param $hash hash
     * @return bool is correct?
     */
    static public function blowfish_verify($input, $hash)
    {
        return ($hash == crypt($input, $hash));
    }

}