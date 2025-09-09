<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "inventario_reparaciones";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Contadores para resumen
$total = $conn->query("SELECT COUNT(*) as total FROM computadoras")->fetch_assoc()['total'];
$en_reparacion = $conn->query("SELECT COUNT(*) as total FROM computadoras WHERE estado='En reparación'")->fetch_assoc()['total'];
$reparado = $conn->query("SELECT COUNT(*) as total FROM computadoras WHERE estado='Reparado'")->fetch_assoc()['total'];
$entregada = $conn->query("SELECT COUNT(*) as total FROM computadoras WHERE estado='Entregada'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pantalla de Inicio - Reparaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #0d6efd, #4dabf7);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-family: 'Segoe UI', sans-serif;
        }

        .inicio-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        }

        .inicio-card h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
        }

        .stats .card {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            border-radius: 15px;
            padding: 20px;
            width: 120px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stats .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        .btn-enter {
            border-radius: 10px;
            padding: 10px 30px;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

<div class="inicio-card">
    <h1><i class="bi bi-laptop"></i> Bienvenido al Sistema de Reparaciones</h1>
    <p class="lead">Monitorea todas las computadoras de manera rápida y sencilla</p>

    <div class="stats">
        <div class="card">
            <h3><?= $total ?></h3>
            <p>Total</p>
        </div>
        <div class="card">
            <h3><?= $en_reparacion ?></h3>
            <p>En reparación</p>
        </div>
        <div class="card">
            <h3><?= $reparado ?></h3>
            <p>Reparadas</p>
        </div>
        <div class="card">
            <h3><?= $entregada ?></h3>
            <p>Entregadas</p>
        </div>
    </div>

    <a href="Reparaciones.php" class="btn btn-light btn-enter"><i class="bi bi-arrow-right-circle"></i> Ir al Sistema</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
