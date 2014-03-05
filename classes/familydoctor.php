<?
class FamilyDoctor{
	private doctor_id;
	private patient_id;
	
	public function __construct($doctor_id,$patient_id){
		$this->doctor_id = $doctor_id;
		$this->patient_id = $patient_id;
	}
	
	public function getDoctorId(){
		return $this->doctor_id;
	}
	
	public function getPatientId(){
		return $this->patient_id;
	}
	
}
?>