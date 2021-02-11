CREATE DATABASE db_transito_vial;

USE db_transito_vial;

CREATE TABLE T_Propietario
( 
  varPropietarioId varchar(36) not null,
  varCodigo char(8) not null,
  varDni varchar(17) null,
  varNombre varchar(100),
  varApellidos varchar(30) null,
  varDomicilio varchar(40) null,
  varDistrito varchar(20) null,
  PRIMARY KEY (varPropietarioId)
);

CREATE TABLE T_Efectivo_Pnp
(
  varEfectivoId varchar(36) not null,
  varCodigo char(6) not null,
  varCip char(8) null,
  varNombre varchar(100),
  varApellidos varchar(30) null,
  varUnidad varchar(25) null,
  PRIMARY KEY (varEfectivoId)
);

CREATE TABLE T_Empresa
(
  varEmpresaId varchar(36) not null,
  varCodigo char(4) not null,
  varNombre varchar(100)null,
  varRuta varchar(100) null,
  varNroIntegrantes int null,
  varUnidad varchar(10) null,
  varServicio varchar(30),
  varEstado char(1) null,
  PRIMARY KEY (varEmpresaId)
);

CREATE TABLE T_Trabajador
(
  varTrabajadorId varchar(36) not null,
  varCodigo char(4) not null,
  varDni numeric(8,0) not null,
  varNombre varchar(50) null,
  varApellidos varchar(30) null,
  varNickName varchar(30) null,
  varPassword varchar(200) null,
  varTelefono varchar(9) null,
  varDireccion varchar(30) null,
  varTipo varchar(30) null,
  varEstado char(1) null,
  lbImagen longblob,
  PRIMARY KEY (varTrabajadorId)
);

CREATE TABLE T_Testigo
(
  varTestigoId varchar(36) not null,
  varCodigo char(7) not null,
  varDni char(8) not null,
  varNombre varchar(50) not null,
  varApellidos varchar(30) null,
  varMediosProbatorios varchar(50) null,
  PRIMARY KEY (varTestigoId)
);

CREATE TABLE T_Infraccion
(
  varInfraccionId varchar(36) not null,
  varCodigo char(4) null,
  varNumero char(4) not null,
  varDescripcion varchar(300) null,
  dalMulta_UIT tinyint null,
  dalValor_UIT decimal(19,4) null,
  varTipo varchar(12) null,
  varDescuento varchar(8) null,
  PRIMARY KEY (varInfraccionId)
);

CREATE TABLE T_Descuento
(
  varDescuentoId varchar(36) not null,
  intPago int null,
  intDias int null,
  varEstado char(1) null,
  varDetalle varchar(250) null,
  PRIMARY KEY (varDescuentoId)
);
 
CREATE TABLE T_Licencia_Conducir
(
  varLicenciaConducirId varchar(36) not null,
  varCodigo char(7) not null,
  varNumero varchar(10) null,
  varClase varchar(7) null,
  dmeFechaExpedicion datetime null,
  dmeFechaRevalidacion datetime null,
  varRestriciones varchar(40) null,
  PRIMARY KEY (varLicenciaConducirId)
);

CREATE TABLE T_Vehiculo
(
  varVehiculoId varchar(36) not null,
  varCodigo varchar(8) not null,
  varNumeroPlaca varchar(12) null,
  varColor varchar(20) null,
  varMarca varchar(30) null,
  varClase varchar(12) null,
  numCapacidad numeric(2,0) null,
  numCargaUtil numeric(4,3) null,
  intAnioFabricacion int null,
  varNroTargetaidentidadVhi varchar(9)null,
  varPropietarioId varchar(36) null,
  varEmpresaId varchar(36) null,
  PRIMARY KEY (varVehiculoId),
  FOREIGN KEY (varPropietarioId)REFERENCES T_Propietario(varPropietarioId),
  FOREIGN KEY (varEmpresaId) REFERENCES T_Empresa(varEmpresaId)
);

CREATE TABLE T_Conductor
(
  varConductorId varchar(36) not null,
  varCodigo char(7) not null,
  varDni char(8) null,
  varNombre varchar(50) null,
  varApellidos varchar(30) null,
  varLicenciaConducirId varchar(36) null,
  dmeFechaNacimiento datetime null,
  varDomicilio varchar(30) null, 
  PRIMARY KEY (varConductorId),
  FOREIGN KEY (varLicenciaConducirId) REFERENCES T_Licencia_Conducir(varLicenciaConducirId)
);

