import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

#Datos de configuración del correo
email_adress   = "email_origen"
email_password = "contraseña_email_origen"
smtp_server    ="smtp.gmail.com"
smtp_port      = 587

def enviar_correo_verificacion(destinatario, token):
    #Crear el mensaje del correo
    mensaje = MIMEMultipart()
    mensaje["From"]    = email_adress
    mensaje["To"]      = destinatario
    mensaje["Subject"] = "Verificación de correo electrónico"
    
    #Cuerpo del mensaje
    cuerpo_mensaje = f"Equipo de (empresa) \n Por favor haz click en el siguiente enlace para veriicar tu correo electrónico : https://ejemplo.com/verificar?token={token}"
    mensaje.attach(MIMEText(cuerpo_mensaje, "plain"))
    
    #Establecer conexión con el servidor SMTP
    server = smtplib.SMTP(smtp_server, smtp_port)
    server.starttls()
    server.login(email_adress, email_password)
    
    #Enviar el correo
    server.sendmail(email_adress, destinatario, mensaje.as_string())
    server.quit()
    print("se logró enviar")
    
#Ejemplo de uso
usuario = "David"
correo  = "destinatario@gmail.com"
token   = "1523"

enviar_correo_verificacion(correo, token)