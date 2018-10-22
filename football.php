<?php
session_start();
?>

<?php

if(isset($_POST['logout']))
{
	logout();
	header("Location:football.php");
}



elseif(isset($_POST['leaderboard']))
{
	display_leaderborad();
}
elseif(isset($_POST['back']) && !isset($_SESSION['username']))
{
	front_page();
}
elseif(isset($_POST['back']))
{
	home_page(array());
}
elseif(isset($_POST['match_user']) || isset($_POST['team_user']))
{
	process_match_page();
}
elseif(isset($_POST['match_submit']))
{
	process_home_page();
}/*
elseif(isset($_POST['leaderboard']))
{
	leaderboard();
}*/

elseif(isset($_SESSION['username']))
{
	
	home_page(array());
}

elseif(isset($_POST['login_submit']))
{
	login_form(array(), array());
	/*if(isset($_POST['login']))
	{
		//process_login_form(array(), array());
		process_login_form($_POST['login_username'] , $_POST['login_password']);
	}*/
}
elseif(isset($_POST['login']))
{
	process_login_form();
}

else if(isset($_POST['signup_submit']))
{
	signup_form();
	
}
elseif(isset($_POST['signup']))
	{
		process_signup_form();
	}

else{
	front_page();
}


?>

<?php

function connections()
{
	$dsn="mysql:host=localhost; dbname=mydatabase;charset=utf8";
	$username= "root";
	$password= "Btech@srm15";

	try{
		$conn =  new PDO($dsn, $username, $password);
		$conn->setAttribute( PDO::ATTR_PERSISTENT , true );
		$conn->setAttribute( PDO::ATTR_ERRMODE , PDO:: ERRMODE_EXCEPTION );	
		return $conn;
	}
	catch(PDOException $e)
	{
		die("<span style='color:red;'> *connection failed</span>" .$e->getMessage());
	}	

}



function logout()
 {
	 unset($_SESSION['username']);
	unset($_SESSION['password']);
	unset($_POST['team_user']);
	unset($_POST['match_user']);
	 session_write_close();
	 header("Location: football.php");
 }
 
 
 function get_by_username($username,$password)
	{
		$conn = connections();
		$sql = "SELECT * FROM login WHERE username = :username AND password = password(:password)";
		try{
			$st= $conn->prepare($sql);
			$st->bindValue(':username',$username,PDO::PARAM_STR);
			$st->bindValue(':password',$password,PDO::PARAM_STR);
			$st->execute();
			if($user_data = $st->fetch())
				return true;
			//parent::disconnect();*/
			//$conn=  null;
			/*if($user_data)
			{
				echo "its working line 113";
			}
			else{
				echo "its not working line 117";
			}*/
			
		}
		catch(PDOException $e){
			echo  "<br>line 87 Query failed<br>".$e->getMessage();
			die("not working line 119");
			//header("Location:football.php");
		}
	}
 ?>

<?php


function process_login_form()
{
	$error= array();
	$missing_field= array();
	validate_login_form($error,$missing_field);
	
	/*if(isset($_SESSION['login_username']) && isset($_SESSION['login_password']) && (get_by_username($_SESSION['login_username'])))
	{
		$_SESSION['username'] = $_SESSION['login_username'];
		$_SESSION['password'] = $_SESSION['login_password'];
	}*/
}


function validate_login_form($error = array(), $missing_field= array())
{
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		if(empty($_POST['login_username']))
		{
			$missing_field[] = 'login_username'; 
		}
		if(!isset($_POST['login_username']) or (!$_POST['login_username']))
		{
			$error[] = '<p>login username is missing</p>'; 
		}
		if(empty($_POST['login_password']))
		{
			$missing_field[] = 'login_password';
		}
		if(!isset($_POST['login_password']) or (!$_POST['login_password']))
		{
			$error[] = '<p>login password is missing</p>';
		}
		if($error or $missing_field)
		{
			login_form($error, $missing_field);
		}
		elseif(get_by_username($_POST['login_username'],$_POST['login_password']))
		{
			$_SESSION['username'] = $_POST['login_username'];
			$_SESSION['password'] = $_POST['login_password'];
			header("Location:football.php");
		}
		
		else
		{
			//$_SESSION['username'] = $_POST['login_username'];
			//$_SESSION['password'] = $_POST['login_password'];
			//header("Location:football.php");
			//echo $_POST['login_username']."<br>";
			//echo $_POST['login_password']."<br>";
			$missing_field[] ='username_passwords_dont_match';
			login_form($missing_field);
		}
	}

}

