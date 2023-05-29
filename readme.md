Backup 1.1 - Solo trabajo de backend.

----Implementamos jQuery AJAX para validar la información del registro desde el lado del servidor-----
De esta forma la validación del registro quedaría validada tanto en el lado del cliente como desde el mismo servidor.
Esto tiene múltiples ventajas.
Las validaciones en el lado del cliente proporcionan una experiencia más fluida al usuario al verificar los datos en tiempo real antes de enviarlos al servidor. Sin embargo, no son suficientes para garantizar la seguridad de la aplicación, ya que el lado del cliente puede ser manipulado. Por lo tanto, es esencial realizar validaciones adicionales en el lado del servidor para proteger la integridad de los datos y prevenir ataques de seguridad. Las validaciones en el lado del servidor aseguran que los datos recibidos sean coherentes y válidos antes de procesarlos y almacenarlos en la base de datos. Combinar validaciones en ambos lados brinda una mejor experiencia de usuario y garantiza la seguridad y consistencia de los datos.

----Comprobamos operaciones CRUD en la base de datos desde la página-----
La conexión a la base de datos está funcionando exitosamente.

----Inserción de todos los departamentos y municipios de Colombia a la base de datos---
Se insertaron todos los departamentos y municipios de colombia a la base de datos.
Se hizo con un script de PHP >database/transferlocations.php.
Se leyó el archivo Colombia.JSON.

----Se reforzó la integridad de los datos en el lado del cliente y del servidor----
Ahora se verifica que el celular y el documento sean datos únicamente numéricos.



Dom/28-mayo/2023

--Proximamente--
-Validaciones del login (cliente-servidor).
-Mensaje de confirmacion al correo electrónico al usuario que se esté registrando.
-User Area.
-Activar sesión personalizada (token y cookies).
