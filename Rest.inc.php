<?php

	/* Rest class responsible for
	* taking request from the client
	* and giving response to the client
	*/

	class REST
	{
		/* specified response content type which is json */
		protected $content_type = "application/json";

		/* Store request methods like GET , PUT etc */
		protected $method = "";

		/* Store the url request variables */
		protected $url = array();

		/* Store the status code */
		protected $code;

		/*
		* Constructor function
		* Get the Url and Request Method
		*/

		public function __construct(){
			$this->getUrl();
			$this->get_request_method();
		}

		/* Get the Url and explode it so that we can process it */

		public function getUrl()
		{
			$this->url = $_GET['url'];
			$this->url = rtrim($this->url, '/');
			$this->url = explode('/', $this->url);
		}

		/*
		* function responsible for
		* response to the client
		*/
		public function response($data,$status){
			$this->code = ($status)?$status:200;
			$this->set_headers();
			echo $data;
			exit;
		}

		/*
		* All the status code
		*/
		protected function get_status_message()
		{
			$status = array(
						100 => 'Continue',
						101 => 'Switching Protocols',
						200 => 'OK',
						201 => 'Created',
						202 => 'Accepted',
						203 => 'Non-Authoritative Information',
						204 => 'No Content',
						205 => 'Reset Content',
						206 => 'Partial Content',
						300 => 'Multiple Choices',
						301 => 'Moved Permanently',
						302 => 'Found',
						303 => 'See Other',
						304 => 'Not Modified',
						305 => 'Use Proxy',
						306 => '(Unused)',
						307 => 'Temporary Redirect',
						400 => 'Bad Request',
						401 => 'Unauthorized',
						402 => 'Payment Required',
						403 => 'Forbidden',
						404 => 'Not Found',
						405 => 'Method Not Allowed',
						406 => 'Not Acceptable',
						407 => 'Proxy Authentication Required',
						408 => 'Request Timeout',
						409 => 'Conflict',
						410 => 'Gone',
						411 => 'Length Required',
						412 => 'Precondition Failed',
						413 => 'Request Entity Too Large',
						414 => 'Request-URI Too Long',
						415 => 'Unsupported Media Type',
						416 => 'Requested Range Not Satisfiable',
						417 => 'Expectation Failed',
						500 => 'Internal Server Error',
						501 => 'Not Implemented',
						502 => 'Bad Gateway',
						503 => 'Service Unavailable',
						504 => 'Gateway Timeout',
						505 => 'HTTP Version Not Supported');

			return ($status[$this->code])?$status[$this->code]:$status[500];
		}

		/* Function gets the Request Method */

		public function get_request_method(){
			$this->method =  $_SERVER['REQUEST_METHOD'];
		}

		/* Function used to set  the Header */

		private function set_headers(){
			header("HTTP/1.1 ".$this->code." ".$this->get_status_message());
			header("Content-Type:".$this->content_type);
		}
	}
?>