?>


 
 
 
 <?php
 
 function login_form($error= array(), $missing_field= array() )
 {
	 header_();
	 if($missing_field)
	{
		foreach($missing_field as $missing_fields)
		{
			echo "<br>".$missing_fields." is missing kindly fill it<br>"; 
		}
	}
	elseif($error)
	{
		foreach ($error as $error_message)
		 {
			echo $error_message."<br>";
		}
	}
	
	
	?>
	
	<form  method="POST"  action="football.php">
	<input type="name" name="login_username" placeholder="username">
	<input type="password" name="login_password" placeholder="password">
	<button type="submit" name="login">Login</button><br><br>
	<button type= "submit" name="back">Back </button>
				
	</form>
	
 
 <?php
 
		
			
 }
 ?>
 
 
 <?php
 function signup_form($missing_field=array())
 {
	 header_();
	 if($missing_field)
	{
		foreach($missing_field as $missing_fields)
		{
			echo "<br>".$missing_fields." is missing kindly fill it<br>"; 
		}
	}
	 
	 ?>
	 
	 <form  method="POST"  action="football.php">
	 <input type="text" name="team_name" placeholder="Team Name"><br>
	<input type="text" name="player1_name" placeholder="player_1 name"><br>
	<input type="text" name="player2_name" placeholder="player_2 name"><br>
	<input type="text" name="signup_username" placeholder="username"><br>
	<input type="password" name="signup_password" placeholder="password"><br>
	<button type="submit" name="signup">Sign UP</button><br>
	</form>
	
	<?php
	
 }
 ?>
 
 <?php
 
 
 function get_by_signup_username($username)
	{
		$conn = connections();
		$sql = "SELECT * FROM login WHERE username = :username";
		try{
			$st= $conn->prepare( $sql);
			$st->bindValue(":username",$username,PDO::PARAM_STR);
			//$st->bindValue(":team_name",$team_name,PDO::PARAM_STR);
			$st->execute();
			
			
			if($user_data = $st->fetch())
			{
				return true;
			}
			//parent::disconnect();
			$conn=  null;
			/*if($user_data)
			{
				return ($user_data);
			}*/
		}
		catch(PDOException $e){
			echo  "<br>line 250 Query failed<br>".$e->getMessage();
			header("Location:football.php");
		}
	}
 
 function get_by_teamname($team_name)
	{
		$conn = connections();
		$sql = "SELECT * FROM login WHERE username = :team_name ";
		try{
			$st= $conn->prepare( $sql);
			//$st->bindValue(":username",$username,PDO::PARAM_STR);
			$st->bindValue(':team_name',$team_name,PDO::PARAM_STR);
			$st->execute();
			
			/*if($st->execute())
			{
				return true;
			}*/
			if($user_data = $st->fetch())
			{
				return true;
			}
			else{
				return false;
			}
			//parent::disconnect();
			$conn=  null;
			/*if($user_data)
			{
				return ($user_data);
			}*/
		}
		catch(PDOException $e){
			echo  "<br>line 324 Query failed<br>".$e->getMessage();
			//header("Location:football.php");
		}
	}
 
 function process_signup_form()
{
	$error= array();
	$missing_field= array();
	validate_signup_form($missing_field);
	/*if(isset($_SESSION['login_username']) && isset($_SESSION['login_password']) && (get_by_username($_SESSION['login_username'])))
	{
		$_SESSION['username'] = $_SESSION['login_username'];
		$_SESSION['password'] = $_SESSION['login_password'];
	}*/
}


