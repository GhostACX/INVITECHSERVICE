-- Crear la base de datos (si no existe)
CREATE DATABASE IF NOT EXISTS inventario_reparaciones;

-- Seleccionar la base de datos
USE inventario_reparaciones;

-- Crear la tabla pantallas
CREATE TABLE IF NOT EXISTS pantallas (
  PINES VARCHAR(50),
  TAMANO VARCHAR(10),
  MODELO VARCHAR(50),
  CANT INT,
  TOUCH VARCHAR(10),
  TIPO VARCHAR(20)
);
INSERT INTO pantallas (ID,PINES, TAMANO, MODELO, CANT, TOUCH, TIPO) VALUES
('40 pines', '15.6"', 'B156XW04 V.5', 1, 'NO', 'BORDES'),
('40 pines ESTRECHO', '15.6"', 'R156NWF7 R2 HW:4.2', 1, 'SI', 'NO'),
('40 pines', '14.0"', 'B140XTK01.0 HW6A', 1, 'SI', 'BORDES'),
('40 pines', '13.3"', 'B133HAK01.4 HW0A', 1, 'SI', 'NO'),
('40 pines', '14.0"', 'B140HAK01.1 HW1A', 1, 'SI', 'BORDES'),
('40 pines', '14.0"', 'B140XW03 V.0 HW6A', 1, 'NO', 'BORDES'),
('40 pines', '14.0"', 'LP140WFB(SP)(F2)', 1, 'SI', 'NO'),
('40 pines', '17.3"', 'NE173QHM-NY2', 1, 'NO', 'NO'),
('40 pines', '15.6"', 'LM156LF2F', 1, 'NO', 'GAMER');
