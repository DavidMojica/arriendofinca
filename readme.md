<<<<<<< HEAD
Backup 1.2 - Solo trabajo de backend.

--Inicio de sesion--
Ahora cada usuario registrado puede ingresar con una sesion totalmente propia al sistema.
Se valida todo desde el cliente y desde el servidor.

--Inmoviliarios--
Cada usuario puede crear su inmoviliario y editarlo. 
Los cambios de hacen en la base de datos también.

--Verificar inicio de sesion--
Ahora la mayoría de peticiones y todas las paginas confirman que el usuario esté logueado para poder
hacer algo dentro del sistema, sino lo remite al login.

--Cerrar sesion--
Si se encuentra una sesion iniciada, se crea un botón que cierra la sesión.

--Simplicidad del codigo--
Se optimizó el codigo a través de dos archivos llamados essentials, cuya funcion es evitar la reiteracion de innecesaria
de código generando un código mas limpio y legible.

--Validaciones del login, crear propiedad y editar propiedad--
Se implementaron varias validaciones que refuerzan la seguridad de la pagina y la integridad de los datos

--Uso de PDO--
Se empezó a usar PDO para garantizar que el sistema esté libre de inyecciones SQL,
mejorar los tiempos de respuesta y orden.


Mié/7-junio/2023

--Proximamente--
--Motor de búsqueda de inmoviliarios
--Anular el inicio de sesion si tiene la sesion iniciada
--Front End
