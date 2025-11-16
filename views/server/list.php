<div class="page-title_group">
    <div class="page-title_info">
        <h1 class="page-title">Ваши cервера</h1>
        <p class="page-description">Список всех добавленых серверов</p>
    </div>
    <div class="page-title_action">
        <a href="/server/add" class="button" style="margin: 0;">Добавить сервер</a>
    </div>
</div>

<table class="table-app" style="margin-top: 20px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Добавлен</th>
            <th style="width: 100%;">Сервер</th>
            <th>Статус</th>
            <th>Ключей</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($APP['info']['servers'] as $server): ?>
            <tr>
                <td><?=$server['id']?></td>
                <td><?=$server['date_add']?></td>
                <td><?=$server['name']?><?php if($server['ip'] != '') { echo("<br><code style=\"display: inline-block; margin-top: 5px;\">" . $server['ip'] . "</code>"); } ?></td>
                <td><?=$server['status_name']?></td>
                <td>0/<?=$server['key_limit']?></td>
                <td style="padding: 0;">
                    <div class="menu-action_wrapper close">
                        <svg class="menu-action_btn" viewBox="0 0 16 16"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><circle cx="8" cy="2.5" r=".75"/><circle cx="8" cy="8" r=".75"/><circle cx="8" cy="13.5" r=".75"/></g></svg>
                        <div class="menu-action_block">
                            <a href="/server/edit/<?=$server['id']?>">Редактировать</a>
                            <a href="/server/del/<?=$server['id']?>">Удалить</a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>