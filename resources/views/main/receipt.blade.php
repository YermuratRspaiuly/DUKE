@extends('main')


@section('title', $title)

@include('layout.navbar')


@section('body')
    @if($cookie)
    <div class="create" style="height: 750px">
        <div class="c_container">
            <h1>Добавить чек</h1>
            <div class="image_container">
                <div class="c_title">
                    <h3 >Фото</h3>
                    <span>
                    Добавьте фото чека
                </span>
                </div>
                <div class="i_flex_container">
                    <div class="create_image">
                        <input type="file" class="create_input" name="image_1" id="image_1" accept="image/jpeg,image/jpg,image/png">
                        <span>Добавить фото</span>
                    </div>
                </div>
                <div class="c_title">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="button" id="SendData" class="btn btn--large" value="Добавить чек">
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="create">
            <div>
                <h1>Чтобы добавить чек  создайте аккаунт</h1>
            </div>
    </div>
    @endif
    <script src="{{ asset('js/CreateReceipt.js') }}"></script>
@endsection