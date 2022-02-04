@extends('main')


@section('title', $title)

@include('layout.navbar')

@section('body')

    <div class="row">
        @if($length)
            @foreach($receipts as $receipt)
        <div class="column">
            <div class="card">
                <div class="image">
                    <img src="{{$origin.$receipt->image}}" alt="">
                </div>
                <div class="content">
                    <h2 class="branch_name"></h2>
                    <p class="title">Клиент: {{$receipt->login}} <b></b></p>
                    <p><b>ТИП:</b> {{$receipt->type}} </p>
                    <p><b>КОД:</b> {{$receipt->code}} </p>
                    @if(\Carbon\Carbon::parse($receipt->created_at)->addWeekday()->format('Y-m-d')<\Carbon\Carbon::now()->format('Y-m-d'))
                        <p><b>Дата:</b> Не учавствует на этой неделе</p>
                    @else
                        <p><b>Дата:</b> {{\Carbon\Carbon::parse($receipt->created_at)->format('Y-m-d')}} </p>
                    @endif
                    <p><b>СТАТУС:</b> {{$receipt->status}} </p>
                </div>
            </div>
        </div>
            @endforeach
        @else
        <h1>Пока у вас нет филиалов</h1>
        @endif
    </div>
    <div class="center">
        {{$receipts->links()}}
    </div>
    <style>
        .hidden {
            display: none;
        }
        .center {
            padding: 30px 0;
            display: flex;
            justify-content: center;
        }
        .w-5 {
            display: none;
        }
    </style>

@endsection