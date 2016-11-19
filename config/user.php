<?php
include('password.php');
class User extends Password{

    private $_db;

    function __construct($db){
    	parent::__construct();

    	$this->_db = $db;
    }

	private function get_user_hash($username){

		try {
      // Havent included acivate yet
      //$stmt = $this->_db->prepare('SELECT password, username, id FROM users WHERE username = :username AND active="Yes" ');
			$stmt = $this->_db->prepare('SELECT password, username, id, firstname, surname, department, usertype FROM users WHERE username = :username');
			$stmt->execute(array('username' => $username));

			return $stmt->fetch();


		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function login($username,$password){

		$row = $this->get_user_hash($username);

		if($this->password_verify($password,$row['password']) == 1){
		    $_SESSION['loggedin'] = true;
		    $_SESSION['username'] = $row['username'];
		    $_SESSION['id'] = $row['id'];
        $_SESSION['name'] = $row['firstname'];
        $_SESSION['usertype'] = $row['usertype'];
        $_SESSION['lastname'] = $row['surname'];
        $_SESSION['department'] = $row['department'];
		    return true;
		}
	}


  public function getuserid(PDO $pdo, $userid){
    $stmt = $this->_db->prepare('SELECT id FROM users WHERE username = :username');
    $result = $stmt->execute(array(':userid' => $userid));
    return $result->fetchFirst(PDO::FETCH_ASSOC);
  }


	public function logout(){
		session_destroy();
	}

	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}
	}
}
?>
