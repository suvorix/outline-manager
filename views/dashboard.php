<div class="page-title_group">
    <div class="page-title_info">
        <h1 class="page-title">Панель управления</h1>
        <p class="page-description">Общая информация и статистика</p>
    </div>
    <div class="page-title_action">
        <div style="display:inline-block">
            <a href="/server/list" class="button button-with-icon" style="margin: 0;">
                <svg viewBox="0 0 20 20"><path fill="currentColor" d="M2.857-14.107a.705.705,0,0,0-.714.714v2.857a.705.705,0,0,0,.714.714H17.143a.705.705,0,0,0,.714-.714v-2.857a.705.705,0,0,0-.714-.714ZM0-13.393A2.877,2.877,0,0,1,2.857-16.25H17.143A2.877,2.877,0,0,1,20-13.393v2.857a2.877,2.877,0,0,1-2.857,2.857H2.857A2.877,2.877,0,0,1,0-10.536Zm11.786.357a1.086,1.086,0,0,1,1.071,1.071,1.086,1.086,0,0,1-1.071,1.071,1.086,1.086,0,0,1-1.071-1.071A1.086,1.086,0,0,1,11.786-13.036Zm2.5,1.071a1.086,1.086,0,0,1,1.071-1.071,1.086,1.086,0,0,1,1.071,1.071,1.086,1.086,0,0,1-1.071,1.071A1.086,1.086,0,0,1,14.286-11.964ZM2.857-2.679a.705.705,0,0,0-.714.714V.893a.705.705,0,0,0,.714.714H17.143a.705.705,0,0,0,.714-.714V-1.964a.705.705,0,0,0-.714-.714ZM0-1.964A2.877,2.877,0,0,1,2.857-4.821H17.143A2.877,2.877,0,0,1,20-1.964V.893A2.877,2.877,0,0,1,17.143,3.75H2.857A2.877,2.877,0,0,1,0,.893ZM10.714-.536a1.086,1.086,0,0,1,1.071-1.071A1.086,1.086,0,0,1,12.857-.536,1.086,1.086,0,0,1,11.786.536,1.086,1.086,0,0,1,10.714-.536Zm4.643-1.071A1.086,1.086,0,0,1,16.429-.536,1.086,1.086,0,0,1,15.357.536,1.086,1.086,0,0,1,14.286-.536,1.086,1.086,0,0,1,15.357-1.607Z" transform="translate(0 16.25)"/></svg>
                <span>Сервера</span>
            </a>
        </div>
    </div>
</div>

<div class="stat-count_group">
    <a class="stat-count_item">
        <p class="stat-count_item-value"><span class="AppTooltip" title="Активные сервера"><?= $APP['info']['server_counts']['count_active'] ?></span>&nbsp;<span style="color: var(--dop-text-color); font-size: 0.7em;">/&nbsp;<span class="AppTooltip" title="Общее количество серверов"><?= $APP['info']['server_counts']['count'] ?></span></span></p>
        <p class="stat-count_item-name">Cервера</p>
    </a>
    <a class="stat-count_item">
        <p class="stat-count_item-value"><span class="AppTooltip" title="Активные ключи"><?= $APP['info']['key_counts']['count_active'] ?></span>&nbsp;<span style="color: var(--dop-text-color); font-size: 0.7em;">/&nbsp;<span class="AppTooltip" title="Общее количество ключей"><?= $APP['info']['key_counts']['count'] ?></span></span></p>
        <p class="stat-count_item-name">Ключи</p>
    </a>
    <a class="stat-count_item AppTooltip" title="Общее количество устройств онлайн (за последние 5 минут)">
        <p class="stat-count_item-value"><?= $APP['info']['key_stat']['device_online'] ?></p>
        <p class="stat-count_item-name">Онлайн</p>
    </a>
    <a class="stat-count_item AppTooltip" title="Средняя скорость передачи данных (за последние 5 минут)">
        <p class="stat-count_item-value"><?= $APP['info']['key_stat']['speed']['data'] ?>&nbsp;<span style="color: var(--dop-text-color); font-size: 0.7em;"><?= $APP['info']['key_stat']['speed']['type'] ?></span></p>
        <p class="stat-count_item-name">Скорость</p>
    </a>
    <a class="stat-count_item"></a>
</div>