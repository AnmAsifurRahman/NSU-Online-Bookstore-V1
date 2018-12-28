<?php
$username = $_POST['username'];
$email = $_POST['email'];
$nsu_id = $_POST['nsu_id'];
$password = $_POST['password'];
//$param_password = password_hash($password, PASSWORD_DEFAULT);

if( !empty($username) ||!empty($email) ||!empty($nsu_id) ||!empty($password))
{
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "book_store";


    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

    if(mysqli_connect_error())
    {
    	die('Connect Error('. mysqli_connect_errno(). ')' . mysqli_connect_error());
    }

    else
    {
    	//$SELECT = "SELECT email from user_info where email = ? Limit 1";
        $SELECT= "SELECT username from user_info where username = ? Limit 1";
        $password = password_hash($password, PASSWORD_DEFAULT);
    	$INSERT = "INSERT into user_info (username, email, nsu_id, password) values(?, ?, ?, ?)";

    	/*$stmt = $conn->prepare($SELECT);
    	$stmt->bind_param("s", $email);
    	$stmt->execute();
    	$stmt->bind_result($email);
    	$stmt->store_result();
    	$rnum = $stmt->num_rows;*/

        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($username);
        $stmt->store_result();
        $rnum = $stmt->num_rows;

    	if($rnum == 0)
    	{
    		$stmt->close();
    		$stmt = $conn->prepare($INSERT);
    		$stmt->bind_param("ssis", $username, $email, $nsu_id, $password);

           // $param_username = $username;

    		$stmt-> execute();
    		header('Location: first.php');
    		//echo "New Record inserted successfully";

    	}

    	else
    	{
    		echo "Somebody already registered using this username. Please try a new username";
    	}
    	$stmt->close();
    	$conn->close();




    }

}

else
{
	echo "All fields are required";
	die();
}










?>
