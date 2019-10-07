$(function(r) {
	$('#new_task').on('click', function(e) {
		$(this).hide();
		$('form[name=new_task]').slideDown(400);
	});

	/* Выход из сессии пользователя */
	$('#logout').on('click', function(e) {
		document.cookie = "iddqd=;expires=Thu, 01 Jan 1970 00:00:01 GMT";
	});

	/* Редактирование задач админом */
	$('.editable').on('blur', edit_task.bind(this,'text'));

	/* Изменение статуса задачи */
	$('[type=checkbox]').on('change', edit_task.bind(this,'checkbox'));

	/* Сортировка */
	$('li.head>.sort').on('click', function(e) {
		let get = window.location.search,
			page = get.indexOf('page') == -1 ? '' : '&' + get.substr(get.indexOf('page'));
		switch ($(this).attr('data-sort')) {
			case '': 
				window.location.href = "/?" + $(this).attr('data-type') + "=asc" + page;
				break;
			case 'asc':
				window.location.href = "/?" + $(this).attr('data-type') + "=desc" + page;
				break;
			case 'desc':
				window.location.href = "/" + (page.substr(1) != '' ? '?' + page.substr(1) : '');
				break;
		}
	});
});

function edit_task(type,e) {
	let id = $(e.target).parents('li').attr('id'),
		val = type == 'text' ? $(e.target).html() : $(e.target).prop('checked');
	
	if (document.cookie == iddqd=admin) {
		$.post('edit.php',{
			edit:type,
			task_id:id,
			value:val
		});
	} else return false;
}