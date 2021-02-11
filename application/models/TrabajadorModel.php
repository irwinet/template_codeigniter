<?php 
	
	class TrabajadorModel extends CI_Model {

        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        function New($id,$codigo,$dni,$nombres,$apellidos,$nickname,$password,$celular,$direccion,$tipo,$estado,$imagen)
        {
            $data = array(  
                'varTrabajadorId' => $id,
                'varCodigo' => $codigo,
                'varDni' => $dni,
                'varNombre' => $nombres,
                'varApellidos' => $apellidos,
                'varNickName' => $nickname,
                'varPassword' => $password,
                'varTelefono' => $celular,
                'varDireccion' => $direccion,
                'varTipo' => $tipo,
                'varEstado' => $estado,
                'lbImagen' => $imagen
            );

            $this->db->insert('t_trabajador',$data);
        }

        function Update($id,$codigo,$dni,$nombres,$apellidos,$nickname,$password,$celular,$direccion,$tipo,$estado,$imagen)
        {
            $data = array(  
                'varCodigo' => $codigo,
                'varDni' => $dni,
                'varNombre' => $nombres,
                'varApellidos' => $apellidos,
                'varNickName' => $nickname,
                'varPassword' => $password,
                'varTelefono' => $celular,
                'varDireccion' => $direccion,
                'varTipo' => $tipo,
                'varEstado' => $estado,
                'lbImagen' => $imagen  
            );

            $this->db->where('varTrabajadorId', $id);
            $this->db->update('t_trabajador', $data);
        }

        function Delete($id)
        {
            $this->db->limit(1);     
            $this->db->delete('t_trabajador', array('varTrabajadorId' => $id));
            if( $this->db->affected_rows() != 1)
                return false;
            else
                return true;
        }

        function Rows()
        {
            $result = $this->db->get('t_trabajador');
            return  $result->num_rows();
        }

        function FetchByPagination($por_pagina,$segmento) 
        {
            $this->db->select('varTrabajadorId AS TrabajadorId,varCodigo AS Codigo, varDni AS Dni, varNombre AS Nombre, varApellidos AS Apellidos, varNickName AS NickName, varTelefono AS Telefono, varDireccion AS Direccion, varTipo AS Tipo, varEstado AS Estado');
            $this->db->from('t_trabajador');
            $this->db->limit($por_pagina,$segmento);
            $this->db->order_by("varApellidos","asc");
            $result = $this->db->get();

            if($result->num_rows()>0)
            {
                return $result->result_array();
            }
            else
            {
                return array();
            }
        }

        function FetchById($id)
        {

            $this->db->select('varTrabajadorId AS TrabajadorId,varCodigo AS Codigo, varDni AS Dni, varNombre AS Nombre, varApellidos AS Apellidos, varNickName AS NickName,varPassword AS Password, varTelefono AS Telefono, varDireccion AS Direccion, varTipo AS Tipo, varEstado AS Estado,lbImagen AS Imagen');
            $this->db->from('t_trabajador');
            $this->db->where('varTrabajadorId',$id);

            $query=$this->db->get();

            if($query->num_rows() > 0){
                $data = $query->row();
                $pass=$this->encrypt->decode($data->Password); 

                $result = array(
                    0 => $data->TrabajadorId, 
                    1 => $data->Codigo, 
                    2 => $data->Dni, 
                    3 => $data->Nombre,
                    4 => $data->Apellidos,
                    5 => $data->NickName,
                    6 => $pass,
                    7 => $data->Telefono,
                    8 => $data->Direccion,
                    9 => $data->Tipo,
                    10 => $data->Estado,
                    11 => $data->Imagen
                );
                
                return $result;
            }
            else
            {
                return array();
            }
        }

        function FetchByFilter($filter)
        {
            $this->db->select('varTrabajadorId AS TrabajadorId,varCodigo AS Codigo, varDni AS Dni, varNombre AS Nombre, varApellidos AS Apellidos, varNickName AS NickName, varTelefono AS Telefono, varDireccion AS Direccion, varTipo AS Tipo, varEstado AS Estado');
            $this->db->from('t_trabajador');
            $this->db->like('varCodigo', $filter);
            $this->db->or_like('varDni', $filter);
            $this->db->or_like('varNickName', $filter);
            $this->db->order_by("varApellidos","asc");

            $query = $this->db->get();

            if($query->num_rows() > 0){
                return $query->result_array();
            }else{
                return array();
            }
        }

	}

?>