<div class="page-title_group">
    <div class="page-title_info">
        <h1 class="page-title">Добавление ключа</h1>
    </div>
</div>

<form method="post" action="/server/<?=$APP['info']['server']['id']?>/key/add">
    <label>
        <p>Имя:</p>
        <input type="text" name="key-name" placeholder="Введите имя...">
    </label>

    <label>
        <p>Пароль:</p>
        <input type="text" name="key-password" placeholder="Введите пароль...">
    </label>

    <label>
        <p>Порт:</p>
        <input type="text" name="key-port" placeholder="Введите порт...">
    </label>

    <label>
        <p>Метод:</p>
        <select name="key-method">
            <?php foreach ($APP['info']['encryptMethods'] as $method): ?>
                <option value="<?=$method?>"><?=$method?><?=($method == 'chacha20-ietf-poly1305' ? ' (рекомендуется)' : '')?></option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>
        <p>Дата окончания:</p>
        <input type="date" name="key-date-end">
    </label>
    
    <div>
        <button style="display: inline-block; width: auto; margin-right: 10px;">Добавить ключ</button>
        <a href="/server/<?=$APP['info']['server']['id']?>/key/list" style="display: inline-block; width: auto;" class="button btn-second">Отмена</a>
    </div>
</form>