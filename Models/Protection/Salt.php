<?php

namespace Codeclouds\Unify\Model\Protection;

/**
 * Salt token.
 * Is used to encrypt & decrypt plaintext.
 */
class Salt
{
    /**
     * Generate salt token
     * @param  integer $length Length of salt token
     * @return String Generated salt token
     */
    public static function generate($length = 16)
    {
        $token = "";

        /**
         * Define possible characters - any character in this string can be
         * picked for use in the token, so if you want to put vowels back in
         * or add special characters such as exclamation marks, this is where
         * you should do it
         */
        $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

        /**
         * Refer to the length of $possible a few times, so let's grab it now
         */
        $maxlength = strlen($possible);

        /**
         * Heck for length overflow and truncate if necessary
         */
        if ($length > $maxlength)
        {
            $length = $maxlength;
        }

        /**
         * Set up a counter for how many characters are in the token so far
         */
        $i = 0;

        /**
         * Add random characters to $token until $length is reached
         */
        while ($i < $length)
        {

            /**
             * Pick a random character from the possible ones
             */
            $char = substr($possible, mt_rand(0, $maxlength - 1), 1);

            /**
             * Have we already used this character in $token?
             */
            if (!strstr($token, $char))
            {
                /**
                 * No, so it's OK to add it onto the end of whatever we've already got...
                 */
                $token .= $char;

                /**
                 * Increase the counter by one
                 */
                $i++;
            }
        }

        return $token;
    }


    // public static function other_salt_generate($length = 16)
    // {
    //     $token = "";

    //     /**
    //      * Define possible characters - any character in this string can be
    //      * picked for use in the token, so if you want to put vowels back in
    //      * or add special characters such as exclamation marks, this is where
    //      * you should do it
    //      */
    //     $possible = "!au2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

    //     /**
    //      * Refer to the length of $possible a few times, so let's grab it now
    //      */
    //     $maxlength = strlen($possible);

    //     /**
    //      * Heck for length overflow and truncate if necessary
    //      */
    //     if ($length > $maxlength)
    //     {
    //         $length = $maxlength;
    //     }

    //     *
    //      * Set up a counter for how many characters are in the token so far
         
    //     $i = 0;

    //     /**
    //      * Add random characters to $token until $length is reached
    //      */
    //     while ($i < $length)
    //     {

    //         /**
    //          * Pick a random character from the possible ones
    //          */
    //         $char = substr($possible, mt_rand(0, $maxlength - 1), 1);

    //         /**
    //          * Have we already used this character in $token?
    //          */
    //         if (!strstr($token, $char))
    //         {
    //             /**
    //              * No, so it's OK to add it onto the end of whatever we've already got...
    //              */
    //             $token .= $char;

    //             /**
    //              * Increase the counter by one
    //              */
    //             $i++;
    //         }
    //     }

    //     return $token;
    // }
}
