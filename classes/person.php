<?
class Person{
	private $person_Id;
	private $first_name;
	private $last_name;
	private $address;
	private $email;
	private $phone;
	
	public function __construct($person_id,$first_name,$last_name,$address,$email,$phone){
		$this->person_id = $person_id;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->email = $email;
		$this->phone = $phone;
	}
	
	public function getPersonId(){
		return $this->person_id;
	}
	
	public function getFirstName(){
		return $this->first_name;
	}
	
	public function getLastName(){
		return $this->last_name;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function getPhone(){
		return $this->email;
	}
}
?>