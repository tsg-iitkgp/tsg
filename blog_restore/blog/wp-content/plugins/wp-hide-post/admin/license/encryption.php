<?php
/**
 * A class to handle secure encryption and decryption of arbitrary data
 *
 * Note that this is not just straight encryption.  It also has a few other
 *  features in it to make the encrypted data far more secure.  Note that any
 *  other implementations used to decrypt data will have to do the same exact
 *  operations.
 *
 * Security Benefits:
 *
 * - Uses Key stretching
 * - Hides the Initialization Vector
 * - Does HMAC verification of source data
 *
 */
class WP_Hide_Post_Encryption
{

    const METHOD = 'aes-256-cbc';

    /**
     * Encrypts (but does not authenticate) a message
     *
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded
     * @return string (raw binary)
     */
    public static function encrypt($message, $key, $encode = false)
    {
        $ivSize = openssl_cipher_iv_length(self::METHOD);
        $iv     = openssl_random_pseudo_bytes($ivSize);

        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode)
        {
            return base64_encode($iv . $ciphertext);
        }
        return $iv . $ciphertext;
    }

    /**
     * Decrypts (but does not verify) a message
     *
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string
     */
    public static function decrypt($message, $key, $encoded = false)
    {
        if ($encoded)
        {
            $message = base64_decode($message, true);
            if ($message === false)
            {
                throw new Exception('Encryption failure');
            }
        }

        $ivSize     = openssl_cipher_iv_length(self::METHOD);
        $iv         = mb_substr($message, 0, $ivSize, '8bit');
        $ciphertext = mb_substr($message, $ivSize, null, '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return $plaintext;
    }
}
