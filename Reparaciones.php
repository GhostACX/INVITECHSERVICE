<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "inventario_reparaciones";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Insertar nueva computadora
if (isset($_POST['guardar'])) {
    $cliente = $_POST['cliente'];
    $telefono = $_POST['telefono'];
    $marca = $_POST['marca'];
    $descripcion = $_POST['descripcion'];
    $fecha_envio = date('Y-m-d');
    $estado = "En reparación";

    $stmt = $conn->prepare("INSERT INTO computadoras (cliente, telefono, marca, descripcion, fecha_envio, estado) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $cliente, $telefono, $marca, $descripcion, $fecha_envio, $estado);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Marcar como Reparado
if (isset($_GET['reparado'])) {
    $id = intval($_GET['reparado']);
    $stmt = $conn->prepare("UPDATE computadoras SET estado = 'Reparado' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Entregar al cliente
if (isset($_GET['entregar'])) {
    $id = intval($_GET['entregar']);
    $fecha_entrega = date('Y-m-d');
    $stmt = $conn->prepare("UPDATE computadoras SET estado = 'Entregada', fecha_entrega = ? WHERE id = ?");
    $stmt->bind_param("si", $fecha_entrega, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Eliminar registro
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM computadoras WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Buscar y filtrar
$where = "";
if (isset($_GET['estado']) && $_GET['estado'] != "todos") {
    $estado = $conn->real_escape_string($_GET['estado']);
    $where .= "WHERE estado = '$estado'";
}

if (isset($_GET['buscar']) && $_GET['buscar'] != "") {
    $buscar = $conn->real_escape_string($_GET['buscar']);
    $where .= ($where ? " AND " : "WHERE ") . "(cliente LIKE '%$buscar%' OR marca LIKE '%$buscar%' OR telefono LIKE '%$buscar%')";
}

$result = $conn->query("SELECT * FROM computadoras $where ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Reparaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f8;
            padding-top: 90px;
        }

        /* Navbar moderno */
        .navbar {
            background: linear-gradient(90deg, #0d6efd, #4dabf7);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
            color: #fff;
        }

        .navbar-brand i {
            font-size: 1.3rem;
            margin-right: 5px;
        }

        .navbar .nav-link {
            color: #fff !important;
            margin-left: 10px;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 8px;
        }

        .navbar .nav-link i {
            font-size: 1.1rem;
            margin-right: 5px;
        }

        .navbar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transition: 0.3s;
        }

        .navbar-toggler {
            border: none;
            outline: none;
        }

        /* Cards modernas */
        .card {
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        /* Formulario elegante */
        .form-control {
            border-radius: 8px;
            padding: 10px 12px;
        }

        .btn-primary, .btn-success, .btn-danger, .btn-outline-secondary, .btn-outline-warning, .btn-outline-primary, .btn-outline-success {
            border-radius: 8px;
        }

        /* Badges colores más suaves */
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-primary {
            background-color: #0d6efd;
        }
        .badge-success {
            background-color: #198754;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand" href="#"><i class="bi bi-laptop"></i> Reparaciones</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item me-2">
          <a class="nav-link" href="index.php"><i class="bi bi-speedometer2"></i> Inicio</a>
        </li>
        <li class="nav-item me-2">
          <a class="nav-link" href="PANTALLAS.php"><i class="bi bi-display"></i> Inventario Pantallas</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
    <h2 class="text-center mb-4"><i class="bi bi-card-checklist"></i> Sistema de Reparaciones</h2>

    <!-- Formulario -->
    <div class="card p-4 mb-4 shadow-sm">
        <form method="POST">
            <div class="row mb-3">
                <div class="col-md-6 mb-2">
                    <input type="text" name="cliente" class="form-control" placeholder="👤 Cliente" required>
                </div>
                <div class="col-md-6 mb-2">
                    <input type="text" name="telefono" class="form-control" placeholder="📞 Teléfono" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6 mb-2">
                    <input type="text" name="marca" class="form-control" placeholder="💻 Marca" required>
                </div>
                <div class="col-md-6 mb-2">
                    <input type="text" name="descripcion" class="form-control" placeholder="📝 Descripción" required>
                </div>
            </div>
            <button type="submit" name="guardar" class="btn btn-primary w-100"><i class="bi bi-plus-circle"></i> Guardar</button>
        </form>
    </div>

    <!-- Buscador -->
    <form method="GET" class="mb-4 d-flex justify-content-center">
        <input type="text" name="buscar" placeholder="🔍 Buscar cliente, marca o teléfono" 
               value="<?= $_GET['buscar'] ?? '' ?>" class="form-control w-50 me-2">
        <button type="submit" class="btn btn-dark"><i class="bi bi-search"></i> Buscar</button>
    </form>

    <!-- Filtros -->
    <div class="mb-4 text-center">
        <a href="?estado=todos" class="btn btn-outline-secondary btn-sm"><i class="bi bi-list-ul"></i> Todos</a>
        <a href="?estado=En reparación" class="btn btn-outline-warning btn-sm"><i class="bi bi-tools"></i> En reparación</a>
        <a href="?estado=Reparado" class="btn btn-outline-primary btn-sm"><i class="bi bi-check-circle"></i> Reparados</a>
        <a href="?estado=Entregada" class="btn btn-outline-success btn-sm"><i class="bi bi-box-seam"></i> Entregadas</a>
    </div>

    <!-- Listado -->
    <div class="row g-4">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php 
                $color = "light";
                if ($row['estado'] == "Entregada") $color = "success";
                elseif ($row['estado'] == "Reparado") $color = "primary";
                elseif ($row['estado'] == "En reparación") $color = "warning";
            ?>
            <div class="col-md-4">
                <div class="card border-<?= $color ?> h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row['cliente'] ?> <small class="text-muted">(📞 <?= $row['telefono'] ?>)</small></h5>
                        <p><b>Marca:</b> <?= $row['marca'] ?></p>
                        <p><b>Descripción:</b> <?= $row['descripcion'] ?></p>
                        <p><b>Estado:</b> <span class="badge bg-<?= $color ?>"><?= $row['estado'] ?></span></p>
                        <p><b>Fecha Ingreso:</b> <?= $row['fecha_envio'] ?></p>
                        <?php if ($row['fecha_entrega']): ?>
                            <p><b>Fecha Entrega:</b> <?= $row['fecha_entrega'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <?php if ($row['estado'] == "En reparación"): ?>
                            <a href="?reparado=<?= $row['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-check-circle"></i> Reparado</a>
                        <?php elseif ($row['estado'] == "Reparado"): ?>
                            <a href="?entregar=<?= $row['id'] ?>" class="btn btn-sm btn-success"><i class="bi bi-box-arrow-in-right"></i> Entregar</a>
                        <?php endif; ?>
                        <a href="?eliminar=<?= $row['id'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Eliminar</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