function validate_signup_form($missing_field= array())
{
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		/*echo $_POST['team_name']."<br>";
		echo $_POST['signup_username']."<br>";*/
		//$_SESSION['username'] = $_POST['signup_username'];
			//$_SESSION['password'] = $_POST['signup_password'];
		
		//echo $_POST['team_name']."<br>";
		//echo "this is sesssion ".$_SESSION['username']."<br>";
		
		if(empty($_POST['team_name']))
		{
			$missing_field[] = 'team_name'; 
		}
		if(get_by_teamname($_POST['team_name']))
		{
			$missing_field[] = 'team_name_already_existed';
		}
		if(empty($_POST['player1_name']))
		{
			$missing_field[] = 'player1_name';
		}
		if(empty($_POST['player2_name']))
		{
			$missing_field[] = 'player2_name';
		}
		if(empty($_POST['signup_username']))
		{
			$missing_field[] = 'signup_username';
		}
		if(get_by_signup_username($_POST['signup_username']))
		{
			$missing_field[] = "username already exixted";
		}
		if(empty($_POST['signup_password']))
		{
			$missing_field[] = 'signup_password';
		}
		
		if($missing_field)
		{
			signup_form($missing_field);
		}
		else{
			$_SESSION['username'] = $_POST['signup_username'];
			$_SESSION['password'] = $_POST['signup_password'];
			//echo $_SESSION['username']."<br>";
			//echo $_SESSION['password']."<br>";
			//echo $_POST['player1_name']."<br>";
			//echo $_POST['player2_name']."<br>";
			//echo $_POST['team_name']."<br>";
			//records($_POST['signup_username'],$_POST['signup_password'] , $_POST['team_name'], $_POST['player1_name'], $_POST['player2_name']);
			records($_SESSION['username'],$_SESSION['password'] , $_POST['team_name'], $_POST['player1_name'], $_POST['player2_name']);
			header("Location:football.php");
			
			/*if(records($_SESSION['username'],$_SESSION['password'] , $_POST['player1_name'], $_POST['player2_name'], $_POST['team_name']))
			{
				echo "true";
			}
			else{
				 die("records not working");
			}*/
		}
		
		//records($_SESSION['username'],$_SESSION['password'] , $_POST['team_name'], $_POST['player1_name'], $_POST['player2_name']);
		
		
		//records($_SESSION['username'],$_SESSION['password'] , $_POST['team_name'], $_POST['player1_name'], $_POST['player2_name']) ;
		/*elseif(get_by_username($_POST['login_username']))
		{
			//header("Location:real_portal.php");
			$_SESSION['login_username'] = $_POST['login_username'];
			$_SESSION['login_password'] = $_POST['login_password'];
		}
		else
		{
			echo  "not returning data from database line 597<br>";
		}*/
	}

}

function records($username, $password, $player1_name, $player2_name, $team_name)
{
	
	$conn = connections();
	$sql = "INSERT INTO login(username, password, player_1, player_2,team_name) VALUES(:username,password(:password),:player1_name,:player2_name,:team_name)" ;
		try{
			$st= $conn->prepare($sql);
			$st->bindValue(':username',$username,PDO::PARAM_STR);
			$st->bindValue(':password',$password,PDO::PARAM_STR);
			$st->bindValue(':player1_name',$player1_name,PDO::PARAM_STR);
			$st->bindValue(':player2_name',$player2_name,PDO::PARAM_STR);
			$st->bindValue(':team_name',$team_name,PDO::PARAM_STR);
			//$st->bindValue(":score",$score,PDO::PARAM_STR);
			$st->execute();
			
		}
		catch(PDOException $e)
		{
			echo "<br>query failed on line 400<br>";
			//header("Location: football.php");

		}
}




