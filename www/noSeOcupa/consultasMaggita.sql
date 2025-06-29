
-- Consulta de los clientes registrados
-- solo se selecciona el id y el nombre para desplegar el filtro
SELECT 
    Clientes.id as id_cliente, 
    Clientes.nombre as nombre, 
    Clientes.apellido as apellido

FROM Clientes;


-- Consulta para Pedidos pendientes por cliente
-- donde se encuentra el numero "4" es donde iria dinamicamente
-- el id del cliente a seleccionar, en este caso solo selecciona
-- los pedidos del cliente con id=4
SELECT 
    Pedido.id as id ,Pedido.id_cliente as id_cliente, 
    Pedido.id_direccion as id_direccion, 
    DATE(Pedido.fecha) as fecha, 
    Pedido.monto as monto, 
    Pedido.estado as estado 

FROM 
    Pedido,Clientes

WHERE 
    estado=0 AND Clientes.id = 4 AND Clientes.id = Pedido.id_cliente;



-- Consulta para Pedidos entregados por cliente
-- donde se encuentra el numero "4" es donde iria dinamicamente
-- el id del cliente a seleccionar, en este caso solo selecciona
-- los pedidos del cliente con id=4
SELECT 
    Pedido.id as id ,Pedido.id_cliente as id_cliente, 
    Pedido.id_direccion as id_direccion, 
    DATE(Pedido.fecha) as fecha, 
    Pedido.monto as monto, 
    Pedido.estado as estado 

FROM 
    Pedido,Clientes

WHERE 
    estado=1 AND Clientes.id = 4 AND Clientes.id = Pedido.id_cliente;




-- Consulta para Pedidos pendientes en general
-- se obtiene el nombre del cliente para saber quien lo hizo

SELECT 
    Pedido.id as id,
    Pedido.id_direccion as id_direccion, 
    DATE(Pedido.fecha) as fecha, 
    Pedido.monto as monto, 
    Pedido.estado as estado,
    Clientes.nombre as nombre,
    Clientes.apellido as apellido

FROM 
    Pedido, Clientes

WHERE 
    estado=0 AND Clientes.id = Pedido.id_cliente;


-- Consulta para Pedidos entregados en general
-- se obtiene el nombre del cliente para saber quien lo hizo

SELECT 
    Pedido.id as id,
    Pedido.id_direccion as id_direccion, 
    DATE(Pedido.fecha) as fecha, 
    Pedido.monto as monto, 
    Pedido.estado as estado,
    Clientes.nombre as nombre,
    Clientes.apellido as apellido

FROM 
    Pedido, Clientes

WHERE 
    estado=1 AND Clientes.id = Pedido.id_cliente;


