function showModal()
{
  $('#fmrEmployee')[0].reset();
  $("#txtTipo, #txtEstado").val('').trigger('change.select2');
  $('#pro').val('Registro');
  $('#titleM').html('<span class="fa fa-user-plus"></span> Nuevo Trabajador');
  $('#registerEmployee').modal({
    show:true,
    backdrop:'static'
  });
}

function newOrUpdateEmployee()
{
	var url = urlNewOrUpdateEmployee;
  var formData = new FormData($('#fmrEmployee')[0]);

    $.ajax({
      type:'POST',
      url:url,
      data:formData,
      cache: false,
      contentType: false,
      processData: false,
      success: function(registro){
        if ($('#pro').val() == 'Registro'){
          $('#fmrEmployee')[0].reset();
          $("#txtTipo, #txtEstado").val('').trigger('change.select2');
          $('#mensaje').addClass('msgSuccess').html('Registro Completado con Éxito').show(200).delay(2500).hide(200);
          $('#tblEmployeeList').html(registro);
          $('#pro').val('Registro');
          return false;
        }else{
          $('#mensaje').addClass('msgSuccess').html('Edición Completada con Éxito').show(200).delay(2500).hide(200);
          $('#tblEmployeeList').html(registro);        
          return false;
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#mensaje').addClass('msgError').html('La Operación no se Completo con Éxito').show(200).delay(2500).hide(200);
      }
    });
    return false;
}

function editEmployee(id){
  $('#fmrEmployee')[0].reset();
  var url = urlFetchByIdEmployee;
    $.ajax({
    type:'POST',
    url:url,
    data:'id='+id,
    success: function(valores){
        var datos = eval(valores);
        $('#pro').val('Edicion');
        $('#id-prod').val(id);
        $('#txtDni').val(datos[2]);
        $('#txtNombres').val(datos[3]);
        $('#txtApellidos').val(datos[4]);
        $('#txtNickName').val(datos[5]);
        $('#txtPassword').val(datos[6]);
        $('#txtCelular').val(datos[7]);
        $('#txtDireccion').val(datos[8]);
        $('#txtTipo').val(datos[9]).trigger("change");
        $('#txtEstado').val(datos[10]).trigger("change");
        $('#txtFoto2').val(datos[11]);
       
        $('#titleM').html('<span class="fa fa-edit"></span> Editar Trabajador');
        $('#registerEmployee').modal({
          show:true,
          backdrop:'static'
        });
      return false;
    }
  });
  return false;
}

function deleteEmployee(id){
  var url = urlDeleteEmployee;
  var pregunta = confirm('¿Esta seguro de eliminar este Trabajador?');
  if(pregunta==true){
    $.ajax({
      type:'POST',
      url:url,
      data:'id='+id,
      success: function(registro){
        $('#tblEmployeeList').html(registro);
        return false;
      }
    });
    return false;
  }else{
    return false;
  }
}

function fetchAll()
{  
    var url = urlFetchAllEmployee;
    $.ajax({
      type:'POST',
      url:url,
      data:{},
      success: function(result){
        $('#tblEmployeeList').html(result);
      }
    });
    return false;
}

$('#txtFoto').on('change',function(){
    var fileName = $(this).val().toLowerCase();
    var idxDot = fileName.lastIndexOf(".") + 1;
    var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
    if(extFile=="png" || extFile=="jpg" || extFile=="jpeg"){
      var sizeFotoMB=this.files[0].size/1024/1024;
      if(sizeFotoMB>2)
      {
        $('#fileFoto').find('p').text('El tamaño máximo es 2MB!');
        $('#fileFoto').modal({
          show:true,
          backdrop:'static'
        });
        $("#txtFoto").val("");
      }
    }   
    else
    {
      $('#fileFoto').find('p').text('Solo se permite archivos JPG/JPEG y PNG!');
      $('#fileFoto').modal({
        show:true,
        backdrop:'static'
      });
      $("#txtFoto").val("");
    }
});

$('#btnLimpiar').on('click',function(){
  $('#txtFiltro').val('');
  $('#chbFiltro').val('1');
});

$('#frmSearch').on('submit',function(e){
  e.preventDefault();
  $.ajax({
    type:$(this).attr("method"),
    url:$(this).attr("action"),
    data:$(this).serialize(),
    success:function(result)
    {
      $('#tblEmployeeList').html(result);
    }
  });
});

setInterval(fetchAll, 300000);