function update_records_username($username)
{
	
	$conn = connections();
	$sql = "UPDATE login SET score = score + 10 WHERE username = :username";
	try{
			$st= $conn->prepare($sql);
			$st->bindValue(':username',$username,PDO::PARAM_STR);
			$st->execute();
			//$conn=null;
	}
	catch(PDOException $e)
		{
			die ("query failed on line 472");
			//header("Location: football.php");

		}
			
	$sql_1 = "UPDATE login SET match_win = match_win + 1 WHERE username = :username";
	try{
		//$conn = connections();
		$st1= $conn->prepare($sql_1);
		$st1->bindValue(':username',$username,PDO::PARAM_STR);
		$st1->execute();
		$conn=null;
	}
	catch(PDOException $e)
		{
			die ("query failed on line 486");
			//header("Location: football.php");

		}
}
function update_records_match_team_name($match_team_name)
{	
	$conn = connections();
	$sql = "UPDATE login SET score = score - 10 WHERE username = :match_team_name";
	try{
			$st= $conn->prepare($sql);
			$st->bindValue(':match_team_name',$match_team_name,PDO::PARAM_STR);
			$st->execute();
			//$conn=null;
	}
	catch(PDOException $e)
		{
			die ("query failed on line 505");
			//header("Location: football.php");

		}
			
	$sql_1 = "UPDATE login SET match_lost = match_lost + 1 WHERE username = :match_team_name";
	try{
		//$conn = connections();
		$st1= $conn->prepare($sql_1);
		$st1->bindValue(':match_team_name',$match_team_name,PDO::PARAM_STR);
		$st1->execute();
		$conn=null;
	}
	catch(PDOException $e)
		{
			die ("query failed on line 520");
			//header("Location: football.php");

		}
}

function login($username)
{
	if($_SESSION['username'])
	{
		home_page();
	}
	else{
		header("Location:football.php");
	}
}

function process_home_page()
{
	$error = array();
	if(empty($_POST['match_team_name']))
	{
		$error[] = 'Team_name_missing';
	}
	if($_POST['match_team_name']== $_SESSION['username'])
	{
		$error[] = 'Do_not_type_your_username';
	}
	if(!get_by_teamname($_POST['match_team_name']))
	{
		$error[] = 'team_not_exist';
	}
	if($error)
	{
		home_page($error);
	}
	else{
		$_SESSION['match_team_name']  = $_POST['match_team_name'];
		match_page();
	}
}


function process_match_page()
{
	if(isset($_POST['match_user']))
	{
		update_records_username($_SESSION['match_team_name']);
		update_records_match_team_name($_SESSION['username']);
		echo $_SESSION['match_team_name'] . " wins";
		
		?>
		
		<form method="POST" action="football.php">
				<br><button type= "submit" name="back">Back </button>
				</form>
				
				<?php
			//die ("update records working");
		//else
			//die("update records not working");
		//header("Location:football.php");
		//home_page(array());
	}
	elseif(isset($_POST['team_user']))
	{
		update_records_username($_SESSION['username']);
		update_records_match_team_name($_SESSION['match_team_name']);
		echo $_SESSION['username'] . " wins";
		
		?>
		
		<form method="POST" action="football.php">
				<br><button type= "submit" name="back">Back </button>
				</form>
				
				<?php
		//header("Location:football.php");
		//home_page(array());
	}
	else{
		die("records update function not working ");
	}
}
	
	

?>
		
		

<?php

function home_page($error)
{
	header_();
	
	
	?>
	<h3><?php echo "Hello " . $_SESSION['username'] ; ?></h3>
	<form  method="POST"  action="football.php">
	<input type="text" name="match_team_name" width="50%" placeholder="Team name to match"><br><br>
	<button type="submit" name="match_submit">Match</button><br><br>
	<button type="submit" name="leaderboard"> See leaderboard</button><br><br>
	<button type="submit" name="logout"> Logout</button>
	</form>
 
 

 <?php
 
 if($error)
 {
		 foreach($error as $error_message)
		 {
			 echo $error_message."<br>";
			 //header("Location:football.php");
		 }
	 }
}

?>

