<div class="page-title_group">
    <div class="page-title_info">
        <h1 class="page-title">Редактирование сервера #<?= $APP['info']['server']['id'] ?></h1>
    </div>
</div>

<form method="post" action="/server/edit">
    <input type="hidden" name="server-id" value="<?= $APP['info']['server']['id'] ?>">

    <label>
        <p>Название сервера:</p>
        <input type="text" name="server-name" placeholder="Введите название сервера" value="<?= $APP['info']['server']['name'] ?>">
    </label>

    <label>
        <p>Максимальное количество ключей:</p>
        <input type="number" name="server-key-limit" placeholder="" min="-1" step="1" value="<?= $APP['info']['server']['key_limit'] ?>">
    </label>

    <div>
        <button style="display: inline-block; width: auto; margin-right: 10px;">Сохранить</button>
        <a href="/server/list" style="display: inline-block; width: auto;" class="button btn-second">Отмена</a>
    </div>
</form>