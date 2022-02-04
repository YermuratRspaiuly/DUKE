@extends('main')
@section('title', $title)

@section('body')
    <div class="mask hide">
        <div>
            <h1 id="success"></h1>
        </div>
    </div>
    <div class="container-full bg-color-1">
        <div class="flex-container">
            <div class="a-frame">
                <div class="a_inner">
                    <h1 class="a_title">Авторизация</h1>

                    <div class="a_form login">
                        <input class="a_input" name="login" type="text" placeholder="ЛОГИН">
                        <div class="error">
                            <span id="reg_1"></span>
                        </div>
                        <input class="a_input" name="password" type="password" id="" placeholder="ПАРОЛЬ">
                        <div class="error">
                            <span id="reg_2"></span>
                        </div>
                        <br>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input class="a_submit" type="submit" value="Логин">
                        <div class="line">
                            <hr>
                            <div class="l_text">ИЛИ</div>
                        </div>
                    </div>

                    <div class="a_signup">
                        <a href="/signup" class="a_signup_title">Зарегистрироваться</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('/js/UserLogin.js')}}"></script>
@endsection