<?php


	
//
?>
<?php
function match_page()
{
	header_();
	?>
	
	<h2> Welcome To War Zone </h2>
	<p> Click on any team to make it win in the match</p>
	<form  method="POST"  action="football.php">
	<button type="submit" name="match_user"><?php echo $_POST['match_team_name'] ;?></button>
	<button type="submit" name="team_user"><?php echo $_SESSION['username'] ;?></button><br><br>
	<button type="submit" name="back"> Back </button>
	</form>
	
	<?php
	}
	?>

<?php 
function leaderboard_()
{
	/*if(!$_SESSION['username'])
	{
		echo "<br>function not working line 619 <br>";
		//header("Location: football.php");
	}*/
	//header_();
	$conn = connections();
	
	//$conn = connections();
	$sql ="SELECT * from login ORDER BY score DESC LIMIT 10";
		try{
			$st= $conn->prepare( $sql);
			//$st->bindValue(":username",$username,PDO::PARAM_STR);
			//$st->bindValue(":team_name",$team_name,PDO::PARAM_STR);
			$st->execute();
			
			/*if($st->execute())
			{
				return true;
			}*/
			if($user_data = $st->fetchAll())
			{
				return $user_data;
			}/*
			else{
				echo "list is empty ";
		    }*/
		}
		catch(PDOException $e)
		{
			 die("error in line 697");
			//header("Location: football.php");

		}
}

function display_leaderborad()
{
	?>
	
	<h2> Match Leaderboard</h2>
	<div name="leaderboard"  style= "border:1px solid black; width:45%; height:130px; overflow: auto;"  >
	<table style="border-color:grey;" >
	<tr>
	<th style="border-right: 1px solid black; padding-right: 150px; border-bottom: 1px solid black";>Team Name</th>
	<th style="border-right: 1px solid black; padding-right: 150px; border-bottom: 1px solid black";> Username</th>
	<th style="border-right: 1px solid black; padding-right: 15px;border-bottom: 1px solid black";>Score</th>
	<th style="border-right: 1px solid black; padding-right: 15px;border-bottom: 1px solid black";>Match Win</th>
	<th style="border-right: 1px solid black; padding-right: 15px;border-bottom: 1px solid black";>Match Lost</th>
	</tr>
		
		
		<?php
				$user_data = leaderboard_();
				foreach($user_data as $user)
					{
						$team_name=  $user['team_name'];
						$username = $user['username'];
						$score = $user['score'];
						$match_win = $user['match_win'];
						$match_lost = $user['match_lost'];
						
						echo "<tr><td style='border-right: 1px solid black; padding-right: 15px; border-bottom: 1px solid black;'>". $team_name ."</td>" ;
						echo "<td style='border-right: 1px solid black; padding-right: 15px; border-bottom: 1px solid black;'>". $username ."</td>" ;
						echo "<td style='border-right: 1px solid black; padding-right: 15px; border-bottom: 1px solid black;'>". $score."</td>";
						echo "<td style='border-right: 1px solid black; padding-right: 15px; border-bottom: 1px solid black;'>". $match_win."</td>";
						echo "<td style='border-right: 1px solid black; padding-right: 15px; border-bottom: 1px solid black;'>". $match_lost."</td></tr>";
					}
				
				?>
				
				</table>
				</div>
				<br>
				<form method="POST" action="football.php">
				<button type= "submit" name="back">Back </button>
				</form>
				</body>
				</html>
				
				<?php
				
				}
				
				?>
	


 
 <?php
 
 function front_page()
 {
	 header_();
	 ?>
	 
	<form  method="POST"  action="football.php">
	<button type="submit" name="login_submit">Login</button>
	<button type="submit" name="signup_submit">Sign Up</button>
	</form>
	
	<?php
 }
 ?>
	 
 
<?php
function header_()
{
	?>
	

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<title>Football</title>
<head>
<meta charset="utf-8">
</head>
<body>
<h1>Football</h1><br>

<?php
	
	}
?>

<?php
function footer()
{
	?>
	
	</body>
</html>

<?php
}
?>

