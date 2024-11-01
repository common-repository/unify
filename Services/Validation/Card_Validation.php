<?php

namespace CodeClouds\Unify\Service\Validation;

/**
 * CC validation.
 * @package CodeClouds\Unify
 */
class Card_Validation extends \CodeClouds\Unify\Abstracts\Validation
{
    /**
     * Print error messages.
     */
    public function print_messages()
    {
        foreach ($this->fields_status as $status)
        {
            \wc_add_notice($status, 'error');
        }
    }
    
    /**
     * CC number validation & set CC type.
     * @param String $cc_number
     * @param boolean $extra_check
     * @return boolean
     */
    protected function cc_number($cc_number, $extra_check = true)
    {
        if (empty($cc_number))
        {
            $this->fields_status[] = '<strong>CC number</strong> is a required field.';
            return false;
        }

        $plugin_settings = get_option('woocommerce_codeclouds_unify_settings');

        $cards   = [
            'visa'       => '(4\d{12}(?:\d{3})?)',
            'amex'       => '(3[47]\d{13})',
            // 'jcb'        => '(35[2-8][89]\d\d\d{10})',
            'maestro'    => '((?:5020|5038|6304|6579|6761)\d{12}(?:\d\d)?)',
            'solo'       => '((?:6334|6767)\d{12}(?:\d\d)?\d?)',
            'mastercard' => '(5[1-5]\d{14})',
			'discover' => '(6(?:011\d{12}|5\d{14}|4[4-9]\d{13}|22(?:1(?:2[6-9]|[3-9]\d)|[2-8]\d{2}|9(?:[01]\d|2[0-5]))\d{10}))',
            'switch'     => '(?:(?:(?:4903|4905|4911|4936|6333|6759)\d{12})|(?:(?:564182|633110)\d{10})(\d\d)?\d?)',
        ];
        $names   = ['Visa', 'American Express', /* 'JCB', */ 'Maestro', 'Solo', 'Mastercard', 'Discover', 'Switch'];
        $matches = [];
        $pattern = '#^(?:' . implode('|', $cards) . ')$#';
        $result  = preg_match($pattern, str_replace(' ', '', $cc_number), $matches);

        if ($extra_check && $result > 0)
        {
            $result = (self::validate_card($cc_number)) ? 1 : 0;
        }

        if ($result > 0)
        {
            \CodeClouds\Unify\Service\Request::setPost('cc_type', $names[sizeof($matches) - 2]);
        }
        else
        {
            /**
             * If plugin is not in test mode.
             */
            if ($plugin_settings['testmode'] != 'yes')
            {
                $this->fields_status[] = 'Please enter a valid <strong>CC number</strong>.';
            }
			//setting default card type as Visa
			else
			{
				\CodeClouds\Unify\Service\Request::setPost('cc_type', 'Visa');
			}
		}
    }

    /**
     * CC expiry validation.
     * @param String $expiry
     * @return boolean
     */
    protected function cc_expiry($expiry)
    {
        if (empty($expiry))
        {
            $this->fields_status[] = '<strong>CC expiry</strong> is a required field.';
            return false;
        }

        $expiry = explode('/', $expiry);

        if (count($expiry) != 2 || ($expiry[0] < 1 || $expiry[0] > 12) || strlen($expiry[0]) < 2 || $expiry[1] < date('y'))
        {
            $this->fields_status[] = 'Please enter a valid <strong>CC expiry</strong>.';
        }
        
    }

    /**
     * CC CVC validation
     * @param String $cvc
     * @return boolean
     */
    protected function cc_cvc($cvc)
    {
        if (empty($cvc))
        {
            $this->fields_status[] = '<strong>CC CVC</strong> is a required field.';
            return false;
        }

        if ($_POST['cc_type'] == 'American Express')
        {
            if (strlen($cvc) != 4)
            {
                $this->fields_status[] = 'Please enter a valid <strong>CC CVC</strong>.';
            }
        }
        else
        {
            if (strlen($cvc) != 3)
            {
                $this->fields_status[] = 'Please enter a valid <strong>CC CVC</strong>.';
            }
        }
    }

    /**
     * Extra CC number validation as per CC number length.
     * @param type $card_number
     * @return boolean
     */
    private function validate_card($card_number)
    {
        $card_number = preg_replace('/\D|\s/', '', $card_number);
        $card_length = strlen($card_number);
        $parity      = $card_length % 2;
        $sum         = 0;

        for ($i = 0; $i < $card_length; $i++)
        {
            $digit = $card_number[$i];

            if ($i % 2 == $parity)
            {
                $digit = $digit * 2;
            }

            if ($digit > 9)
            {
                $digit = $digit - 9;
            }

            $sum = $sum + $digit;
        }
        $valid = ($sum % 10 == 0);
        return $valid;
    }
}
