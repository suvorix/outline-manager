<div class="page-title_group">
    <div class="page-title_info">
        <h1 class="page-title">Добавление сервера</h1>
    </div>
</div>

<form method="post" action="/add-server">
    <p>Войдите на свой сервер и выполните эту команду</p>
    <code>sudo bash -c "$(wget -qO- https://raw.githubusercontent.com/Jigsaw-Code/outline-server/master/src/server_manager/install_scripts/install_server.sh)"</code>

    <label>
        <p>Вставьте сюда результаты вашей установки:</p>
        <input type="text" name="server-data" placeholder="{&quot;apiUrl&quot;:&quot;https://xxx.xxx.xxx.xxx:xxxxx/xxxxxxxxxxxxxxxxxxxxxx&quot;,&quot;certSha256&quot;:&quot;xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx&quot;}">
    </label>
    <div>
        <button style="display: inline-block; width: auto;">Добавить сервер</button>
        <a href="/servers" style="display: inline-block; width: auto;" class="button btn-second">Отмена</a>
    </div>
</form>