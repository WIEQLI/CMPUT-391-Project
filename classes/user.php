<?
class User{
	private $user_name;
	private $password;
	private $class_of;
	private $person_id;
	private $date_registered;
	
	public function __construct($user_name,$password,$class_of,$person_id,$date_registered){
	$this->user_name = $user_name;
	$this->password = $password;
	$this->class_of = $class_of;
	$this->person_id = $person_id;
	$this->date_registered = $date_registered;
	}
	
	public function getUserName(){
		return $this->user_name;
	}
	
	public function getPassword(){
		return $this->password;
	}
	
	public function getClassOf(){
		return $this->class_of;
	}
	
	public function getPersonId(){
		return $this->person_id;
	}
	
	public function getDateRegistered(){
		return $this->date_registered;
	}
}
?>