CREATE TABLE T_Historial
(
  varHistorialId varchar(36) not null,
  varCodigo char(6) not null,
  varTrabajadorId varchar(36) not null,
  varEvento varchar(80) null,
  dmeFechaEvento datetime null,
  varDetalle varchar(200) null,
  PRIMARY KEY (varHistorialId),
  FOREIGN KEY (varTrabajadorId) REFERENCES T_Trabajador(varTrabajadorId)
);

CREATE TABLE T_Tarjeta_Circulacion
(
  varTarjetaCirculacionId varchar(36) not null,
  varNumero char(7) not null,
  varTrabajadorId varchar(36) null,
  varPropietarioId varchar(36) null,
  varVehiculoId varchar(36) null,
  varEmpresaId varchar(36) null,
  varNumeroResolucion int null,
  dmeFechaEmision datetime null,
  dmeFechaVencimiento datetime null,
  PRIMARY KEY (varTarjetaCirculacionId),
  FOREIGN KEY (varTrabajadorId) REFERENCES T_Trabajador(varTrabajadorId),
  FOREIGN KEY (varPropietarioId) REFERENCES T_Propietario(varPropietarioId),
  FOREIGN KEY (varVehiculoId) REFERENCES T_Vehiculo(varVehiculoId),
  FOREIGN KEY (varEmpresaId) REFERENCES T_Empresa(varEmpresaId)
);

CREATE TABLE T_Boleta
(
  varBoletaId varchar(36) not null,
  varNumero numeric(6,0) not null,
  varTrabajadorId varchar(36) null,
  varPlaca varchar(12) null,
  varNombreConductor varchar (50) null,
  dmeFechaPago datetime null,
  dalTotalBruto decimal(19,4) null,
  dalPago decimal(5,2) null,
  dalMontoTotal decimal(19,4) null,
  varConceptoPago varchar(100) null,
  varObservacion varchar(500) null,
  varEstado varchar(10) null,
  PRIMARY KEY (varBoletaId),
  FOREIGN KEY (varTrabajadorId) REFERENCES T_Trabajador(varTrabajadorId)
);

CREATE TABLE T_Detalle_Boleta
(
  varDetalleBoletaId varchar(36) not null,
  varNumeroPapeleta numeric(8,0) not null,
  varNumeroBoleta numeric(6,0) not null,
  varCodidoInfraccion varchar(10) null,
  dalImporte decimal(19,4) null,
  dalMontoDependencia decimal(19,4) null,
  PRIMARY KEY (varDetalleBoletaId,varNumeroPapeleta,varNumeroBoleta)
);

CREATE TABLE T_Detalle_Otro
(
  varDetalleOtroId varchar(36) not null,
  varNumeroBoleta numeric(6,0) not null,
  varDetalle varchar(50) not null,
  intCantidad int null,
  dalPrecioUnitario decimal(19,4) null,
  dalImporte decimal(19,4) null,
  PRIMARY KEY (varDetalleOtroId,varNumeroBoleta,varDetalle)
);

CREATE TABLE T_Papeleta
(
  varPapeletaId varchar(36) not null,
  varNumero numeric(8,0) not null,
  varTrabajadorId varchar(36) null,
  varInfraccionId varchar(36) null,
  varVehiculoId varchar(36) null,
  varEfectivoId varchar(36) null,
  varConductorId varchar(36) null,
  varLugar varchar(30) null,
  varCuadra varchar(20) null,
  dmeFecha datetime null,
  varImagen varchar(60) null,
  varEstado varchar(10) null,
  varNumerOficio varchar(10) null,
  varObservacion varchar(150) null,
  varObservacionPnp varchar(150) null,
  varObservacionConductor varchar(150) null,
  varTestigoId varchar(36) null,
  PRIMARY KEY (varPapeletaId),
  FOREIGN KEY (varTrabajadorId) REFERENCES T_Trabajador(varTrabajadorId),
  FOREIGN KEY (varInfraccionId) REFERENCES T_Infraccion(varInfraccionId),
  FOREIGN KEY (varVehiculoId) REFERENCES T_Vehiculo(varVehiculoId),
  FOREIGN KEY (varEfectivoId) REFERENCES T_Efectivo_Pnp(varEfectivoId),
  FOREIGN KEY (varConductorId) REFERENCES T_Conductor(varConductorId),
  FOREIGN KEY (varTestigoId) REFERENCES T_Testigo(varTestigoId)
);