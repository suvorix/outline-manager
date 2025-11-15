<div class="page-title_group">
    <div class="page-title_info">
        <h1 class="page-title">Ваши cервера</h1>
        <p class="page-description">Список всех добавленых серверов</p>
    </div>
    <div class="page-title_action">
        <a href="/add-server" class="button" style="margin: 0;">Добавить сервер</a>
    </div>
</div>

<table class="table-app" style="margin-top: 20px;">
    <thead>
        <tr>
            <th>#</th>
            <th>Сервер</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($APP['info']['servers'] as $server): ?>
            <tr>
                <td><?=$server['id']?></td>
                <td><?=$server['name']?><?php if($server['ip'] != '') { echo("<br><code style=\"display: inline-block; margin-top: 5px;\">" . $server['ip'] . "</code>"); } ?></td>
                <td></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>