<?php
/**************************************
* Database Provisioning App           *
* Fidelity Investments 2016           *
* David Medvedev                      *
* Contact: David.Medvedev1@marist.edu *
* (203) 554-6157                      *
***************************************/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();


/*
$errors = array(); // array to hold validation errors
$data = array(); // array to pass back data
$conn_string = array(); // array to hold connection string
$rolname = $_SESSION['userId']; // array to hold user's username string
$rolpass = $_SESSION['pass']; // array to hold user password string
$database = $_SESSION['db']; // array to hold database string

//Connect to database dynamic
$conn_string = "host=localhost port=5432 dbname=truecourse user=postgres password=password";
//echo $conn_string;
$db_found = pg_connect($conn_string) or die('Could not connect do database');
*/
pg_connect("host=localhost port=5432 dbname=TrueCourse user=postgres password=password");

//search for user function
function searchCoach()
{
	
	if (empty($_POST['search']))
		$errors['search'] = 'Search is required.';
	
	if ( ! empty($errors)) {
		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['errors']  = $errors;
	} else {
	
	$search = $_POST['search'];
	if (isset($search)) {
		$data = array(
			"search" => $search,
		);
		//stored procedure db call 
		$result = pg_query("select * from coaches where LOWER(first) = LOWER('$search') or LOWER(last) = LOWER('$search')");
		$resultArray = pg_fetch_all($result);
		if ($resultArray) {
			$response = json_encode($resultArray); //encode the data in json format
			echo $response;
		}
		else {
			$data['errors'] = 'failure';
			//echo json_encode($data);
			// echo "There was an error! " . pg_last_error();
		}
	}
	
	}
	
}
function searchUser()
{
	
	if (empty($_POST['search']))
		$errors['search'] = 'Search is required.';
	
	if ( ! empty($errors)) {
		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['errors']  = $errors;
	} else {
	
	$search = $_POST['search'];
	if (isset($search)) {
		$data = array(
			"search" => $search,
		);
		//stored procedure db call 
		$result = pg_query("select * from members where LOWER(first) = LOWER('$search') or LOWER(last) = LOWER('$search')");
	
		$resultArray = pg_fetch_all($result);
		if ($resultArray) {
			$response = json_encode($resultArray); //encode the data in json format
			echo $response;
		}
		else {
			$data['errors'] = 'failure';
			//echo json_encode($data);
			// echo "There was an error! " . pg_last_error();
		}
	}
	
	}
	
}
function getCoaches()
{
	//stored procedure db call 
	$result = pg_query("Select * from Coaches");
	$resultArray = pg_fetch_all($result);
	$response = json_encode($resultArray); //encode the data in json format
	echo $response;
	/*
	if (isset($_GET['callback'])) {
	$response = $_GET['callback'] . '(' . $response . ')';
	}
	echo $response;
	*/
}

function getEncounters()
{
	$isblank = True; 
	//stored procedure db call 
	$id = $_POST['mid'];
	$result = pg_query("Select date,importance,location,notes from encounters INNER join members on encounters.mid=members.mid where members.mid = $id order by date desc");
	$resultArray = pg_fetch_all($result);
	$response = json_encode($resultArray); //encode the data in json format
	echo $response;
	

	if (empty($resultArray))  {
		$isblank = true;
		echo $isblank;
	} else {
		$isblank = false;
		echo $isblank;
	}

	/*
	if (isset($_GET['callback'])) {
	$response = $_GET['callback'] . '(' . $response . ')';
	}
	echo $response;
	*/
}

