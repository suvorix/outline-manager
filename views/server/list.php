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
            <th style="width: 100%;"><span class="AppTooltip" title="Информация о сервере">Сервер</span></th>
            <th style="min-width: 125px;"><span class="AppTooltip" title="Дата добавления сервера">Дата создания</span></th>
            <th><span class="AppTooltip" title="Количество активных ключей на сервере">Ключей</span></th>
            <th><span class="AppTooltip" title="Максимальное количество ключей на сервере">Лимит</span></th>
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
                    <span style="color: var(--dop-text-color)"><?=explode(' ', $server['date_add'])[1]?></span>
                </td>
                <td style="text-align:center;">0</td>
                <td style="text-align:center;">
                    <?php 
                        if($server['key_limit'] == -1) {
                            echo('<svg class="AppTooltip" title="Безлимитный" width="19.5" height="9.5" viewBox="0 0 19.5 9.5"><path d="M14,9.417A4.165,4.165,0,0,1,17,8a4,4,0,0,1,0,8c-4.5,0-5.5-8-10-8a4,4,0,0,0,0,8,4.165,4.165,0,0,0,3-1.417" transform="translate(-2.25 -7.25)" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/></svg>');
                        } else {
                            echo('<span class="AppTooltip" title="Максимум ' . $server['key_limit'] . ' активных ключей">' . $server['key_limit'] . '</span>');
                        } 
                    ?>
                </td>
                <td>
                    <div class="menu-action_wrapper close">
                        <svg class="menu-action_btn" viewBox="0 0 16 16"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><circle cx="8" cy="2.5" r=".75"/><circle cx="8" cy="8" r=".75"/><circle cx="8" cy="13.5" r=".75"/></g></svg>
                        <div class="menu-action_block">
                            <a href="/server/edit/<?=$server['id']?>">
                                <svg viewBox="0 0 19.99 20.007"><path fill="currentColor" d="M2.3-5.2a3.37,3.37,0,0,0-.9,1.523l-1.367,5a.953.953,0,0,0,.234.9.911.911,0,0,0,.938.234L6.172,1.094A3.37,3.37,0,0,0,7.7.2L19.18-11.289A2.873,2.873,0,0,0,20-13.281a2.873,2.873,0,0,0-.82-1.992L17.773-16.68a2.873,2.873,0,0,0-1.992-.82,2.873,2.873,0,0,0-1.992.82Zm13.477-10.43a1.019,1.019,0,0,1,.664.273l1.406,1.406a.857.857,0,0,1,.273.664.942.942,0,0,1-.273.664l-2.07,2.07-2.734-2.734,2.07-2.07A1.019,1.019,0,0,1,15.781-15.625ZM4.3-4.531l7.422-7.422,2.734,2.734L7.031-1.8ZM3.164-3.008,5.508-.664l-3.2.9Z" transform="translate(-0.01 17.5)"/></svg>
                                <span>Редактировать</span>
                            </a>
                            <a href="/server/del/<?=$server['id']?>" class="action-red">
                                <svg viewBox="0 0 17.5 20.625"><path fill="currentColor" d="M6.484-18.125a1.257,1.257,0,0,0-1.172.82l-.625,1.68H.938A.951.951,0,0,0,0-14.687a.951.951,0,0,0,.938.938H16.563a.951.951,0,0,0,.938-.937.951.951,0,0,0-.937-.937h-3.75l-.625-1.68a1.257,1.257,0,0,0-1.172-.82ZM1.25-11.875V0a2.518,2.518,0,0,0,2.5,2.5h10A2.518,2.518,0,0,0,16.25,0V-11.875H14.375V0a.617.617,0,0,1-.625.625h-10A.617.617,0,0,1,3.125,0V-11.875ZM7.5-9.062A.951.951,0,0,0,6.563-10a.951.951,0,0,0-.937.938v6.875a.951.951,0,0,0,.938.938A.951.951,0,0,0,7.5-2.187Zm4.375,0A.951.951,0,0,0,10.938-10,.951.951,0,0,0,10-9.062v6.875a.951.951,0,0,0,.938.938.951.951,0,0,0,.938-.937Z" transform="translate(0 18.125)"/></svg>
                                <span>Удалить</span>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>