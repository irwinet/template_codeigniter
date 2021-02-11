<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      &nbsp;
      <small>&nbsp;</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo site_url('Home');?>"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Trabajadores - Gestor</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Criterios de Búsqueda</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Colapso">
            <i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Retirar">
            <i class="fa fa-times"></i>
          </button>
        </div>
      </div>
      <div class="box-body">
        <?php 
          $attributes = array('id'=>'frmSearch','class'=>'form-horizontal');  
          echo form_open('trabajador/FetchByFilter',$attributes)
        ?>
          <div class="form-group">
            <label for="chbFiltro" class="col-sm-1 control-label">Filtro Clave:</label>

            <div class="col-sm-2">
              <select class="form-control" id="chbFiltro" name="chbFiltro">
                  <option value="1">Codigo</option>
                  <option value="2">Dni</option>
                  <option value="3">NickName</option>
                </select>
            </div>

            <div class="col-sm-2">
              <input type="text" name="txtFiltro" id="txtFiltro" class="form-control backgroundInput" placeholder="Valor">
            </div>
            
            <div class="col-sm-offset-5 col-sm-2"> 
              <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-search"></i> Buscar</button>
              <button type="button" id="btnLimpiar" class="btn btn-default pull-left"><i class="fa fa-eraser"></i> Limpiar</button>
            </div>
            
          </div>
        </form>
      </div>
    </div>
    <!-- /.box -->

    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Lista de Resultados</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Colapso">
            <i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Retirar">
            <i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-xs-12">              
              <!-- /.box-header -->
              <div id="tblEmployeeList" class="box-body table-responsive no-padding">
                <table class="table table-hover">
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
                  </tr>
                    <?php 
                      foreach ($trabajadores as $trabajador){
                    ?>
                      <tr>
                        <td align="center">
                          <a href="javascript:editEmployee('<?php echo $trabajador['TrabajadorId']; ?>');" class="btn btn-primary btn-xs" title="Actualizar Trabajador"><i class="fa fa-edit"></i></a>
                          <a href="javascript:deleteEmployee('<?php echo $trabajador['TrabajadorId']; ?>');" class="btn btn-primary btn-xs" title="Eliminar Trabajador"><i class="fa fa-bitbucket"></i></a>
                        </td>
                        <td><?php echo $trabajador['Codigo'] ?></td>
                        <td><?php echo $trabajador['Dni'] ?></td>
                        <td><?php echo $trabajador['Apellidos'].', '.$trabajador['Nombre'] ?></td>
                        <td><?php echo $trabajador['NickName'] ?></td>
                        <td><?php echo $trabajador['Telefono'] ?></td>
                        <td><?php echo $trabajador['Direccion'] ?></td>
                        <td><?php echo $trabajador['Tipo'] ?></td>
                        <?php if($trabajador['Estado']=='A'){ ?>
                          <td>
                            <span class="label label-success">Activo</span>
                          </td>
                        <?php } else { ?>
                          <td>
                            <span class="label label-danger">Inactivo</span>
                          </td>
                        <?php } ?>
                      </tr>
                    <?php 
                      }
                    ?>
                </table>
                <div class="pull-right">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
              </div>
              <!-- /.box-body -->
          </div>
        </div>
      </div>
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- Modal Employee -->
<div class="modal fade" id="registerEmployee" tabindex="-1" role="dialog" aria-labelledby="myModalEmployee" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="titleM"><span class="fa fa-user-plus"></span> Nuevo Trabajador</h4>
        </div>

        <?php 
          $attributes = array('class' => 'fmrEmployee','id'=>'fmrEmployee','onsubmit'=>'return newOrUpdateEmployee();');  
          echo form_open_multipart('',$attributes)
        ?>

        <div class="modal-body">
            <table border="0" width="100%">
              <tr>
                  <td colspan="2"><input type="hidden" required="required" id="id-prod" name="id-prod" readonly="readonly" class="form-control"/></td>
              </tr>
              <tr>
                <td><label class="control-label" for="pro">Proceso:</label></td>
                <td><input type="text" required="required" class="form-control" readonly="readonly" id="pro" name="pro"/></td>
              </tr>
              <tr>
                <td><label class="control-label" for="txtDni">Dni: </label></td>
                <td><input onkeypress="validateOnlyNumbers();" class="form-control" type="text" name="txtDni" id="txtDni" placeholder="Digite su Dni" maxlength="8" required></td>
              </tr>
              <tr>
                <td><label class="control-label" for="txtNombres">Nombres: </label></td>
                <td><input onkeypress="return validateOnlyLetters(event);" class="form-control" type="text" name="txtNombres" id="txtNombres" placeholder="Digite sus Nombres" required></td>
              </tr>
              <tr>
                <td><label class="control-label" for="txtApellidos">Apellidos: </label></td>
                <td><input onkeypress="return validateOnlyLetters(event);" class="form-control" type="text" name="txtApellidos" id="txtApellidos" placeholder="Digite sus Apellidos" required></td>
              </tr>
              <tr>
                <td><label class="control-label" for="txtNickName">NickName: </label></td>
                <td><input class="form-control" type="text" name="txtNickName" id="txtNickName" placeholder="Digite su NickName" required></td>
              </tr>
              <tr>
                <td><label class="control-label" for="txtPassword">Contraseña: </label></td>
                <td><input class="form-control" type="password" name="txtPassword" id="txtPassword" placeholder="Digite su Contraseña" required></td>
              </tr>
              <tr>
                <td><label class="control-label" for="txtCelular">Celular: </label></td>
                <td><input onkeypress="validateOnlyNumbers();" class="form-control" type="text" name="txtCelular" id="txtCelular" placeholder="Digite su Celular" maxlength="9"></td>
              </tr>
              <tr>
                <td><label class="control-label" for="txtDireccion">Dirección: </label></td>
                <td><input class="form-control" type="text" name="txtDireccion" id="txtDireccion" placeholder="Digite su Dirección" required></td>
              </tr>
              <tr>
                <td><label class="control-label" for="txtTipo">Tipo: </label></td>
                <td>
                  <select name="txtTipo" id="txtTipo" class="form-control select2" style="width: 100%;" required>
                    <option value="">(Seleccione)</option>
                    <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                    <option value="USUARIO">USUARIO</option>
                  </select>
                </td>
              </tr> 
              <tr>
                <td><label class="control-label" for="txtEstado">Estado: </label></td>
                <td>
                  <select name="txtEstado" id="txtEstado" class="form-control select2" style="width: 100%;" required>
                    <option value="">(Seleccione)</option>
                    <option value="A">ACTIVO</option>
                    <option value="I">INACTIVO</option>
                  </select>
                </td>
              </tr> 
              <tr>
                <td colspan="2"><input class="form-control" type="hidden" readonly="readonly" name="txtFoto2" id="txtFoto2"></td>
              </tr>
              <tr>
                <td><label class="control-label" for="txtFoto">Foto: </label></td>
                <td>
                  <input class="form-control" type="file" name="txtFoto" id="txtFoto" accept="image/*">
                  <p class="help-block">Máximo 2 MB - PNG/JPG/JPEG</p>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                    <div id="mensaje"></div>
                </td>
              </tr>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>

      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="fileFoto" tabindex="-1" role="dialog" aria-labelledby="myModalFoto" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="titleM"><span class="fa fa-image"></span> Foto del Trabajador</h4>
        </div>

        <div class="modal-body">
          <p class="text-justify">
            
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
        </div>

    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>