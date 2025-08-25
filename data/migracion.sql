-- Crear la base de datos
CREATE DATABASE wiigoInventario;
USE wiigoInventario;

-- CREAR TABLAS ---
CREATE TABLE rol (
	IdRol int not null AUTO_INCREMENT PRIMARY KEY,
	Nombre varchar(30) not null
);

CREATE TABLE persona(
	IdPersona int not null AUTO_INCREMENT PRIMARY KEY,
	Nombre varchar(40) not null,
	Apellido varchar(40) not null,
	Documento varchar(10) not null,
	Sexo varchar(1) not null,
	Correo varchar(40) not null,
	Salario decimal DEFAULT 0,
	PorcentajeComision decimal,
	FechaVinculacion date,
	FechaTerminacion date,
	IdRol int not null,
	CONSTRAINT fk_persona_rol FOREIGN KEY (IdRol) REFERENCES rol(IdRol),
	CONSTRAINT ck_persona_Sexo CHECK(Sexo = 'M' OR Sexo = 'F'),
	CONSTRAINT ck_persona_PorcentajeComision CHECK(PorcentajeComision <= 1),
	CONSTRAINT ck_persona_Salario CHECK(Salario > 0)
);

CREATE TABLE tipoCalzado(
	IdTipoCalzado int not null AUTO_INCREMENT PRIMARY KEY,
	Nombre varchar(30) not null
);

CREATE TABLE marca(
	IdMarca int not null AUTO_INCREMENT PRIMARY KEY,
	Nombre varchar(40) not null
);

CREATE TABLE calzado(
	IdCalzado int not null AUTO_INCREMENT PRIMARY KEY,
	Genero varchar(1) not null,
	Descripcion varchar(100) not null,
	IdTipoCalzado int not null,
	IdMarca int not null,
	IdProveedor int not null,
	CONSTRAINT fk_calzado_tipoCalzado FOREIGN KEY (IdTipoCalzado) REFERENCES tipoCalzado(IdTipoCalzado),
	CONSTRAINT fk_calzado_marca FOREIGN KEY (IdMarca) REFERENCES marca(IdMarca),
	CONSTRAINT ck_calzado_Genero CHECK(Genero = 'F' OR Genero = 'M' OR Genero = 'U')	
);

CREATE TABLE inventario(
	IdInventario int not null AUTO_INCREMENT PRIMARY KEY,
	Existencia int not null,
	Precio int not null DEFAULT 0,
	Talla int not null,
	IdCalzado int not null,
	CONSTRAINT fk_inventario_calzado FOREIGN KEY (IdCalzado) REFERENCES calzado(IdCalzado),
	CONSTRAINT ck_inventario_Existencia CHECK(Existencia >= 0),
	CONSTRAINT ck_inventario_Precio CHECK(Precio >= 0),
	CONSTRAINT ck_inventario_Talla CHECK(Talla > 16 AND Talla < 49)
);

CREATE TABLE pedido(
	IdPedido int not null AUTO_INCREMENT PRIMARY KEY,
	Fecha date not null,
	AdjuntarFoto LONGBLOB,
	IdPersona int not null,
	CONSTRAINT fk_pedido_persona FOREIGN KEY (IdPersona) REFERENCES persona(IdPersona)
);

CREATE TABLE recibo(
	IdRecibo int not null AUTO_INCREMENT PRIMARY KEY,
	FechaHora datetime not null DEFAULT CURRENT_TIMESTAMP,
	IdPersona int not null,
	CONSTRAINT fk_recibo_persona FOREIGN KEY (IdPersona) REFERENCES persona(IdPersona)
);

CREATE TABLE detalleRecibo(
	IdDetalleRecibo int not null AUTO_INCREMENT PRIMARY KEY,
	Cantidad int not null,
	Precio int not null DEFAULT 0,
	IdCalzado int not null,
	IdRecibo int not null,
	CONSTRAINT fk_detalleRecibo_recibo FOREIGN KEY (IdRecibo) REFERENCES recibo(IdRecibo),
	CONSTRAINT fk_detalleRecibo_calzado FOREIGN KEY (IdCalzado) REFERENCES calzado(IdCalzado),
	CONSTRAINT ck_detalleRecibo_Cantidad CHECK(Cantidad > 0),
	CONSTRAINT ck_detalleRecibo_Precio CHECK(Precio >= 0)
);