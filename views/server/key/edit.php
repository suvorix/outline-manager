<div class="page-title_group">
    <div class="page-title_info">
        <h1 class="page-title">Редактирование ключа</h1>
    </div>
</div>

<form method="post" action="/server/<?=$APP['info']['server']['id']?>/key/edit/<?=$APP['info']['key']['id']?>">
    <label>
        <p>Имя:</p>
        <input type="text" name="key-name" placeholder="Введите имя..." value="<?=$APP['info']['key']['key_name']?>">
    </label>

    <label>
        <p>Дата окончания:</p>
        <input type="date" name="key-date-end" value="<?=$APP['info']['key']['date_end']?>">
    </label>
    
    <div>
        <button style="display: inline-block; width: auto; margin-right: 10px;">Изменить ключ</button>
        <a href="/server/<?=$APP['info']['server']['id']?>/key/list" style="display: inline-block; width: auto;" class="button btn-second">Отмена</a>
    </div>
</form>