function getUsers()
{
	//stored procedure db call 
	$result = pg_query("Select * from members,address");
	$resultArray = pg_fetch_all($result);
	$response = json_encode($resultArray); //encode the data in json format
	echo $response;
	/*
	if (isset($_GET['callback'])) {
	$response = $_GET['callback'] . '(' . $response . ')';
	}
	echo $response;
	*/
}
function getRoles()
{
	//stored procedure db call 
	$result = pg_query("select provisioning.getRoles()");
	$resultArray = pg_fetch_all($result);
	$response = json_encode($resultArray); //encode the data in json format
	echo $response;
}
function logout()
{	
	$rolname = $_SESSION['userId']; // array to hold user's username string
	$rolpass = $_SESSION['pass']; // array to hold user password string
	$database = $_SESSION['db']; // array to hold database string
	$conn_string = "host=$database port=5432 dbname=aae_data user=$rolname password=$rolpass";
	$db_found = pg_connect($conn_string) or die('Could not connect do database');
	$dc = pg_close($db_found);
	
	
	
}
function addUser()
{

		$fname = $_POST['fname'];
		$mname = $_POST['mname'];
		$lname = $_POST['lname'];
		$sex = $_POST['sex'];
		$phone = $_POST['phone'];
		$dob = $_POST['dob'];
		$cpref = $_POST['cpref'];
		$coach = $_POST['coach'];
		
		
		//stored procedure db call 
		$query = "INSERT INTO members
		(first, mname, last, sex, phone, dob, cpref, coach)
		VALUES
		('$fname', '$mname', '$lname', '$sex','$phone','$dob','$cpref','$coach');";
		$query = pg_query($query);
		
		
		if (!$query ) {
			$data['query'] = die("Error in SQL query: " . pg_last_error());
			$data['success'] = false;
			$data['errors'] = $errors;
		}
		else {
			$data['success'] = true;
			$data['errors'] = "Success!";
		}
	
	
	echo json_encode($data);
}
function addCoach()
{

		$cfirst = $_POST['cfirst'];
		$clast = $_POST['clast'];
		$user = $_POST['username'];
		$pass = $_POST['password'];
		$location = $_POST['location'];
		$age = $_POST['age'];

		
		
		//stored procedure db call 
		$query = "INSERT INTO coaches
		(first, last, location, age)
		VALUES
		('$cfirst', '$clast', '$location', '$age');";
	
		$query2 = "CREATE USER $user WITH PASSWORD '$pass';";

		$query = pg_query($query);
		$query2 = pg_query($query2);
		
		
		if (!$query ) {
			$data['query'] = die("Error in SQL query: " . pg_last_error());
			$data['success'] = false;
			$data['errors'] = $errors;
		}
		else {
			$data['success'] = true;
			$data['errors'] = "Success!";
		}
	
	
	echo json_encode($data);
}
function updateUser()
{

		$fname = $_POST['first'];
		$mname = $_POST['middle'];
		$lname = $_POST['last'];
		$mid = $_POST['memid'];
		$sex = $_POST['sex'];
		$phone = $_POST['phone'];
		$dob = $_POST['dob'];
		$education = $_POST['education'];
		$income = $_POST['income'];
		$vfood = $_POST['vfood'];
		$vbook = $_POST['vbook'];
		$al1 = $_POST['addressline1'];
		$al2 = $_POST['addressline2'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zip = $_POST['zip'];

		//stored procedure db call 
		$query = "UPDATE members
		SET first = '$fname', mname = '$mname', last = '$lname', sex = '$sex', phone = '$phone', dob = '$dob', education = '$education', income = '$income', vfood = '$vfood'
		WHERE mid = $mid;";
		$query2 = "UPDATE address
		SET addressline1 = '$al1', addressline2 = '$al2', city = '$city', state = '$state', zip = '$zip' FROM members
		WHERE members.mid = $mid;";
		$query = pg_query($query);
		$query = pg_query($query2);
		
		
		if (!$query ) {
			$data['query'] = die("Error in SQL query: " . pg_last_error());
			$data['success'] = false;
			$data['errors'] = $errors;
		}
		else {
			$data['success'] = true;
			$data['errors'] = "Success!";
		}
	
	
	echo json_encode($data);
}
function addEncounter()
{

		$location = $_POST['location'];
		$date = $_POST['date'];
		$notes = $_POST['notes'];
		$mid = $_POST['memid'];

		
		
		//stored procedure db call 
		$query = "INSERT INTO encounters
		(location, date, notes, mid)
		VALUES
		('$location', '$date', '$notes', '$mid');";
		$query = pg_query($query);
		
		
		if (!$query ) {
			$data['query'] = die("Error in SQL query: " . pg_last_error());
			$data['success'] = false;
			$data['errors'] = $errors;
		}
		else {
			$data['success'] = true;
			$data['errors'] = "Success!";
		}
	
	
	echo json_encode($data);
}
function addFamily()
{

		$relationship = $_POST['relationship'];
		$mid = $_POST['memid'];

		
		
		//stored procedure db call 
		$query = "INSERT INTO relationship
		(relationshiptype, mid)
		VALUES
		('$relationship', '$mid');";
		$query = pg_query($query);
		
		
		if (!$query ) {
			$data['query'] = die("Error in SQL query: " . pg_last_error());
			$data['success'] = false;
			$data['errors'] = $errors;
		}
		else {
			$data['success'] = true;
			$data['errors'] = "Success!";
		}
	
	
	echo json_encode($data);
}
function checkRole()
{
$nrole = $_POST['roles'];
$qu = "select provisioning.checkRole('$nrole')";
$result = pg_query($qu);
		
		while ($row = pg_fetch_row($result)) {		
			
			$data['isRole'] = $row[0];
			echo json_encode($data);
		}
}
function checkID()
{
$corpid = $_POST['rolname'];
$qu = "select provisioning.checkuser('$corpid')";
$result = pg_query($qu);
		
		while ($row = pg_fetch_row($result)) {		
			
			$data['ifExists'] = $row[0];
			echo json_encode($data);
		}
}
function changeRole()
{
	if (empty($_POST['groname'])) $errors['groname'] = 'Roles are required.';
	// return a response ===========================================================
	// response if there are errors
	if (!empty($errors)) {
		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['errors'] = $errors;
	}
	else {
		$rolname = $_POST['usename'];
		$orole = $_POST['groname'];
		$nrole = $_POST['roles'];
		$corpid = $_SESSION['userId'];
		
		//stored procedure db call 
		$query = "select provisioning.changeRole('$rolname','$orole', '$nrole','$corpid')";
		$query = pg_query($query);
		
		echo $query;
		
		
		if (!$query) {
			// if there are no errors, return a message
			$data['success'] = false;
			$data['errors'] = $errors;
		}
		else {
			$data['success'] = true;
			$data['errors'] = "Success!";
		}
		
			}
	echo json_encode($data);
}
		/*
		$qu = "select provisioning.checkRole('$nrole')";
		
		$result = pg_query($qu);
		
		while ($row = pg_fetch_row($result)) {		
			
			if ($row[0] == "false") {
			echo "This must be false: $row[0]";
			
					
		
				} else {
				$data['test'] = false ;
				
				}
			
			
			
		}
	
		
		
		
		
	
		*/
		
		
		
function deleteUser()
{

	$memID = $_POST['mid'];
	$query = "DELETE FROM encounters
	WHERE mid = $memID; DELETE FROM members
	WHERE mid = $memID";
	$result = pg_query($query);
	
	

	
}

function deleteCoach()
{
	$coachID = $_POST['id'];
	$query = "DELETE FROM coaches
	WHERE id = $coachID";
	//echo $query;
	$result = pg_query($query);
	
	if (!$result) {
	printf ("ERROR");
	exit();
	}
	printf ("Dropped user: $rolname from the database successfully");
	
}
// Setup for action switch
$action = !empty($_GET['action']) ? $_GET['action'] : 'default';
$method = $_SERVER['REQUEST_METHOD'];
$result = "";
switch ($method) {
//API switch case	
case 'GET':
	switch ($action) {
	case 'getcoach':
		$result = getCoaches();
		// header('Cache-Control: no-cache, must-revalidate');
		// header('content-type:application/json');
		break;

	case 'getuser':
		$result = getUsers();
		// header('Cache-Control: no-cache, must-revalidate');
		// header('content-type:application/json');
		break;
	case 'getrole':
		$result = getRoles();
		//header('Cache-Control: no-cache, must-revalidate');
		//header('content-type:application/json');
		break;
	}
	break;
case 'POST':
	switch ($action) {
	case 'create':
		$result = addUser();
		// $result = updateUser();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;
	case 'addencounter':
		$result = addEncounter();
		// $result = updateUser();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;
	case 'checkrole':
		$result = checkRole();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;
	case 'addfamily':
		$result = addFamily();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;
	case 'addcoach':
		$result = addCoach();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;
	case 'getencounter':
		$result = getEncounters();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;
	case 'updateuser':
		$result = updateUser();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;
	case 'checkid':
		$result = checkID();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;	
	case 'changerole':
		$result = changeRole();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;
	case 'deleteuser':
		$result = deleteUser();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;
	case 'deletecoach':
		$result = deleteCoach();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;
	case 'searchcoach':
		$result = searchCoach();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;
	case 'searchuser':
		$result = searchUser();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;	
	case 'logout':
		$result = logout();
		header('Cache-Control: no-cache, must-revalidate');
		header('content-type:application/json');
		break;
	}
	break;
}
?>