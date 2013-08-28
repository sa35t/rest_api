<?php

require_once("Rest.inc.php");
require_once("database.php");
require_once("helper_function.php");

/*
* Class extends from Rest responsible for processing
* of request and generating response
*/
class API extends REST
{
	public function __construct()
		{
			/* Calling Parent constructor*/
			parent::__construct();
		}

		/*
		 * Public method for access api.
		 * This Method dynamically called appropriate
		 * Method based on
		 *  request method like put , delete etc.
		 */

		public function processApi()
		{
			/*
			* Here we can evaluate url[0] if we want to
			* load different controller, but we are checking
			* only for url[1] as we have only one controller
			*/
			$id = (int)$this->url[1];
			switch (strtolower($this->method))
			{
				/* Get request */
				case 'get':
					$this->getUsers($id);
				break;
				/* put request */
				case 'put' :
					if($id)
					{
						$this->updateUser($id);
					}
					else
					{
						$message = array("status" => "Failure", "msg" => "Please provide id for updation");
						$this->response($this->json($message), 400);
					}
				break;

				/* Post request */
				case 'post':
					$this->insertUser();
				break;

				/* Delete request */
				case 'delete':
					$this->deleteUser($id);
				break;
				default:
					$message = array("status" => "Failure", "msg" => "Not a valid request method");
					$this->response($this->json($message), 400);
				break;
			}
		}

		/*
		 *	Insert User Function
		 */

		private function insertUser()
		{
				if($this->method != "POST"){
					$this->response('',406);
				}

				/*
				* grab_http_body found in helper_function.php
				*/
				$data = grab_http_body();
				if($data)
				{
					/* Calling database insertuser function */
					$id = Database::insertUser($data);
					if($id)
					{
						$status = array('status' => "success", "msg" => "one row inserted", "newId" => $id);
						$this->response($this->json($status), 200);
					}
					else
					{
						$message = array('status' => "failure", "msg" => "Not enough parameters");
						$this->response($this->json($message), 400);
					}
				}
				else
				{
					$message = array('status' => "failure", "msg" => "No data is passed");
					$this->response($this->json($message), 400);
				}
			}

			/*
			* Get user function
			*/

		private function getUsers($id=null)
			{
				//Double checking if request is Get or not

				if($this->method != "GET"){
					$this->response('',406);
				}
				$result = array();
				//Get result from database
				$data = Database::getUser($id);
				//If there is any data present
				if($data)
				{
					foreach($data as $data)
					{
						$result[] = $data;
					}
					/* Sending Response in Json Format */
					$this->response($this->json($result), 200);
				}
				else
				{
					/* If no data is found */
					$message = array('status' => "Not found", "msg" => "No id is found");
					$this->response($this->json($message),404);
				}
			}

			/*
			* Delete User function
			*/

		private function deleteUser($id=null)
		{
			if($this->method != "DELETE"){
				$this->response('',406);
			}
			if($id > 0)
			{
				//Delete data from database
				$data = Database::deleteUser($id);
				if($data)
				{
					$success = array('status' => "Success", "msg" => "Successfully $data row  deleted.");
					$this->response($this->json($success),200);
				}else
				{
					$message = array('status' => "Not found", "msg" => "No content available corresponding to the id");
					$this->response($this->json($message) ,404);
				}
			}
			else
			{
				/* If id in not found in database */

				$message= array('status' => "Not found", "msg" => "Please insert id greater than 0");
				$this->response($this->json($message) ,404);
			}
		}

			/* Update user function*/

			private function updateUser($id)
			{
				if($this->method != "PUT"){
					$this->response('',406);
				}

				$data = grab_http_body();
				if($data)
				{
					$affect = Database::updateUser($data, $id);
					if($affect)
					{
						$status = array("status" => "success", "msg" => "one row updated");
						$this->response($this->json($status), 200);
					}
					else
					{
						$message = array("status" => "Failure", "msg" => "Please provide full information");
						$this->response($this->json($message), 400);
					}
				}
			}

		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}

	// Initiate Library

	$api = new API;
	$api->processApi();
	?>