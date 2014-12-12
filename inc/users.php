<?php
/**
*	users class: login/logout
 *               check user credentials
 *               change user credentials
*/

class users
{	
	protected $db;

    /**
     * @param $database
     */
    public function __construct($database)
	{
	    $this->db = $database;
	}


    /**
     * @param void
     * @return bool
     * get contact settings data
     */
    public function contact_get_data()
    {
        $query = $this->db->prepare("SELECT * FROM `contact_settings`");

        try
        {
            $query->execute();

            if($query->rowCount() > 0)
            {
                $settings_data = $query->fetchAll();
                return $settings_data;
            }
            else
            {
                return false;
            }
        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param $address
     * @param $telephone
     * @param $email
     * change contact settings
     */
    public function change_contact_settings($address, $telephone, $email)
    {
        $address = htmlspecialchars($address, ENT_QUOTES, "UTF-8");
        $telephone = htmlspecialchars($telephone, ENT_QUOTES, "UTF-8");
        $email = htmlspecialchars($email, ENT_QUOTES, "UTF-8");

        $query = $this->db->prepare("UPDATE `contact_settings` SET `address` = :address, `telephone` = :telephone, `email` = :email");
        $query->bindParam(":address", $address, PDO::PARAM_STR);
        $query->bindParam(":telephone", $telephone, PDO::PARAM_STR);
        $query->bindParam(":email", $email, PDO::PARAM_STR);

        try
        {
            $query->execute();

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }
		
    /**
     * @param $id
     * get current user data
     */
    public function user_get_data($id)
	{
		$query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id LIMIT 1");
		$query->bindParam(":id", $id, PDO::PARAM_INT);
		
		try
		{		
			$query->execute();

			if($query->rowCount() == 1)
			{
                $user_data = $query->fetchAll();
				return $user_data;
			} 
			else 
			{
				return false;
			}
			
		} 
		catch(PDOException $ex)
		{
			die($ex->getMessage());
		}
	}

    /**
     * @param $username
     * @param $password
     * @param $ip
     * main login function
     */
    public function login($username, $password, $ip)
	{
		$username = htmlspecialchars($username, ENT_QUOTES, "UTF-8");
		$lastOnline = time();
		$password = sha1($password);
		
		$query = $this->db->prepare("SELECT `id` FROM `users` WHERE username = :username AND password = :password");
		$query->bindParam(":username", $username, PDO::PARAM_STR);
		$query->bindParam(":password", $password, PDO::PARAM_STR);

		try
		{		
			$query->execute();
			
			if($query->rowCount() == 1)
			{
				$sql_update = $this->db->prepare("UPDATE `users` SET `ip` = :ip, `lastOnline` = :lastOnline WHERE `username` = :username");
				$sql_update->bindParam(":ip", $ip, PDO::PARAM_STR);
				$sql_update->bindParam(":lastOnline", $lastOnline, PDO::PARAM_STR);
				$sql_update->bindParam(":username", $username, PDO::PARAM_STR);
				$sql_update->execute();
				
				return $query->fetchColumn(0);	
			} 
			else 
			{
				return false;
			}
			
		} catch(PDOException $ex){
			die($ex->getMessage());
		}	
		
	}

    /**
     * @param $userid
     * @param $username
     * @param $email
     * user credentials change - only username/email
     */
    public function user_small_change($userid, $username, $email)
    {
		$username = htmlspecialchars($username, ENT_QUOTES, "UTF-8");
        $email = htmlspecialchars($email, ENT_QUOTES, "UTF-8");

        $query = $this->db->prepare("UPDATE `users` SET `email` = :email, `username` = :username WHERE `id` = :id");
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->bindParam(":id", $userid, PDO::PARAM_INT);
		
		try{		
			$query->execute();

		} catch(PDOException $ex){
			die($ex->getMessage());
		}
	}

    /**
     * @param $userid
     * @param $username
     * @param $email
     * @param $password
     * user credentials change - everything
     */
    public function user_full_change($userid, $username, $email, $password)
    {
        $username = htmlspecialchars($username, ENT_QUOTES, "UTF-8");
        $email = htmlspecialchars($email, ENT_QUOTES, "UTF-8");
        $password = sha1($password);

        $query = $this->db->prepare("UPDATE `users` SET `email` = :email, `username` = :username, `password` = :password WHERE `id` = :id");
        $query->bindParam(":email", $email, PDO::PARAM_STR);
        $query->bindParam(":username", $username, PDO::PARAM_STR);
        $query->bindParam(":password", $password, PDO::PARAM_STR);
        $query->bindParam(":id", $userid, PDO::PARAM_INT);

        try{
            $query->execute();

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param $ip_address
     * return failedLogin ID if exists
     */
    public function getFailedLogin($ip_address)
    {
        $query = $this->db->prepare("SELECT * FROM `failed_logins` WHERE `ip_address` = :ip_address");
        $query->bindParam("ip_address", $ip_address, PDO::PARAM_STR);

        try{
            $query->execute();
            if($query->rowCount() == 1)
            {
                return $query->fetchColumn(0);
            }
            else
            {
                return false;
            }

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param $ip_address
     * add user IP to failed logins
     */
    public function addFailedLogin($ip_address)
    {
        $query = $this->db->prepare("INSERT INTO `failed_logins`(`ip_address`) VALUES(:ip_address)");
        $query->bindParam("ip_address", $ip_address, PDO::PARAM_STR);

        try{
            $query->execute();
            return true;

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param $ip_address
     * @param $login_id
     * update counter for IP and failed login
     */
    public function updateFailedLogin($ip_address, $login_id)
    {
        $query = $this->db->prepare("UPDATE `failed_logins` SET `ip_address` = :ip_address, `counter` = `counter` + 1 WHERE `id` = :id");
        $query->bindParam("ip_address", $ip_address, PDO::PARAM_STR);
        $query->bindParam("id", $login_id, PDO::PARAM_INT);

        try{
            $query->execute();
            return true;

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

    /**
     * @param $failed_attempts
     * get all failed logins with more than specific number of tries
     */
    public function getAllFailedLogins($failed_attempts)
    {
        $query = $this->db->prepare("SELECT `ip_address` FROM `failed_logins` WHERE `counter` >= :counter", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $query->bindParam("counter", $failed_attempts, PDO::PARAM_INT);

        $ip_list = array();

        try{
            $query->execute();
            for ($i = 0; $row = $query->fetch(); $i++) {
                $ip_list[] = $row;
            }

            return $ip_list;

        } catch(PDOException $ex){
            die($ex->getMessage());
        }
    }

	
    /**
     * @param void
     * logout current user
     */
    public function logout()
    {
		session_start();
		session_destroy();
		header("Location: ../");
		exit();
	}	
}

?>