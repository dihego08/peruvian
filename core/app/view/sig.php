<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$accion = $_GET['accion'];
include("env.php");
function executar_sh($tipo)
{
    switch ($tipo) {
        case 'backup':
            contar_archivos();
            shell_exec('nohup sh backup.sh > /dev/null 2>&1 &');
            break;
        case 'restore':
            shell_exec('nohup sh restore.sh > /dev/null 2>&1 &');
            break;
    }
}
function contar_archivos()
{
    include("env.php");
    $folderPath = '../../../BIBLIOTECA'; // Especifica la ruta de la carpeta
    $files = array_diff(scandir($folderPath), array('.', '..')); // Escanea el directorio y elimina "." y ".."

    $fileCount = 0;

    foreach ($files as $file) {
        if (is_file($folderPath . '/' . $file)) {
            $fileCount++;
        }
    }

    $query = $mbd->prepare("UPDATE aux SET id = :fileCount WHERE i = 11;");
    $query->bindParam(":fileCount", $fileCount);
    $query->execute();
}
if ($accion == 'crear_carpeta') {
    if (isset($_POST['id_padre'])) {
        $query = $mbd->prepare("SELECT * FROM biblioteca WHERE id = :id_padre");
        $query->bindParam(":id_padre", $_POST['id_padre']);
        $query->execute();
        $padre = $query->fetch(PDO::FETCH_ASSOC);

        $q = $mbd->prepare("INSERT INTO biblioteca(nombre_carpeta, id_padre) values(:nombre_carpeta, :id_padre)");
        $q->bindParam(":id_padre", $_POST['id_padre']);
        $q->bindParam(":nombre_carpeta", $_POST['nombre_carpeta']);
        $q->execute();

        echo json_encode(array("Result" => "OK", "Message" => "OK"));
    } else {

        $q = $mbd->prepare("INSERT INTO biblioteca(nombre_carpeta, id_padre) values(:nombre_carpeta, :id_padre)");
        $q->bindParam(":id_padre", $_POST['id_padre']);
        $q->bindParam(":nombre_carpeta", $_POST['nombre_carpeta']);
        $q->execute();

        echo json_encode(array("Result" => "OK", "Message" => "OK"));
    }
} elseif ($accion == "verificar_archivos") {
    $folderPath = '../../../BIBLIOTECA'; // Especifica la ruta de la carpeta
    $files = array_diff(scandir($folderPath), array('.', '..')); // Escanea el directorio y elimina "." y ".."

    $fileCount = 0;

    foreach ($files as $file) {
        if (is_file($folderPath . '/' . $file)) {
            $fileCount++;
        }
    }
    $query = $mbd->prepare("SELECT * FROM aux WHERE i = 11");
    $query->execute();
    $cantidad = $query->fetch(PDO::FETCH_ASSOC);
    echo $fileCount < $cantidad['id'] ;
    if ($fileCount < $cantidad['id']) {
        //executar_sh("restore");

        $archivos = scandir($folderPath);

        foreach ($archivos as $archivo) {
            // Verifica si el nombre del archivo contiene '#U00f'
            if (strpos($archivo, '#U00') !== false) {
                $rutaArchivo = $directorio . '/' . $archivo;
                // Verifica si es un archivo y lo elimina
                if (is_file($rutaArchivo)) {
                    unlink($rutaArchivo);
                    echo "Archivo eliminado: $archivo\n";
                }
            }
        }
        echo "Archivos restaurados";
    }else{
        echo "Nada que restaurar";
    }
} elseif ($accion == 'lista_carpetas') {
    $query = $mbd->prepare("SELECT * FROM biblioteca WHERE (id_padre is null or id_padre = 0 or id_padre = '') and mostrar = 0;");
    $query->execute();
    $values = array();
    while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
        $values[] = $res;
    }
    echo json_encode($values);
} elseif ($accion == 'data_carpeta') {
    echo json_encode(array("nombre_carpeta" => nombre_carpeta($_POST['id_carpeta'], $mbd)));
} elseif ($accion == 'lista_contenido') {
    $query = $mbd->prepare("SELECT * FROM contenido_biblioteca WHERE id_carpeta = " . $_POST['id_carpeta']);
    $query->execute();

    $query_2 = $mbd->prepare("SELECT * FROM biblioteca WHERE id_padre = " . $_POST['id_carpeta']);
    $query_2->execute();

    $query_3 = $mbd->prepare("SELECT * FROM biblioteca where id = " . $_POST['id_carpeta']);
    $query_3->execute();
    $sql_3 = $query_3->fetch(PDO::FETCH_ASSOC);

    $padre = "";
    if ($sql_3['id_padre'] == "" || is_null($sql_3['id_padre']) || empty($sql_3['id_padre'])) {
        $padre = "NO";
    } else {
        $query_4 = $mbd->prepare("SELECT * FROM biblioteca where id = " . $_POST['id_carpeta']);
        $query_4->execute();
        $padre_ = $query_4->fetch(PDO::FETCH_ASSOC);
        $padre = $padre_['nombre_carpeta'];
    }

    $result = array();

    while ($carpetas = $query_2->fetch(PDO::FETCH_ASSOC)) {
        $result[] = array(
            "id" => $carpetas['id'],
            "nombre_carpeta" => $carpetas['nombre_carpeta'],
            "id_padre" => $carpetas['id_padre'],
            "padre" => $padre,
            "type" => "C"
        );
    }

    while ($contenido = $query->fetch(PDO::FETCH_ASSOC)) {
        $result[] = array(
            "id" => $contenido['id'],
            "archivo" => $contenido['archivo'],
            "id_carpeta" => $contenido['id_carpeta'],
            "padre" => $padre,
            "type" => "A"
        );
    }

    echo json_encode($result);
} elseif ($accion == "lista_contenido_por_nombre") {
    $query = $mbd->prepare("SELECT * FROM contenido_biblioteca WHERE archivo LIKE '%" . $_POST['texto'] . "%' ORDER BY id DESC");
    $query->execute();

    while ($contenido = $query->fetch(PDO::FETCH_ASSOC)) {
        $result[] = array(
            "id" => $contenido['id'],
            "archivo" => $contenido['archivo'],
            "id_carpeta" => $contenido['id_carpeta'],
            "fecha" => $contenido['fecha_creacion'],
            "ruta" => nombre_carpeta($contenido['id_carpeta'], $mbd),
            "type" => "A"
        );
    }

    echo json_encode($result);
} elseif ($accion == 'guardar_material_permanente') {
    $query_la_carpeta = $mbd->prepare("SELECT * FROM biblioteca where id = " . $_POST['id_carpeta']);
    $query_la_carpeta->execute();

    $la_carpeta = $query_la_carpeta->fetch(PDO::FETCH_ASSOC);

    if ($la_carpeta['id_padre'] == "" || is_null($la_carpeta['id_padre'])) {
        $fileName = quitarTildes($_FILES["file1"]["name"]);
        $fileTmpLoc = $_FILES["file1"]["tmp_name"];
        $fileType = $_FILES["file1"]["type"];
        $fileSize = $_FILES["file1"]["size"];
        $fileErrorMsg = $_FILES["file1"]["error"];

        if (!$fileTmpLoc) {
        }

        if (move_uploaded_file($fileTmpLoc, $_SERVER['DOCUMENT_ROOT'] . "/BIBLIOTECA/" . $fileName)) {
            $_POST['archivo'] = $fileName;
            $query_insert = $mbd->prepare("INSERT INTO contenido_biblioteca(archivo, id_carpeta) VALUES (:archivo, :id_carpeta);");
            $query_insert->bindParam(":archivo", $_POST['archivo']);
            $query_insert->bindParam(":id_carpeta", $_POST['id_carpeta']);
            $query_insert->execute();
            //executar_sh("backup");
            echo json_encode(array("Result" => "OK", "Message" => "OK"));
        } else {
            echo json_encode(array("Result" => "ERROR"));
        }
    } else {
        $padre_q = $mbd->prepare("SELECT * FROM biblioteca where id = " . $la_carpeta['id_padre']);
        $padre_q->execute();
        $padre = $padre_q->fetch(PDO::FETCH_ASSOC);

        $fileName = quitarTildes($_FILES["file1"]["name"]);
        $fileTmpLoc = $_FILES["file1"]["tmp_name"];
        $fileType = $_FILES["file1"]["type"];
        $fileSize = $_FILES["file1"]["size"];
        $fileErrorMsg = $_FILES["file1"]["error"];

        if (!$fileTmpLoc) {
        }
        if (move_uploaded_file($fileTmpLoc, $_SERVER['DOCUMENT_ROOT'] . "/BIBLIOTECA/" . $fileName)) {
            $_POST['archivo'] = $fileName;
            $query_insert = $mbd->prepare("INSERT INTO contenido_biblioteca(archivo, id_carpeta) VALUES (:archivo, :id_carpeta);");
            $query_insert->bindParam(":archivo", $_POST['archivo']);
            $query_insert->bindParam(":id_carpeta", $_POST['id_carpeta']);
            $query_insert->execute();
            //executar_sh("backup");
            echo json_encode(array("Result" => "OK", "Message" => "OK"));
        } else {
            echo json_encode(array("Result" => "ERROR"));
        }
    }
} elseif ($accion == 'accesos_rapidos') {
    $query = $mbd->prepare("SELECT * FROM biblioteca WHERE (id_padre is null or id_padre = 0 or id_padre = '') and mostrar = 1;");
    $query->execute();
    $values = array();
    while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
        $values[] = $res;
    }
    echo json_encode($values);
} elseif ($accion == 'eliminar') {
    if ($_POST['tipo'] == "A") {
        $query = $mbd->prepare("SELECT * FROM contenido_biblioteca where id = " . $_POST['id']);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);
        if (is_null($data) || empty($data)) {
            echo json_encode(array("Result" => "ERROR", "Message" => "El archivo no existe."));
        } else {
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/BIBLIOTECA/" . $data['archivo'])) {
                unlink($_SERVER['DOCUMENT_ROOT'] . "/BIBLIOTECA/" . $data['archivo']);
                //executar_sh("backup");
                $quer = $mbd->prepare("DELETE FROM contenido_biblioteca where id = " . $_POST['id']);
                $quer->execute();
                echo json_encode(array("Result" => "OK", "Message" => "El archivo se ha eliminado correctamente."));
            } else {
                echo json_encode(array("Result" => "ERROR", "Message" => "El archivo no se encuentra en la lista de carpetas."));
            }
        }
    } else {
        $quer = $mbd->prepare("DELETE FROM biblioteca where id = " . $_POST['id']);
        $quer->execute();
        echo json_encode(array("Result" => "OK", "Message" => "La carpeta se ha eliminado correctamente."));
    }
} elseif ($accion == 'editar') {
    if ($_POST['tipo'] == "C") {
        $qu = $mbd->prepare("UPDATE biblioteca set nombre_carpeta = :nombre_carpeta WHERE id = :id");
        $qu->bindParam(":nombre_carpeta", $_POST['nuevo_nombre']);
        $qu->bindParam(":id", $_POST['id']);
        $qu->execute();
    } else {
        $query = $mbd->prepare("SELECT * FROM contenido_biblioteca where id = " . $_POST['id']);
        $query->execute();
        $data = $data = $query->fetch(PDO::FETCH_ASSOC);

        rename($_SERVER['DOCUMENT_ROOT'] . "/BIBLIOTECA" . "/" . $data['archivo'], $_SERVER['DOCUMENT_ROOT'] . "/BIBLIOTECA" . "/" . $_POST['nuevo_nombre']);

        //executar_sh("backup");

        $qu = $mbd->prepare("UPDATE contenido_biblioteca set archivo = :archivo WHERE id = :id");
        $qu->bindParam(":archivo", $_POST['nuevo_nombre']);
        $qu->bindParam(":id", $_POST['id']);
        $qu->execute();
    }
    echo json_encode(array("Result" => "OK", "Message" => "OK"));
}elseif($accion =="ejecutar_mover"){
    $query = $mbd->prepare("UPDATE contenido_biblioteca SET id_carpeta = :id_carpeta WHERE id = :id;");
    $query->bindParam(":id", $_POST['id']);
    $query->bindParam(":id_carpeta", $_POST['id_padre']);
    $query->execute();

    $count = $query->rowCount();
    if($count>0){
        echo json_encode(array("Result" => "OK", "Message" => "Archivo movido correctamente."));
    }else{
        echo json_encode(array("Result" => "ERROR", "Message" => "Algo ha salido mal."));
    }
}
function removeFolder($folderName)
{
    if (is_dir($folderName)) {
        $folderHandle = opendir($folderName);
    }

    while ($file = readdir($folderHandle)) {

        if ($file != "." && $file != "..") {

            if (!is_dir($folderName . "/" . $file)) {
                unlink($folderName . "/" . $file);
            } else {
                removeFolder($folderName . '/' . $file);
            }
        }
    }

    closedir($folderHandle);

    rmdir($folderName);
}
function quitarTildes($cadena)
{
    $acentos = array(
        'á' => 'a',
        'é' => 'e',
        'í' => 'i',
        'ó' => 'o',
        'ú' => 'u',
        'Á' => 'A',
        'É' => 'E',
        'Í' => 'I',
        'Ó' => 'O',
        'Ú' => 'U',
        'ñ' => 'n',
        'Ñ' => 'N'
    );

    return strtr($cadena, $acentos);
}
function nombre_carpeta($id_carpeta, $mbd)
{
    if (is_null($id_carpeta) || empty($id_carpeta)) {
        return '';
    }
    $aux = array();
    $nombre_carpeta = "";
    $id_padre = 1000;
    while ($id_padre > 0) {
        $query = $mbd->prepare("SELECT * FROM biblioteca WHERE id = :id_carpeta");
        if ($id_padre == 1000) {
            $query->bindParam(":id_carpeta", $id_carpeta);
        } else {
            $query->bindParam(":id_carpeta", $id_padre);
        }

        $query->execute();
        $car = $query->fetch(PDO::FETCH_ASSOC);
        if (isset($car['nombre_carpeta'])) {
            $aux[] = $car['nombre_carpeta'];
            $id_padre = $car['id_padre'];
        } else {
            $id_padre = 0;
        }
    }
    $aux = array_reverse($aux);
    $nombre_carpeta .= '/' . implode('/', $aux);
    return $nombre_carpeta;
}
