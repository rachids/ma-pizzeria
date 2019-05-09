<?php

include('Client.php');

class Avatar {

	const BASE_URI = 'http://avatars.io';

	protected $access_token = FALSE;
	protected $client;

	function __construct($access_token) {
		if (!$access_token) {
		  throw new Exception('Invalid Credentials');
		}

		$this->access_token = $access_token[0];
		$this->client = new Client(self::BASE_URI);
	}

	function upload($file, $identifier = '') {
		if (!file_exists($file) OR !is_readable($file)) {
		  throw new Exception('Cannot Access File');
		}

		$response = $this->send_file($file, $identifier);

		if (empty($response->data->upload_info)) {
			return $response->data->url;
		}

		$this->set_aws_s3_acl($response->data->upload_info, $file);

		return $this->get_file_url($response->data->id);
	}

	function send_file($file, $identifier) {
		$content = array(
			'data' => array(
				'filename' => $file,
				'md5' => md5_file($file),
				'size' => filesize($file),
				'path' => $identifier
			)
		);

		$this->response = $this->client->post(
			$content,
			array(
				'Content-Type: application/json; charset=utf-8',
				'Authorization: OAuth ' . $this->access_token
			),
			'token'
		);

		return $this->response;
	}

	function set_aws_s3_acl($upload_info, $file) {
		$this->client->put(
			$upload_info,
			array(
				'Authorization: ' . $upload_info->signature,
				'Date: ' . $upload_info->date,
				'Content-Type: ' . $upload_info->content_type,
				'x-amz-acl: public-read'
			),
			$file
		);
	}

	function get_file_url($image_id) {
		$response = $this->client->post(
			'',
			array(
				'Authorization: OAuth ' . $this->access_token
			),
			'token/' . $image_id . '/complete'
		);

		return $response->data;
	}

	function url($service = 'twitter', $key, $size = 'default') {
		return self::BASE_URI . '/' . $service . '/' . $key . '?size=' . $size;
	}

}