<?
include_once 'config.php';
if ($_POST['name'] != '') {
	
	$name = htmlspecialchars(strip_tags($_POST['name']));
	$mail = htmlspecialchars(strip_tags($_POST['email']));
	$text = htmlspecialchars(strip_tags($_POST['text']));

	$q = $pdo->prepare("INSERT INTO _tasks (t_uname, t_email, t_text, t_status) VALUES (?, ?, ?, 0)");
	$q->execute(array($name, $mail, $text));

	header('Location: /');
}
?>