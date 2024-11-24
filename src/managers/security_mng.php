<?php
require_once (__DIR__ . '/../models/people/reader.php');

final class SecurityManager{
    
    /**
     * By now, is what it is; I'll change Reader set_passphrase to comport
     * password_hash after studying about peppering passwords.
     * https://www.php.net/manual/en/function.password-hash.php
    */
    public static function check_password(?Reader $user, $post_passphrase): bool{
        $post_passphrase_sha256 = hash('sha256', $post_passphrase);
        return $user -> get_passphrase() === $post_passphrase_sha256;
    }
}

?>