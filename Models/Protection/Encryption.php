<?php

namespace Codeclouds\Unify\Model\Protection;

/**
 * Encryption model.
 * Is used to encrypt plaintext by the salt.
 */
class Encryption
{
	/**
	 * Make encryption.
	 * @param String $plaintext
	 * @param String $salt
	 * @return String Encrypted text.
	 */
	public static function make($plaintext, $salt)
	{
		if(phpversion() > 7.1)
		{
			/**
			 * Generate an initialization vector
			 */
		    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
		    /**
		     * Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
		     */
		    $encrypted = openssl_encrypt($plaintext, 'aes-256-cbc', $salt, 0, $iv);
		    /**
		     * The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
		     */
		    return base64_encode($encrypted . '::' . $iv);
		}
		else
		{
			$td = mcrypt_module_open('cast-256', '', 'ecb', '');
		    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

		    mcrypt_generic_init($td, $salt, $iv);
		    $encrypted_data = mcrypt_generic($td, $plaintext);

		    mcrypt_generic_deinit($td);
		    mcrypt_module_close($td);

		    $encoded_64 = rtrim(strtr(base64_encode($encrypted_data), '+/', '-_'), '=');
		    
		    return trim($encoded_64);
		}
	}
}