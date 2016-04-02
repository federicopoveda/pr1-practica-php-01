# Seguir instrucciones iniciales en el `forro.sql`
# - Crear base de datos
# - Crear el usuario
# - Le brindamos privilegios al usuario

# Creamos nuestra tabla de usuarios
CREATE TABLE usuarios(
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  full_name VARCHAR(50),
  PRIMARY KEY (id)
);
