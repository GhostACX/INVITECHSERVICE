-- Crear base de datos
CREATE DATABASE IF NOT EXISTS inventario_reparaciones;
USE inventario_reparaciones;

-- Crear tabla computadoras con todos los campos
CREATE TABLE IF NOT EXISTS computadoras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    marca VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_envio DATE NOT NULL,
    fecha_entrega DATE DEFAULT NULL,
    empresa_reparacion VARCHAR(100) NOT NULL,
    estado VARCHAR(50) DEFAULT 'En reparación'
);