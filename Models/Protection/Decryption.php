<?php

namespace Codeclouds\Unify\Model\Protection;

/**
 * Decryption model.
 * Is used to decrypt encrypted text by the salt.
 */
class Decryption
{
    /**
     * Make decryption.
     * @param String $crypttext Encrypted text.
     * @param String $salt
     * @return String Plain text.
     */
    public static function make($crypttext, $salt)
    {
        if(phpversion() > 7.1)
        {
            /**
             * To decrypt, split the encrypted data from our IV - our unique separator used was "::"
             */
            list($encrypted_data, $iv) = explode('::', base64_decode($crypttext), 2);
            return openssl_decrypt($encrypted_data, 'aes-256-cbc', $salt, 0, $iv);
        }
        else
        {
            $decoded_64 = base64_decode(str_pad(strtr($crypttext, '-_', '+/'), strlen($crypttext) % 4, '=', STR_PAD_RIGHT));
            $td         = mcrypt_module_open('cast-256', '', 'ecb', '');
            $iv         = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

            mcrypt_generic_init($td, $salt, $iv);

            $decrypted_data = mdecrypt_generic($td, $decoded_64);

            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            
            return trim($decrypted_data);
        }
    }
}
