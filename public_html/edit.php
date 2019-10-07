<?
include_once 'config.php';
// подготовим запрос в соответсвии с пришедшими данными
if ($_POST['edit'] == 'text') {
	$set = 't_text = ?, t_admin = 1';
	$val = $_POST['value'];
} else {
	$set = 't_status = ?';
	$val = $_POST['value'] ? 1 : 0;
}
// выполним запрос
$q = $pdo->prepare("UPDATE _tasks SET ".$set." WHERE id = ?");
$q->execute(array($val,$_POST['task_id']));
?>