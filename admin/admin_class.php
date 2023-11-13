<?php
session_start();
ini_set('display_errors', 1);
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{

		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			if ($_SESSION['login_type'] != 1) {
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				return 2;
				exit;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function login2()
	{

		extract($_POST);
		if (isset($email))
			$username = $email;
		$qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			if ($_SESSION['login_alumnus_id'] > 0) {
				$bio = $this->db->query("SELECT * FROM alumnus_bio where id = " . $_SESSION['login_alumnus_id']);
				if ($bio->num_rows > 0) {
					foreach ($bio->fetch_array() as $key => $value) {
						if ($key != 'passwors' && !is_numeric($key))
							$_SESSION['bio'][$key] = $value;
					}
				}
				return 1;
			}
			// Code start by Hamza for Org
			// print($_SESSION['login_org_id']);
			if ($_SESSION['login_org_id'] > 0) {

				$org = $this->db->query("SELECT * FROM orgnaization_bio where org_id = " . $_SESSION['login_org_id']);
				if ($org->num_rows > 0) {
					foreach ($org->fetch_array() as $key => $value) {
						if ($key != 'passwors' && !is_numeric($key))
							$_SESSION['org'][$key] = $value;
					}
				}
			}
			// foreach ($_SESSION as $key => $value) {
			// 	var_dump($_SESSION[$key]);
			// }
			// return 11;
			// if ($_SESSION['org']['org_status'] != 1) { needs to be uncommented
			// 	foreach ($_SESSION as $key => $value) {
			// 		unset($_SESSION[$key]);
			// 	}
			// 	return 2;
			// 	exit;
			// }
			return 5; // the user is an organization
			// Code end by Hamza
			if ($_SESSION['bio']['status'] != 1) {
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				return 2;
				exit;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if (!empty($password))
			$data .= ", password = '" . md5($password) . "' ";
		$data .= ", type = '$type' ";
		if ($type == 1)
			$establishment_id = 0;
		$data .= ", establishment_id = '$establishment_id' ";
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users set " . $data);
		} else {
			$save = $this->db->query("UPDATE users set " . $data . " where id = " . $id);
		}
		if ($save) {
			return 1;
		}
	}
	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete)
			return 1;
	}
	function signup()
	{
		extract($_POST);
		// define('SITE_ROOT', realpath(dirname(__FILE__)));
		// var_dump('SITE_ROOT')
		// return 900;
		$data = '';
		// $lastname = 'ali';
		// $email = 'abcc@gmail.com';
		// $user_type = 10;
		// $user_type = (int)$user_type;
		// var_dump($firstname);
		// var_dump($lastname);
		// var_dump($email);
		// var_dump($user_type);
		// $data = " name = '" . $firstname . ' ' . $lastname . "' ";
		// $data = " name = '" . $lastname . ' ' . $lastname . "' ";
		// var_dump($_FILES['img']['tmp_name']);
		// return;
		$data .= "name = '$lastname' ";
		$data .= ", username = '$email' ";
		$data .= ", type = '$user_type' ";
		$data .= ", password = '" . md5($password) . "' ";

		$pass = $password;
		$cpass = $confirm_password;
		// 1. check password and confirm password
		if ($pass != $cpass) {
			return 5;
		}
		// 2. check email already exists or not
		// $chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		// if ($chk > 0) {
		// 	return 100;
		// 	exit;
		// }
		// return $user_type;
		// 3. insert data into user table if everything is alright
		$save = '';
		// $save = $this->db->query("INSERT INTO users set " . $data);
		$name = $firstname;
		$username = $email;
		$password = md5($password);
		$type = $user_type;
		$alumnus_id = 0;
		$org_id = 0;
		$sql = "INSERT INTO users (name,username,password,type,alumnus_id,org_id) VALUES ('$name','$username','$password','$type','$alumnus_id','$org_id')";
		$save = $this->db->query($sql);
		// test start***************
		// $sql = "INSERT INTO departments (name) VALUES ('John')";
		// $save = $this->db->query($sql);

		// $name = "John";
		// $org_id = 21;
		// $sql = "INSERT INTO departments (name,org_id) VALUES ('$name','$org_id')";
		// $save = $this->db->query($sql);

		// if ($save) {
		// 	echo "Insertion successful";
		// } else {
		// 	echo "Error: " . $this->db->error;
		// }
		// return;
		// test end ****************
		if ($save) {
			// return 1000;
			$uid = $this->db->insert_id;
			$data = '';
			foreach ($_POST as $k => $v) {
				if ($k == 'password')
					continue;
				if (empty($data) && !is_numeric($k))
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			// if ($_FILES['img']['tmp_name'] != '') {
			// 	$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			// 	$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			// 	$data .= ", avatar = '$fname' ";
			// }
			// Check user type ****** Code start by Hamza
			// return $user_type;
			// print($user_type);
			// exit();
			if ($user_type == 20) { // If user type is organization, then save data in Organization table
				// $save_org = $this->db->query("INSERT INTO orgnaization_bio set $data ");
				// $data = '';
				// $data = " name = '$firstname' ";
				$data = '';
				$fname = '';
				$org_status = 1;
				$data = "name = '" . $firstname . ' ' . $lastname . "' ";
				$data .= ", email = '$email' ";
				$data .= ", phone = '$phone' ";
				$data .= ", org_type = '$org_type' ";
				$data .= ", org_status = '$org_status' ";
				$data .= ", address = '$address' ";
				if ($_FILES['img']['tmp_name'] != '') {
					// define('SITE_ROOT', realpath(dirname(__FILE__)));
					// move_uploaded_file($_FILES['file']['tmp_name'], SITE_ROOT.'/static/images/slides/1/1.jpg');
					// http://52.66.22.163/
					$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
					$move = move_uploaded_file($_FILES['img']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/admin/assets/uploads/' . $fname);
					$data .= ", avatar = '$fname' ";
				} else {
					$fname = '';
					$data .= ", avatar = '$fname' ";
				}


				// $data .= ", avatar = '$fname' ";

				$save_org = $this->db->query("INSERT INTO orgnaization_bio set $data ");
				// print("Data saved.");
				// print($save_org);
				// var_dump($data);
				if ($save_org) {
					$oid = $this->db->insert_id;
					$this->db->query("UPDATE users set org_id = $oid where id = $uid ");
					$login = $this->login2();
					if ($login)
						return 2;
				}
				return 2;
			} else if ($user_type == 3) {  // alumni signup
				// print("Hello alumni");
				// print($user_type);
				// exit();
				// exit();
				// **************** Code end by Hamza
				$data = '';
				$move = '';
				// if ($_FILES['img']['tmp_name'] != '') {
				// 	$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
				// 	$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
				// 	// $data .= ", avatar = '$fname' ";
				// }

				$data = " firstname = '" . $firstname . ' ' . $lastname . "' ";
				$data .= ", email = '$email' ";
				$data .= ", phone = '$phone' ";
				// $data .= ", org_type = '$org_type' ";
				$data .= ", address = '$address' ";
				$data .= ", gender = '$gender' ";
				$data .= ", batch = '$batch' ";
				$data .= ", connected_to = '$connected_to' ";
				$data .= ", org_id = '$academia_id' ";
				$data .= ", dept_id = '$dept_id' ";
				$data .= ", status = '1' ";
				$data .= ", alumni = '1' "; // insert 1 if this is alumni signup
				if ($_FILES['img']['tmp_name'] != '') {
					$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
					$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
					$data .= ", avatar = '$fname' ";
				}
				// $data .= ", avatar = '$move' ";
				// var_dump($data);
				// exit();
				// $save_alumni = $this->db->query("INSERT INTO alumnus_bio SET firstname='1John Doe', email='john@example.com'");
				// var_dump($fname);
				// var_dump($move);
				// var_dump($data);
				$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
				// var_dump($save_alumni);
				// exit();
				// print($save_alumni);
				if ($save_alumni) {
					// print("Data submitted");
					$aid = $this->db->insert_id;
					// var_dump($aid);
					// exit();
					$login = $this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
					// $login = $this->login2();
					if ($login)
						return 1;
				}
			} else if ($user_type == 10) { // student signup
				$data = '';
				$move = '';
				// if ($_FILES['img']['tmp_name'] != '') {
				// 	$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
				// 	$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
				// 	// $data .= ", avatar = '$fname' ";
				// }

				$data = " firstname = '" . $firstname . ' ' . $lastname . "' ";
				$data .= ", email = '$email' ";
				$data .= ", phone = '$phone' ";
				// $data .= ", org_type = '$org_type' ";
				$data .= ", address = '$address' ";
				$data .= ", gender = '$gender' ";
				$data .= ", batch = '$batch' ";
				// $data .= ", connected_to = '$connected_to' ";
				$data .= ", org_id = '$academia_id' ";
				$data .= ", dept_id = '$dept_id' ";
				$data .= ", status = '1' ";
				$data .= ", alumni = '0' "; // insert 0 if this is student signup
				if ($_FILES['img']['tmp_name'] != '') {
					$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
					$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
					$data .= ", avatar = '$fname' ";
				}
				// $data .= ", avatar = '$move' ";
				// var_dump($data);
				// exit();
				// $save_alumni = $this->db->query("INSERT INTO alumnus_bio SET firstname='1John Doe', email='john@example.com'");
				// var_dump($fname);
				// var_dump($move);
				// var_dump($data);
				$save_student = $this->db->query("INSERT INTO alumnus_bio set $data ");
				// var_dump($save_alumni);
				// exit();
				// print($save_alumni);
				if ($save_student) {
					// print("Data submitted");
					$aid = $this->db->insert_id;
					// var_dump($aid);
					// exit();
					$login = $this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
					// $login = $this->login2();
					if ($login)
						return 1;
				}
			}
		} else {
			return 205;
		}
	}
	function load_depts()
	{
		extract($_POST);
		// $uniId = $id;
		$result = $this->db->query("SELECT * FROM departments where org_id = $id ");
		$r = $result->fetch_all();
		$data_json = json_encode($r);
		echo $data_json;
		// print_r($r);
		// exit();
		// return $result;
		// if ($result->num_rows > 0) {
		// 	// var_dump($result);
		// 	// Encode the data as JSON
		// 	// $data_json = json_encode($result);

		// 	// Output the JSON
		// 	// echo $data_json;
		// 	$data_json = json_encode($result->fetch_array());
		// 	echo $data_json;
		// 	// exit;
		// }
		// echo $id;
		return;
	}
	function update_account()
	{
		extract($_POST);
		$data = " name = '" . $firstname . ' ' . $lastname . "' ";
		$data .= ", username = '$email' ";
		if (!empty($password))
			$data .= ", password = '" . md5($password) . "' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if ($chk > 0) {
			return 2;
			exit;
		}
		$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if ($save) {
			$data = '';
			foreach ($_POST as $k => $v) {
				if ($k == 'password')
					continue;
				if (empty($data) && !is_numeric($k))
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if ($_FILES['img']['tmp_name'] != '') {
				$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
				$data .= ", avatar = '$fname' ";
			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if ($data) {
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if ($login)
					return 1;
			}
		}
	}

	function save_settings()
	{
		extract($_POST);
		$data = " name = '" . str_replace("'", "&#x2019;", $name) . "' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '" . htmlentities(str_replace("'", "&#x2019;", $about)) . "' ";
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", cover_img = '$fname' ";
		}

		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set " . $data);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set " . $data);
		}
		if ($save) {
			$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if (!is_numeric($key))
					$_SESSION['settings'][$key] = $value;
			}

			return 1;
		}
	}


	function save_course()
	{
		extract($_POST);
		$data = " course = '$course' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO courses set $data");
		} else {
			$save = $this->db->query("UPDATE courses set $data where id = $id");
		}
		if ($save)
			return 1;
	}
	function delete_course()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM courses where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	// code added by Hamza ******
	function delete_dept()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM departments where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_dept()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", org_id = '$org_id' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO departments set $data");
		} else {
			$save = $this->db->query("UPDATE courses set $data where id = $id");
		}
		if ($save)
			return 1;
	}
	function delete_org()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM orgnaization_bio where org_id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function update_org_acc()
	{
		extract($_POST);
		$update = $this->db->query("UPDATE orgnaization_bio set org_status = $status where org_id = $id");
		if ($update)
			return 1;
	}
	// code end by Hamza
	function update_alumni_acc()
	{
		extract($_POST);
		$update = $this->db->query("UPDATE alumnus_bio set status = $status where id = $id");
		if ($update)
			return 1;
	}
	function save_gallery()
	{
		extract($_POST);
		$img = array();
		$fpath = 'assets/uploads/gallery';
		$files = is_dir($fpath) ? scandir($fpath) : array();
		foreach ($files as $val) {
			if (!in_array($val, array('.', '..'))) {
				$n = explode('_', $val);
				$img[$n[0]] = $val;
			}
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO gallery set about = '$about' ");
			if ($save) {
				$id = $this->db->insert_id;
				$folder = "assets/uploads/gallery/";
				$file = explode('.', $_FILES['img']['name']);
				$file = end($file);
				if (is_file($folder . $id . '/_img' . '.' . $file))
					unlink($folder . $id . '/_img' . '.' . $file);
				if (isset($img[$id]))
					unlink($folder . $img[$id]);
				if ($_FILES['img']['tmp_name'] != '') {
					$fname = $id . '_img' . '.' . $file;
					$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/gallery/' . $fname);
				}
			}
		} else {
			$save = $this->db->query("UPDATE gallery set about = '$about' where id=" . $id);
			if ($save) {
				if ($_FILES['img']['tmp_name'] != '') {
					$folder = "assets/uploads/gallery/";
					$file = explode('.', $_FILES['img']['name']);
					$file = end($file);
					if (is_file($folder . $id . '/_img' . '.' . $file))
						unlink($folder . $id . '/_img' . '.' . $file);
					if (isset($img[$id]))
						unlink($folder . $img[$id]);
					$fname = $id . '_img' . '.' . $file;
					$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/gallery/' . $fname);
				}
			}
		}
		if ($save)
			return 1;
	}
	function delete_gallery()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM gallery where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_career()
	{
		extract($_POST);
		$data = " company = '$company' ";
		$data .= ", job_title = '$title' ";
		$data .= ", location = '$location' ";
		$data .= ", description = '" . htmlentities(str_replace("'", "&#x2019;", $description)) . "' ";

		if (empty($id)) {
			// echo "INSERT INTO careers set ".$data;
			$data .= ", user_id = '{$_SESSION['login_id']}' ";
			$save = $this->db->query("INSERT INTO careers set " . $data);
		} else {
			$save = $this->db->query("UPDATE careers set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_career()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM careers where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_forum()
	{
		extract($_POST);
		$data = " title = '$title' ";
		$data .= ", description = '" . htmlentities(str_replace("'", "&#x2019;", $description)) . "' ";

		if (empty($id)) {
			$data .= ", user_id = '{$_SESSION['login_id']}' ";
			$save = $this->db->query("INSERT INTO forum_topics set " . $data);
		} else {
			$save = $this->db->query("UPDATE forum_topics set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_forum()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM forum_topics where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_comment()
	{
		extract($_POST);
		$data = " comment = '" . htmlentities(str_replace("'", "&#x2019;", $comment)) . "' ";

		if (empty($id)) {
			$data .= ", topic_id = '$topic_id' ";
			$data .= ", user_id = '{$_SESSION['login_id']}' ";
			$save = $this->db->query("INSERT INTO forum_comments set " . $data);
		} else {
			$save = $this->db->query("UPDATE forum_comments set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_comment()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM forum_comments where id = " . $id);
		if ($delete) {
			return 1;
		}
	}
	function save_event()
	{
		extract($_POST);
		$data = " title = '$title' ";
		$data .= ", schedule = '$schedule' ";
		$data .= ", content = '" . htmlentities(str_replace("'", "&#x2019;", $content)) . "' ";
		if ($_FILES['banner']['tmp_name'] != '') {
			$_FILES['banner']['name'] = str_replace(array("(", ")", " "), '', $_FILES['banner']['name']);
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['banner']['name'];
			$move = move_uploaded_file($_FILES['banner']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", banner = '$fname' ";
		}
		if (empty($id)) {

			$save = $this->db->query("INSERT INTO events set " . $data);
		} else {
			$save = $this->db->query("UPDATE events set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_event()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM events where id = " . $id);
		if ($delete) {
			return 1;
		}
	}

	function participate()
	{
		extract($_POST);
		$data = " event_id = '$event_id' ";
		$data .= ", user_id = '{$_SESSION['login_id']}' ";
		$commit = $this->db->query("INSERT INTO event_commits set $data ");
		if ($commit)
			return 1;
	}
}
