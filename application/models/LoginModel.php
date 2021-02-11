<?php 
	
	class LoginModel extends CI_Model {

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
            $this->load->library('encrypt');
        }

        function autentification($nickname,$password){
        
		    $this->db->select("varNombre AS Nombre, varApellidos AS Apellidos, varNickName, varEstado, varPassword,varTipo AS Tipo,lbImagen AS Imagen");
		    $this->db->from("t_trabajador");
		    $this->db->where("varNickName",$nickname);
		    $this->db->where("varEstado","A");
		    
		    $query = $this->db->get();

		    if($query->num_rows() > 0){
		      $result = $query->row();
		      $pass=$this->encrypt->decode($result->varPassword); 

		      if ($pass==$password) {
		        return $result;
		      }
		    }else{
		      return false;
		    }
		        
		}

	}

?>