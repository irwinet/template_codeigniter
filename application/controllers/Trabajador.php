<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trabajador extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('login'))
        {
            redirect(base_url());
        }
        $this->load->model('TrabajadorModel');
        $this->load->helper('url');
        $this->load->library('upload');
        $this->load->library('encrypt');
        $this->load->helper('form');
    }

	public function index()
	{
        $pages=10; 
        $this->load->library('pagination'); 
        $config['base_url'] = site_url('Employee/Manager');
        $config['total_rows'] = $this->TrabajadorModel->Rows();
        $config['per_page'] = $pages; 
        $config['num_links'] = 20;
        $config['first_link'] = 'Primera';
        $config['last_link'] = 'Última';
        $config["uri_segment"] = 3;
        $config['next_link'] = 'Siguiente';
        $config['prev_link'] = 'Anterior';

        $this->pagination->initialize($config);
        $data["trabajadores"] = $this->TrabajadorModel->FetchByPagination($config['per_page'],$this->uri->segment(3));    

		$this->load->view('paginas/cabeza.php', $data);
		$this->load->view('trabajador/listar.php');
		$this->load->view('paginas/pie.php');
	}

	public function NewOrUpdate()
    {
        if($this->input->is_ajax_request())
        {
            
            $this->load->helper('url');

            $proceso= $this->input->post('pro');      
            $guid=sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
            $id = ($this->input->post('id-prod')=="")?$guid:$this->input->post('id-prod');
            $dni = $this->input->post('txtDni');
            $nombres = strtoupper($this->input->post('txtNombres'));
            $apellidos = strtoupper($this->input->post('txtApellidos'));
            
            $concat='';
            $arrayApellidos = explode(" ",$apellidos);
            foreach ($arrayApellidos as $value) {
                $concat=$concat.$value[0];
            }
            $codigo = $nombres[0].$concat;     
            
            $nickname = $this->input->post('txtNickName');
            $password = $this->encrypt->encode($this->input->post('txtPassword'));
            $celular = $this->input->post('txtCelular');
            $direccion = strtoupper($this->input->post('txtDireccion'));
            $tipo = $this->input->post('txtTipo');
            $estado = $this->input->post('txtEstado');

            //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA UPLOAD_VIEW
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['max_size'] = '2048';
            $config['max_width'] = '2024';
            $config['max_height'] = '2008';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('txtFoto')) {
                $imgActualUser=$this->input->post('txtFoto2');
                if($imgActualUser=="")
                {
                    $imagen="apiettravel.png";
                }
                else
                {
                    $imagen=$imgActualUser;
                }
                
            } else {
                //EN OTRO CASO SUBIMOS LA IMAGEN, CREAMOS LA MINIATURA Y HACEMOS 
                //ENVÍAMOS LOS DATOS AL MODELO PARA HACER LA INSERCIÓN
                $file_info = $this->upload->data();
                //USAMOS LA FUNCIÓN create_thumbnail Y LE PASAMOS EL NOMBRE DE LA IMAGEN,
                //ASÍ YA TENEMOS LA IMAGEN REDIMENSIONADA
                $this->_create_thumbnail($file_info['file_name']);
                $data = array('upload_data' => $this->upload->data());
                $imagen = $file_info['file_name'];                
            }

            switch($proceso){
                case 'Registro':
                    $result = $this->TrabajadorModel->New($id,$codigo,$dni,$nombres,$apellidos,$nickname,$password,$celular,$direccion,$tipo,$estado,$imagen);
                	break;                    
                case 'Edicion':
                    $result = $this->TrabajadorModel->Update($id,$codigo,$dni,$nombres,$apellidos,$nickname,$password,$celular,$direccion,$tipo,$estado,$imagen);
                	break;
            }
     
            $this->Result();
        }
        else
        {
            show_404();
        }
    }

    public function Delete()
    {
        if($this->input->is_ajax_request())
        {
            $this->load->helper('url');

            $trabajadorId = $this->input->post('id');

            $data = $this->TrabajadorModel->FetchById($trabajadorId);
            if($data[0]!=null)
            {
                $this->load->helper("file");
                
                $file = $data[11];
                if($file!="apiettravel.png")
                {
                    $path = './uploads/'.$file;
                    $pathThumbs = './uploads/thumbs/'.$file;

                    unlink($path);
                    unlink($pathThumbs);
                }
            }
 
            $result = $this->TrabajadorModel->Delete($trabajadorId); 

            $this->Result();
        }
        else
        {
            show_404();
        }
    }

    public function FetchAll()
    {
        if($this->input->is_ajax_request())
        { 
            $this->Result();
        }
        else
        {
            show_404();
        }   
    }

    public function Result()
    {
        $pages=10; 
        $this->load->library('pagination'); 
        $config['base_url'] = site_url('Employee/Manager'); 
        $config['total_rows'] = $this->TrabajadorModel->Rows();
        $config['per_page'] = $pages; 
        $config['num_links'] = 20; 
        $config['first_link'] = 'Primera';
        $config['last_link'] = 'Última';
        $config["uri_segment"] = 3;
        $config['next_link'] = 'Siguiente';
        $config['prev_link'] = 'Anterior';
        $this->pagination->initialize($config); 
        $trabajadores = $this->TrabajadorModel->FetchByPagination($config['per_page'],$this->uri->segment(3));          

        echo '<table class="table table-hover">
                <tr>
                    <th class="text-center">
                        <a href="javascript:showModal();" id="newEmployee" class="btn btn-primary btn-xs" title="Nuevo Trabajador"><i class="fa fa-plus"></i></a>
                    </th>
                    <th>CODIGO</th>
                    <th>DNI</th>
                    <th>APELLIDOS Y NOMBRES</th>
                    <th>NICKNAME</th>
                    <th>TELEFONO</th>
                    <th>DIRECCIÓN</th>
                    <th>TIPO</th>
                    <th>ESTADO</th>                     
                </tr>';
                foreach ($trabajadores as $trabajador):
                    if($trabajador['Estado']=='A')
                    {
                        $trabajador['Estado']='<span class="label label-success">Activo</span>';
                    }
                    else
                    {
                        $trabajador['Estado']='<span class="label label-danger">Inactivo</span>';
                    }
                    echo '<tr>
                            <td align="center">
                                <a href="javascript:editEmployee(\''.$trabajador['TrabajadorId'].'\');" class="btn btn-primary btn-xs" title="Actualizar Trabajador"><i class="fa fa-edit"></i></a>
                                <a href="javascript:deleteEmployee(\''.$trabajador['TrabajadorId'].'\');" class="btn btn-primary btn-xs" title="Eliminar Trabajador"><i class="fa fa-bitbucket"></i></a>
                            </td>
                            <td>'.$trabajador['Codigo'].'</td>
                            <td>'.$trabajador['Dni'].'</td>
                            <td>'.$trabajador['Apellidos'].', '.$trabajador['Nombre'].'</td>                                       
                            <td>'.$trabajador['NickName'].'</td>
                            <td>'.$trabajador['Telefono'].'</td>
                            <td>'.$trabajador['Direccion'].'</td>
                            <td>'.$trabajador['Tipo'].'</td>
                            <td>'.$trabajador['Estado'].'</td>
                          </tr>';
                endforeach;
        echo '</table>';
        echo '<div class="pull-right">';
              echo $this->pagination->create_links();
        echo '</div>';
    }

    public function FetchById()
    {
        if($this->input->is_ajax_request())
        {
            $trabajadorId = $this->input->post('id');
            $result = $this->TrabajadorModel->FetchById($trabajadorId); 
            echo json_encode($result);
        }
        else
        {
            show_404();
        }
    }

    public function FetchByFilter()
    {
        if($this->input->is_ajax_request())
        {
            $filter = $this->input->post('txtFiltro');
            $trabajadores = $this->TrabajadorModel->FetchByFilter($filter);    

            if($filter=="")
            {
                $this->Result();
            }   
            else
            {
                echo '<table class="table table-hover">
                        <tr>
                            <th class="text-center">
                                <a href="javascript:showModal();" id="newEmployee" class="btn btn-primary btn-xs" title="Nuevo Trabajador"><i class="fa fa-plus"></i></a>
                            </th>
                            <th>CODIGO</th>
                            <th>DNI</th>
                            <th>APELLIDOS Y NOMBRES</th>
                            <th>NICKNAME</th>
                            <th>TELEFONO</th>
                            <th>DIRECCIÓN</th>
                            <th>TIPO</th>
                            <th>ESTADO</th>                     
                        </tr>';
                        foreach ($trabajadores as $trabajador):
                            if($trabajador['Estado']=='A')
                            {
                                $trabajador['Estado']='<span class="label label-success">Activo</span>';
                            }
                            else
                            {
                                $trabajador['Estado']='<span class="label label-danger">Inactivo</span>';
                            }
                            echo '<tr>
                                    <td align="center">
                                        <a href="javascript:editEmployee(\''.$trabajador['TrabajadorId'].'\');" class="btn btn-primary btn-xs" title="Actualizar Trabajador"><i class="fa fa-edit"></i></a>
                                        <a href="javascript:deleteEmployee(\''.$trabajador['TrabajadorId'].'\');" class="btn btn-primary btn-xs" title="Eliminar Trabajador"><i class="fa fa-bitbucket"></i></a>
                                    </td>
                                    <td>'.$trabajador['Codigo'].'</td>
                                    <td>'.$trabajador['Dni'].'</td>
                                    <td>'.$trabajador['Apellidos'].', '.$trabajador['Nombre'].'</td>                                       
                                    <td>'.$trabajador['NickName'].'</td>
                                    <td>'.$trabajador['Telefono'].'</td>
                                    <td>'.$trabajador['Direccion'].'</td>
                                    <td>'.$trabajador['Tipo'].'</td>
                                    <td>'.$trabajador['Estado'].'</td>
                                  </tr>';
                        endforeach;
                echo '</table>';
            }
        }
        else
        {
            show_404();
        }
    }

    function _create_thumbnail($filename){
        $config['image_library'] = 'gd2';
        //CARPETA EN LA QUE ESTÁ LA IMAGEN A REDIMENSIONAR
        $config['source_image'] = 'uploads/'.$filename;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        //CARPETA EN LA QUE GUARDAMOS LA MINIATURA
        $config['new_image']='uploads/thumbs/';
        $config['width'] = 150;
        $config['height'] = 150;
        $this->load->library('image_lib', $config); 
        $this->image_lib->resize();
    }

}
