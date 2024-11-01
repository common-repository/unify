<?php

namespace CodeClouds\Unify\Abstracts;

/**
 * Connection's Abstract class.
 * @package CodeClouds\Unify
 */
abstract class Order_Abstract
{

	/**
	 * Store an instance of the CRM.
	 */
	protected $api_instance = null;

	/**
	 * Store payloads for API call.
	 */
	protected $api_payload = [];

	/**
	 * Store configuration of CRM.
	 */
	protected $api_config = [];

	/**
	 * Store response of the API.
	 */
	protected $api_response = [];

	/*
	 * Patterns for templating.
	 */
	private $pattrens = [
		'/\{\{([^}]*.?)\}\}/' => 'var',
		'/\{\%([^}]*.?)\%\}/' => 'array',
		'/\{array2:([^}]*.?)\}/' => 'array2',
		'/\{list:([^}]*.?)\}/' => 'list'
	];

	/**
	 *
	 * Store matched pair.
	 */
	private $matched_collection = [];

	/**
	 * Call API for payment process.
	 * @return array
	 */
	abstract public function make_order();

	/**
	 * Format the configuration as per patterns.
	 */
	protected function format_data()
	{
		$this->set_config(
			$this->api_payload['config']['connection'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3)[2]['function']
		);

		$offset = 0;
		$matches = [];

		foreach ($this->pattrens as $pattren => $value)
		{
			while (\preg_match($pattren, $this->api_config, $matches, PREG_OFFSET_CAPTURE, $offset))
			{
				if ($this->api_payload['config']['connection'] == 'konnektive' && $value == 'array')
				{
					$method = 'replace_array_konnektive';
				}
				else
				{
					$method = 'replace_' . $value;
				}

				$this->$method($matches[0][0], end($matches)[0]);

				/**
				 * Get byte offset and byte length (assuming single byte encoded)
				 */
				$match_start = $matches[0][1];
				$match_length = strlen($matches[0][0]);

				/**
				 * Update offset to the end of the match
				 */
				$offset++;
			}
			$offset = 0;
		}

		$this->api_config = \json_decode(
			\str_replace(
				\array_keys($this->matched_collection), \array_values($this->matched_collection), $this->api_config
			), true
		);
	}

	/**
	 * The array_get function retrieves a value from a deeply nested array using "dot" notation.
	 * @param array $arr
	 * @param String $path
	 * @return mixed
	 */
	protected function array_get(&$arr, $path)
	{
		$loc = &$arr;
		foreach (\explode('.', $path) as $step)
		{
			$loc = &$loc[$step];
		}
		return $loc;
	}

	/**
	 * Replace by keys.
	 * @param String $str
	 * @param String $match
	 */
	private function replace_var($str, $match)
	{
		$this->matched_collection[$str] = $this->array_get($this->api_payload, \trim($match));
	}

	/**
	 * Replace by array pairs.
	 * @param String $str
	 * @param String $match
	 */
	private function replace_array($str, $match)
	{
		$api_config = \json_decode($this->api_config, true);
		$match = \explode(':', \trim($match));
		$items = $this->array_get($this->api_payload, $match[0]);
		$collection = [];


		foreach ($items as $key => $item)
		{
			foreach ($api_config[$str] as $config_key => $config)
			{
				if ($match[1] != '-')
				{
					if (isset($match[2]))
					{
						$collection[$key][$config_key] = $item[$config];
					}
					else
					{
						$collection[$item[$match[2]]][$config_key] = $item[$config];
					}
				}
				else
				{
					if (isset($match[2]))
					{
						$collection[\str_replace('{#}', $item[$match[2]], $config_key)] = $item[$config];
					}
					else
					{
						$collection[\str_replace('{#}', $key + 1, $config_key)] = $item[$config];
					}
				}
			}
		}
		unset($api_config[$str]);

		if (!empty($match[1]) && $match[1] != "-")
		{
			$this->api_config = \json_encode($api_config + [$match[1] => $collection]);
		}
		else
		{
			$this->api_config = \json_encode($api_config + $collection);
		}
	}

	/**
	 * Replace by set of keys.
	 * @param String $str
	 * @param String $match
	 */
	private function replace_List($str, $match)
	{
		$match = explode('|', $match);
		$arr = [];
		$keys = \explode(', ', \trim($match[0]));
		$items = $this->array_get($this->api_payload, $keys[0]);
		$start = 0;

		if (isset($match[1]))
		{
			$start = trim($match[1]);
		}

		for ($i = $start; $i < count($items); $i++)
		{
			$arr[] = $items[$i][$keys[1]];
		}

		$this->matched_collection[$str] = \implode(',', $arr);
	}

	/**
	 * Set the configuration file of CRM for data format.
	 */
	public function set_config($connection, $name)
	{
		$this->api_config = \file_get_contents(__DIR__ . '/../Config/' . strtolower($connection) . '/' . $name . '.config.json');
	}

	private function replace_array_konnektive($str, $match)
	{
		$api_config = \json_decode($this->api_config, true);
		$match = \explode(':', \trim($match));
		$items = $this->array_get($this->api_payload, $match[0]);
		$collection = [];

		foreach ($items as $key => $item)
		{
			foreach ($api_config[$str] as $config_key => $config)
			{
				if (isset($match[2]))
				{
					$collection[\str_replace('{#}', $item[$match[2]], $config_key)] = $item[$config];
				}
				else
				{
					$collection[\str_replace('{#}', $key + 1, $config_key)] = $item[$config];
				}
			}
		}
		unset($api_config[$str]);

		if (!empty($match[1]) && $match[1] != "-")
		{
			$this->api_config = \json_encode($api_config + [$match[1] => $collection]);
		}
		else
		{
			$this->api_config = \json_encode($api_config + $collection);
		}
	}

/**
	 * Set the configuration file of CRM for data format.
	 */

	private function replace_array2($str, $match)
	{
		$api_config = \json_decode($this->api_config, true);
		$match = \explode(',', \trim($match));
		$items = $this->array_get($this->api_payload, $match[0]);
		$collection = [];

		foreach ($api_config[$str] as $config_key => $config)
		{
			$collection[trim($match[1])][$config_key] = $items[$config];
		}

		unset($api_config[$str]);

		$this->api_config = \json_encode($api_config + $collection);
	}

}
