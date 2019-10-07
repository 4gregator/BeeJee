<?
include_once 'config.php';
include 'head.php';

$admin = $_COOKIE['iddqd'] == 'admin' ? true : false;
?>
<div class="container">
    <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container d-flex justify-content-between">
        <? if (isset($_COOKIE['iddqd'])) { ?>
            <!-- ВЫХОД ИЗ СЕССИИ -->
            <span  class="col offset-10 text-light"><?=$_COOKIE['iddqd']?></span>
            <button id="logout" class="col btn btn-primary">Logout</button>
        <? } else { ?>
            <!-- АВТОРИЗАЦИИ -->
            <a href="/auth.php" class="col offset-11 btn btn-primary">Login</a>
        <? } ?>
        </div>    </div>
    <div class="container-fluid">
        <h1>Список задач</h1>
    </div>
    <ul class="list-group border border-primary mb-2">
        <li class="head list-group-item d-flex">
            <span data-sort="<?=isset($_GET['name']) ? $_GET['name'] : ''?>" data-type="name" class="sort col-2">Имя пользователя</span>
            <span data-sort="<?=isset($_GET['email']) ? $_GET['email'] : ''?>" data-type="email" class="sort col-2">Email</span>
            <span class="col-6">Задача</span>
            <span data-sort="<?=isset($_GET['status']) ? $_GET['status'] : ''?>" data-type="status" class="sort col-2 text-center">Статус</span>
        </li>
        <?
        // ВЫВОД СПИСКА ЗАДАЧ С СОРТИРОВКОЙ
        // проверим, нужна ли сортировка и подготовим запрос
        $query = 'SELECT * FROM _tasks ';

        // пропишем вручную запрос на сортировку (чтоб избежать sql-иньекций)
        // на скорую руку лучше не придумалось :)
        if (isset($_GET['name'])) {
            $query .= 'ORDER BY t_uname';
            switch ($_GET['name']) {
                case 'asc': break;
                case 'desc': $query .= ' DESC';
            }
        } else if (isset($_GET['email'])) {
            $query .= 'ORDER BY t_email';
            switch ($_GET['email']) {
                case 'asc': break;
                case 'desc': $query .= ' DESC';
            }
        } else if (isset($_GET['status'])) {
            $query .= 'ORDER BY t_status';
            switch ($_GET['status']) {
                case 'desc': break;
                case 'asc': $query .= ' DESC';
            }
        }

        // узнаем количество строк в таблице для пагинации
        $rc = $pdo->query("SELECT id FROM _tasks")->rowCount();

        if (isset($_GET['page'])) {
            $page = intval($_GET['page']);
            $page = ($page - 1) * 3;
            if ($rc > $page) $query .= " LIMIT ".$page.", 3";
        } else $query .= " LIMIT 3";

        $q = $pdo->query($query);
        while ($res = $q->fetch()) {
        ?>
            <li class="list-group-item d-flex" id="<?=$res['id']?>">
                <span class="col-2"><?=$res['t_uname']?></span>
                <span class="col-2"><?=$res['t_email']?></span>
                <div class="col-6 <?=$admin ? 'editable' : ''?>" <?=$admin ? 'contenteditable' : ''?> ><?=$res['t_text']?></div>
                <div class="col-2 text-center">
                    <input type="checkbox" <?=$res['t_status'] ? 'checked' : ''?> <?=$admin ? '' : 'disabled'?> />
                <? if ($res['t_admin']) { ?>
                        <div class="small">отредактировано администратором</div>
                <? } ?>
                </div>
            </li>
        <?
        }
        ?>
    </ul>
    <!-- ДОБАВЛЕНИЕ ЗАДАЧИ -->
    <button id="new_task" type="button" class="btn btn-primary">Добавить</button>
    <form action="add.php" name="new_task" method="post" class="row col-5">
        <input type="text" name="name" class="form-control mb-1" placeholder="Имя пользователя" required>
        <input type="email" name="email" class="form-control mb-1" placeholder="Email" required>
        <textarea name="text" class="form-control mb-1" placeholder="Текст задачи" required></textarea>
        <input type="submit" value="Сохранить" class="btn btn-primary">
    </form>
    <!-- ПАГИНАЦИЯ -->
    <div class="pagination d-flex justify-content-between align-items-center mt-5">
    <?
    $get = (isset($_GET['page'])) ? strstr($_SERVER['REQUEST_URI'],'page',true) : $_SERVER['REQUEST_URI'];
    if (isset($_GET['page'])) {
        $url = $_GET['page'] == 2 ? substr($get, 0, strlen($get) - 1) : $get.'page='.($_GET['page'] - 1);
    ?>
        <a href="<?=$url?>" class="btn btn-primary"><i class="fas fa-long-arrow-alt-left"></i></a>
    <? } ?>
        <span class="col-2 text-center <?=isset($_GET['page']) ? '' : 'offset-5'?>">стр. <?=isset($_GET['page']) ? $_GET['page'] : '1'?></span>
    <?
    if ($rc > $page + 3) {
        if (isset($_GET['page'])) $page = $_GET['page'];
        else {
            $page = 1;
            $get = strlen($get) > 1 ? $get.'&' : $get.'?';
        }
        $url = $get.'page='.($page + 1);
    ?>
        <a href="<?=$url?>" class="btn btn-primary"><i class="fas fa-long-arrow-alt-right"></i></a>
    <? } ?>
    </div>
</div>
<? include 'bottom.php' ?>