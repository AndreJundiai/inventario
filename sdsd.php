<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkbox Texto</title>
</head>
<body>

<form action="salvar_texto.php" method="post">
    <label for="mostrarTexto">Mostrar Texto</label>
    <input type="checkbox" id="mostrarTexto" name="mostrarTexto" onchange="mostrarCampoTexto()">
    <br><br>
    <div id="campoTexto" style="display:none;">
        <label for="textoParaMostrar">Texto:</label><br>
        <textarea id="textoParaMostrar" name="textoParaMostrar" rows="4" cols="50"></textarea>
    </div>
    <br><br>
    <input type="submit" value="Salvar">
</form>

<script>
function mostrarCampoTexto() {
    var checkBox = document.getElementById("mostrarTexto");
    var campoTexto = document.getElementById("campoTexto");
    if (checkBox.checked == true){
        campoTexto.style.display = "block";
    } else {
       campoTexto.style.display = "none";
    }
}
</script>

</body>
</html>
