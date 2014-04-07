<?php
/*
* Theme Name: AdminScript_AddTeacherAndPages
* Description:	This theme takes in a csv of teacher's names, their email, and their 
*				associated classes. The teacher's names, the class names and their
*				email must be unique. The file must be a .csv of the form
*				$teacherName, $teacherEmail, $class1ID, $class2ID,..., $classNID
*				The script will then check to see if the teacher account is already
*				created, if not if it will do so, then will create pages and groups
*				for the teachers class's
*/

// testing script: Calls createNewwTeachers with a predefined array of teachers
$testingTeachers = array(
				"jdm10c@my.fsu.edu" => "Jarrod Moore",
				"rkmor2@gmail.com"  => "Bob Bo Billy"
				);
createNewTeachers($testingTeachers);




/*
* Function:		createNewTeachers
* Arguments:	array with email as keys and value as names
* Notes:		Only new teachers should be in this array. 
function createNewTeachers
*/
function createNewTeachers($newTeacherArray)
{

//$currentTeachers = get_user('role=author');	//All teachers are stored as authors

$userName = array();
foreach($newTeacherArray as $teacherEmail => $value){	//Gets user name from email
	$temp = explode("@", $teacherEmail);
	$userName[$teacherEmail] = $temp[0];
	}

$firstName = array();
$lastName  = array();
$seperatedName = array();
foreach($newTeacherArray as $teacherEmail => $fullName){
	$seperatedName = explode(" ", $fullName);
	$lastName[$teacherEmail] = array_pop($seperatedName);
	$firstName[$teacherEmail] = implode(" ", $seperatedName);
/*	echo $seperatedName[$teacherEmail];
	echo "\n";
	echo $firstName[$teacherEmail];
	echo "\n";
	echo $lastName[$teacherEmail];
	echo "\n";
*/	
	}
	
foreach($newTeacherArray as $teacherEmail => $value){
	$userData = array(
					'user_login' => $teacherEmail,
					'first_name' => $firstName[$teacherEmail],
					'last_name'  => $lastName[$teacherEmail],
					'user_email' => $teacherEmail,
					'role'		 => "author",
					'user_pass'  => "EOASfsu");
					
/*	echo $userData['user_login'];
	echo "\n";
	echo $userData['first_name'];
	echo "\n";
	echo $userData['last_name'];
	echo "\n";
	echo $userData['user_email'];
	echo "\n";
*/	
	$user_id = wp_insert_user($userData);
	if(!is_wp_error($user_id)){
		echo "User created : ". $user_id;
		echo "\n";
		}
	}	
}
?>


