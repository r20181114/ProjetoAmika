<!--
//login.php
!-->

<?php

include('database_connection.php');

session_start();

$message = '';

if(isset($_SESSION['user_id']))
{
 header('location:../Homepage.php');
}

if(isset($_POST["login"]))
{
 $query = "
   SELECT * FROM utilizadores
    WHERE email = :email
 ";
 $statement = $connect->prepare($query);
 $statement->execute(
    array(
      ':email' => $_POST["email"]
     )
  );
  $count = $statement->rowCount();
  if($count > 0)
 {
  $result = $statement->fetchAll();
    foreach($result as $row)
    {
      if(password_verify($_POST["password"], $row["password"]))
      {
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['nome'] = $row['nome'];
        $sub_query = "
        INSERT INTO login_details 
        (user_id) 
        VALUES ('".$row['user_id']."')
        ";
        $statement = $connect->prepare($sub_query);
        $statement->execute();
        $_SESSION['login_details_id'] = $connect->lastInsertId();
        header("location:../Homepage.php");
      }
      else
      {
       $message = "<label>Wrong Password</label>";
      }
    }
 }
 else
 {
  $message = "<label>Wrong Email</label>";
 }
}

?>
