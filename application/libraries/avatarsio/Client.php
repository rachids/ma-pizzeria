<?php

class Client {

	const version = 'v1';

	protected $content;
	protected $base_url;

	function __construct($base_uri) {
		$this->base_uri = $base_uri;
	}

	function post($content, $headers, $endpoint) {

		$url = $this->base_uri . '/'. self::version . '/' . $endpoint;

		$s = curl_init();

		curl_setopt($s, CURLOPT_URL, $url);
		curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($s, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($s, CURLOPT_POST, 1);
		curl_setopt($s, CURLOPT_POSTFIELDS, json_encode($content));

		$result = curl_exec($s);

		curl_close($s);

		return $this->response($result);
	}

	function put($content, $headers, $file) {

		$s = curl_init();

		curl_setopt($s, CURLOPT_URL, $content->upload_url);
		curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($s, CURLOPT_PUT, 1);

		$f = fopen($file, 'r');

		curl_setopt($s, CURLOPT_INFILE, $f);
		curl_setopt($s, CURLOPT_INFILESIZE, filesize($file));

		$result = curl_exec($s);

		fclose($f);

		return $this->response($result);
	}

	private function response($result) {
		return json_decode($result);
	}
}
