<?php

require_once('database.config.php');
require_once('helper_function.php');

/*
* Database class taking care of
* all tha database related stuffs
*/
class Database

  {
          private $dbConnect ;
           public function dbConnect()
           {

            /* Connecting to the database */
            return new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER , DB_PASS);
            }

            public static function getUser($id=null)
            {
                $final_data = array();
                $dbconnect = self::dbConnect();
                if($id==NULL)
                {
                    $data = $dbconnect->query("select user_fullname, user_id, user_email from users");
                }
                else
                {
                     $data = $dbconnect->prepare("select user_fullname , user_id, user_email from users where user_id = :id");
                     $data->execute(array('id' => $id));
                    }

                    /* Fetching data in Associate array format */

                  $data = $data->fetchAll(PDO::FETCH_ASSOC);
                 if($data)
                 {

                  return $data;
                 }
                 else
                 {
                  return false;
                 }
            }

            public static function deleteUser($id=null)
            {
              $dbconnect = self::dbConnect();
                if($id==NULL)
                {
                    $data = $dbconnect->query("delete from users");
                }
                else
                {
                   $data = $dbconnect->prepare("delete from users where user_id = :id");
                   $data->execute(array('id' => $id));
                }
                return $data->rowCount();
            }

          public static function insertUser($data)
          {
            $dbconnect = self::dbConnect();

            /*converting array into variables
            *like ([email] => sa1991ndeep) into $email= sa1991ndeep
            */

           foreach($data as $key=>$value)
            {
              $$key = $value;
            }

            if($name && $email && $password)
            {
              $data = $dbconnect->prepare("insert into users (user_fullname , user_email , user_password) values(:name , :email , :password)");
              $data->execute(array(':name' => $name,
                                    ':email' => $email,
                                    ':password' => $password)
                            );
              $id = $dbconnect->lastInsertId();
              return $id;
            }
            else
            {
              return false;
            }
          }

          public static function updateUser($data,$id)
          {
            $dbconnect = self::dbConnect();

            foreach($data as $key=>$value)
            {
              $$key = $value;
            }

            if($id && $email && $name && $password)
            {
                $data = $dbconnect->prepare("update users set user_fullname = :name , user_email = :email , user_password = :password where user_id = $id");
                $data->execute(array(':name' => $name,
                                      ':email' => $email,
                                      ':password' => $password)
                              );
                return $data->rowCount();
              }
            return false;
         }
}
?>