<div class="navbar">
    <a href="/">Все чеки</a>
    <a href="/create/receipt">Добавить чек</a>
    <div class="right-navbar">
        @if($cookie)
            <a id="exit" href="/exit">Выйти</a>
        @else
            <a id="exit" href="/signin">Логин</a>
            <a id="exit" href="/signup">Регистрация</a>
        @endif
    </div>
</div>
<div class="mask hide">
    <div>
        <h1 id="success"></h1>
    </div>
</div>

<script>
    let exit = document.querySelector('#exit')
</script>