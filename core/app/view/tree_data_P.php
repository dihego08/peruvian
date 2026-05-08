<?php
include("env.php");
$query1 = $mbd->prepare("SELECT COUNT(*) as total FROM menus");
$query1->execute();
$total = 0;
while ($r = $query1->fetch(PDO::FETCH_ASSOC)) {
    $total = $r['total'];
}
$query = $mbd->prepare("SELECT * FROM menus where id");
$query->execute();
$array = array();
$idUsuario = $_GET['idUsuario'];
$tr = false;
while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
    $r = ($res['parent_id'] == '0') ? '#' : $res['parent_id'];

    $q = $mbd->prepare("SELECT COUNT(*) as cant FROM menus_entidades, user WHERE menus_entidades.idUsuario = user.id and user.id = :idUsuario and menus_entidades.idMenu = :id AND menus_entidades.idMenu NOT IN (1, 12, 15)");
    $q->bindParam(':idUsuario', $idUsuario);
    $q->bindParam(':id', $res['id']);
    $q->execute();
    $val = 0;
    while ($t = $q->fetch(PDO::FETCH_ASSOC)) {
        $val = $t['cant'];
    }
    if ($val > 0) {
        $tr = true;
    } else {
        $tr = false;
    }
    if ($r == "#" && (empty($res['link']) || is_null($res['link']))) {
        $tr = false;
    }
    $values[] = array(
        'id' => $res['id'],
        'parent' => $r,
        'text' => $res['text'],
        'icon' => $res['icon'],
        'li_attr' => array('value' => $res['link']),
        'state' => array('checked' => $tr, 'opened' => true)
    );
}
$JSON = json_encode($values);
echo $JSON;
