<?
include_once 'config.php';
$wrong_user = false;
if ($_POST['name'] != '') {
	$q = $pdo->prepare("SELECT * FROM _users WHERE u_name = ? AND u_pass = ? LIMIT 1");
	$q->execute(array($_POST['name'], $_POST['pass']));
	$res = $q->fetch();

	if ($res['id'] > 0) {
		setcookie('iddqd',$res['u_name'],time()+60*60*24*1,'/');
		header('Location: /');
		exit();
	} else $wrong_user = true;
}
include 'head.php';
?>
<div class="container mt-5">
	<fieldset class="col-3">
		<legend>Авторизация</legend>
		<form method="post" name="auth">
			<input type="text" name="name" class="form-control mb-1" placeholder="Имя пользователя" required>
			<input type="password" name="pass" class="form-control mb-1" placeholder="Пароль" required>
			<input type="submit" value="Войти" class="btn btn-primary">
		</form>
	</fieldset>
<? if ($wrong_user) { ?>
	<div class="container-fluid mt-3">Не верно указан юзер или пароль, попробуйте ещё раз!</div>
<? } ?>
</div>
<?
include 'bottom.php';
?>