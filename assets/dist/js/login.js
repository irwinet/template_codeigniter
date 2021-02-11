$('#frmLogin').on('submit',function(e){
	e.preventDefault();
	$.ajax({
		type:$(this).attr("method"),
		url:$(this).attr("action"),
		data:$(this).serialize(),
		success:function(result)
		{
			if(result==="error")
			{
				$('#mensaje').text("Datos Incorrectos o Cuenta Inactiva");
				$('#mensaje').show(200).delay(2500).hide(200);
			}
			else
			{
				window.location.href = urlWelcome;
			}
		}
	});
});

$('#btnFacebook').on('click',function(e)
{
	var url=urlLoginFacebook;
	e.preventDefault();
	$.ajax({
		type:'POST',
		url:url,
		data:{},
		success:function(result)
		{
			if(result==="error")
			{
				$('#mensaje').text("Datos Incorrectos o Cuenta Inactiva");
				$('#mensaje').show(200).delay(2500).hide(200);
			}
			else
			{
				window.location.href = result;
			}
		}
	});
});

$('#btnCerrar').on('click',function(e){
	e.preventDefault();
	$.ajax({
		url:urlCerrarSesion,
		type:"POST",
		data: {},
		success: function()
		{
			window.location.href = urlBase;
		}
	});
});
