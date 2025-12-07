<div class="page-title_group">
    <div class="page-title_info">
        <h1 class="page-title">Ваши cервера</h1>
        <p class="page-description">Список всех добавленых серверов</p>
    </div>
    <div class="page-title_action">
        <a href="/server/add" class="button" style="margin: 0;">Добавить сервер</a>
    </div>
</div>

<table class="table-app table-app-main" style="margin-top: 20px;">
    <thead>
        <tr>
            <th style="width: 100%;">Сервер</th>
            <th style="min-width: 125px;">Дата создания</th>
            <th>Ключей</th>
            <th>Лимит</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($APP['info']['servers'] as $server): ?>
            <tr>
                <td>
                    <div style="display: flex;">
                        <div style="margin-right: 10px; padding-top: 5px;">
                            <span class="AppTooltip server-status server-status-<?=$server['status']?>" title="<?=$server['status_name']?>"></span>
                        </div>
                        <div>
                            <span><?=$server['name']?></span>
                            <?php 
                                if($server['ip'] != '') { 
                                    echo("<br><span><code style=\"display: inline-block; margin-top: 5px;\">" . $server['ip'] . "</code></span>"); 
                                } 
                            ?>
                        </div>
                    </div>
                </td>
                <td>
                    <span><?=explode(' ', $server['date_add'])[0]?></span><br>
                    <span style="opacity: 0.3"><?=explode(' ', $server['date_add'])[1]?></span>
                </td>
                <td style="text-align:center;">0</td>
                <td style="text-align:center;">
                    <?php 
                        if($server['key_limit'] == -1) {
                            echo('<svg width="19.5" height="9.5" viewBox="0 0 19.5 9.5"><path d="M14,9.417A4.165,4.165,0,0,1,17,8a4,4,0,0,1,0,8c-4.5,0-5.5-8-10-8a4,4,0,0,0,0,8,4.165,4.165,0,0,0,3-1.417" transform="translate(-2.25 -7.25)" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/></svg>');
                        } else {
                            echo($server['key_limit']);
                        } 
                    ?>
                </td>
                <td>
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