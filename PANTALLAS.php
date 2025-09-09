<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "inventario_reparaciones";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$result = $conn->query("SELECT PINES, TAMANO, MODELO, CANT, TOUCH, TIPO FROM pantallas");
if(!$result){
    die("Error en la consulta: " . $conn->error);
}

$inventario = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inventario de Pantallas</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { font-family: 'Poppins', sans-serif; margin:0; background:#f0f2f5; color:#333; }
h2 { text-align:center; margin:100px 0 30px; color:#4a90e2; }
.navbar { position:fixed; top:0; left:0; width:100%; background:#4a90e2; color:white; display:flex; justify-content:space-between; align-items:center; padding:10px 20px; box-shadow:0 4px 10px rgba(0,0,0,0.2); z-index:1000; }
.navbar .logo { font-size:20px; font-weight:bold; }
.navbar ul { list-style:none; margin:0; padding:0; display:flex; }
.navbar ul li { margin:0 10px; }
.navbar ul li a { text-decoration:none; color:white; padding:8px 15px; border-radius:5px; transition:0.3s; }
.navbar ul li a:hover { background: rgba(255,255,255,0.2); }

.acciones { margin:20px auto; text-align:center; }
input[type="text"], input[type="number"] { padding:8px; margin:5px; border:1px solid #ccc; border-radius:8px; width:140px; }
button { padding:8px 12px; margin:3px; border:none; border-radius:8px; cursor:pointer; font-weight:bold; transition:0.3s; display:inline-flex; align-items:center; gap:5px; }
.btn-add { background:#28a745; color:white; }
.btn-add:hover { background:#218838; }
.btn-edit { background:#ffc107; color:white; }
.btn-edit:hover { background:#e0a800; }
.btn-delete { background:#dc3545; color:white; }
.btn-delete:hover { background:#c82333; }
.btn-export { background:#17a2b8; color:white; }
.btn-export:hover { background:#138496; }
.btn-print { background:#6f42c1; color:white; }
.btn-print:hover { background:#5a32a3; }

table { border-collapse: collapse; width:90%; margin:0 auto 50px auto; border-radius:12px; overflow:hidden; box-shadow:0 6px 15px rgba(0,0,0,0.1); background:white; }
th, td { padding:15px 10px; text-align:center; border-bottom:1px solid #eee; }
th { background:#4a90e2; color:white; font-weight:600; cursor:pointer; }
tr:nth-child(even) { background:#f9f9f9; }
tr:hover { background:#e8f0fe; transform: scale(1.01); transition:0.2s; }

@media (max-width:768px){
  table, th, td { font-size:13px; }
  input[type="text"], input[type="number"] { width:100px; }
}
</style>
</head>
<body>

<nav class="navbar">
  <div class="logo"><i class="fa-solid fa-desktop"></i> Sistema</div>
  <ul>
    <li><a href="Reparaciones.php"><i class="fa-solid fa-computer"></i> Reparación</a></li>
    <li><a href="PANTALLAS.php"><i class="fa-solid fa-tv"></i> Pantallas</a></li>
  </ul>
</nav>

<h2>📋 Inventario de Pantallas</h2>

<div class="acciones">
  <input type="text" id="buscador" placeholder="🔍 Buscar..." onkeyup="buscarTabla()">
  <button class="btn-export" onclick="exportarExcel()"><i class="fa-solid fa-file-excel"></i> Exportar</button>
  <button class="btn-print" onclick="window.print()"><i class="fa-solid fa-print"></i> Imprimir</button>
</div>

<div class="acciones">
  <input type="text" id="nuevoPines" placeholder="PINES">
  <input type="text" id="nuevoTamano" placeholder="TAMANO">
  <input type="text" id="nuevoModelo" placeholder="MODELO">
  <input type="number" id="nuevoCant" placeholder="CANT">
  <input type="text" id="nuevoTouch" placeholder="TOUCH">
  <input type="text" id="nuevoTipo" placeholder="TIPO">
  <button class="btn-add" onclick="agregarFila()"><i class="fa-solid fa-plus"></i> Agregar</button>
</div>

<table id="tabla">
  <thead>
    <tr>
      <th onclick="ordenarTabla(0)">PINES ⬍</th>
      <th onclick="ordenarTabla(1)">TAMANO ⬍</th>
      <th onclick="ordenarTabla(2)">MODELO ⬍</th>
      <th onclick="ordenarTabla(3)">CANT ⬍</th>
      <th onclick="ordenarTabla(4)">TOUCH ⬍</th>
      <th onclick="ordenarTabla(5)">TIPO ⬍</th>
      <th>ACCIONES</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($inventario as $fila): ?>
      <tr>
        <td><?= htmlspecialchars($fila["PINES"]) ?></td>
        <td><?= htmlspecialchars($fila["TAMANO"]) ?></td>
        <td><?= htmlspecialchars($fila["MODELO"]) ?></td>
        <td><?= htmlspecialchars($fila["CANT"]) ?></td>
        <td><?= htmlspecialchars($fila["TOUCH"]) ?></td>
        <td><?= htmlspecialchars($fila["TIPO"]) ?></td>
        <td>
          <button class="btn-edit" onclick="editarFila(this)"><i class="fa-solid fa-pen-to-square"></i> Editar</button>
          <button class="btn-delete" onclick="borrarFila(this)"><i class="fa-solid fa-trash"></i> Borrar</button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
function buscarTabla() {
  let input = document.getElementById("buscador").value.toLowerCase();
  let filas = document.querySelectorAll("#tabla tbody tr");
  filas.forEach(fila => fila.style.display = fila.innerText.toLowerCase().includes(input) ? "" : "none");
}

function agregarFila() {
  let pines = document.getElementById("nuevoPines").value.trim();
  let tamano = document.getElementById("nuevoTamano").value.trim();
  let modelo = document.getElementById("nuevoModelo").value.trim();
  let cant = document.getElementById("nuevoCant").value.trim();
  let touch = document.getElementById("nuevoTouch").value.trim();
  let tipo = document.getElementById("nuevoTipo").value.trim();
  if(!pines || !tamano || !modelo || !cant){ alert("⚠️ Completa los campos obligatorios."); return; }

  let tabla = document.querySelector("#tabla tbody");
  let fila = `<tr>
    <td>${pines}</td>
    <td>${tamano}</td>
    <td>${modelo}</td>
    <td>${cant}</td>
    <td>${touch}</td>
    <td>${tipo}</td>
    <td>
      <button class="btn-edit" onclick="editarFila(this)"><i class="fa-solid fa-pen-to-square"></i> Editar</button>
      <button class="btn-delete" onclick="borrarFila(this)"><i class="fa-solid fa-trash"></i> Borrar</button>
    </td>
  </tr>`;
  tabla.insertAdjacentHTML("beforeend", fila);
  document.querySelectorAll("#nuevoPines, #nuevoTamano, #nuevoModelo, #nuevoCant, #nuevoTouch, #nuevoTipo").forEach(input => input.value="");
}

function borrarFila(boton) {
  if(confirm("¿Seguro que deseas eliminar esta fila?")){
    boton.closest("tr").remove();
  }
}

function editarFila(boton) {
  let fila = boton.closest("tr");
  let celdas = fila.querySelectorAll("td");
  if(boton.innerText.includes("Editar")){
    for(let i=0;i<celdas.length-1;i++){
      let valor=celdas[i].innerText;
      celdas[i].innerHTML=`<input type='text' value='${valor}'>`;
    }
    boton.innerHTML='<i class="fa-solid fa-floppy-disk"></i> Guardar';
  } else {
    for(let i=0;i<celdas.length-1;i++){
      celdas[i].innerText=celdas[i].querySelector("input").value;
    }
    boton.innerHTML='<i class="fa-solid fa-pen-to-square"></i> Editar';
  }
}

function exportarExcel() {
  let tabla=document.getElementById("tabla");
  let htmlTabla=tabla.outerHTML.replace(/ /g,'%20');
  let enlace=document.createElement("a");
  enlace.href='data:application/vnd.ms-excel,'+htmlTabla;
  enlace.download='inventario_pantallas.xls';
  enlace.click();
}

function ordenarTabla(col){
  let tabla=document.getElementById("tabla");
  let filas=Array.from(tabla.tBodies[0].rows);
  let asc=tabla.getAttribute("data-asc")==="true"?false:true;
  filas.sort((a,b)=>{
    let x=a.cells[col].innerText.toLowerCase();
    let y=b.cells[col].innerText.toLowerCase();
    if(!isNaN(x) && !isNaN(y)) return asc ? x-y : y-x;
    return asc ? x.localeCompare(y) : y.localeCompare(x);
  });
  filas.forEach(f=>tabla.tBodies[0].appendChild(f));
  tabla.setAttribute("data-asc",asc);
}
</script>
</body>
</html>
