<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Receipt;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Cookie;
use Carbon\Carbon;
use Illuminate\Support\Arr;



class AuthController extends Controller
{
    public function UserLogin () {

        $user = User::all();
        return view('userAuth.UserLogin', [
            'name' => $user,
            'title' => 'Авторизация'
        ]);
    }

    public function UserAuth () {
        return view('userAuth.UserAuth', ['title' => 'Регистрация']);
    }

    public function Login(Request $request) {

        $validator = Validator::make($request->all(), [
            'login' => 'required|max:50|min:2',
            'password' => 'required'
        ]);

        $login = $request['login'];
        $password = $request['password'];


        if($validator->fails()) {
            return response()->json(['error'=> 'Ошибка при валидации', 'status' => '400']);
        }

        $user = User::where('login', $login)->exists();

        if ($user == null) {
            return response()->json(['message'=> 'Вы не зарегестрировались', 'status' => '400', 'key'=>'USER']);
        }

        $user = User::where('login', $login)->first();

        if (!Hash::check($password, $user->password)) {
            return response()->json(['message'=> 'Неправильный пароль', 'status' => '400', 'key'=>'PASSWORD']);
        }
        $token = Str::random(30);
        $user->token = $token;
        $user->save();
        Cookie::queue('token', $token, 120);
        return response()->json(['message'=> 'Успешно', 'status' => '200', 'token' => $token]);
    }

    public function Registration(Request $request) {

        $validator = Validator::make($request->all(), [
            'login' => 'required|max:50|min:2',
            'password' => 'required',
            'p_repeat' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['error'=> 'Ошибка при валидации', 'status' => '400']);
        }

        $login = $request['login'];
        $password = $request['password'];
        $p_repeat = $request['p_repeat'];

        $user = User::where('login', $login)->exists();

        if ($user != null) {
            return response()->json(['message'=> 'Пользователь уже существует', 'status' => '400', 'key'=>'USER']);
        }

        if ($password != $p_repeat) {
            return response()->json(['message'=> 'Пароль не совпадает', 'status' => '400', 'key'=>'PASSWORD']);
        }

        $user = new User;
        $user->login = $login;
        $user->password = Hash::make($password);
        $user->save();

        //print $request;
        return response()->json(['message'=> 'Пользователь успешно создан', 'status' => '200']);
    }

    public function Main(Request $request) {
        
        $cookie = true;
        if (Cookie::get('token') == null) {
            $cookie = false;
        }
        $origin = $_SERVER['HTTP_HOST'];

        $receipts = Receipt::join('users', 'receipts.userID', 'users.id')
            ->select('receipts.*', 'users.login')->paginate(6);
            //->get();

        $length = count($receipts);
        return view('main.main', [
            'title' => 'Главный меню',
            'cookie' => $cookie,
            'receipts' => $receipts,
            'length' => $length,
            'origin' => 'http://'.$origin.'/'
        ]);
    }

    public function Exit(Request $request) {
        Cookie::queue(Cookie::forget('token'));
        return redirect('/');
    }

    public function CreateReceipt (Request $request) {
        $cookie = true;
        if (Cookie::get('token') == null) {
            $cookie = false;
        }

        return view('main.receipt', [
            'title' => 'Добавить чек',
            'cookie' => $cookie
        ]);
    }

    public function UploadImage (Request $request) {

        $token = Cookie::get('token');

        if ($token == null) {
            return response()->json(['message'=>'Вы не авторизовались', 'status' => '400']);
        }
        $validator = Validator::make($request->all(), [
            'image_1' => 'required|mimes:jpg,png,jpeg',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>'Ошибка при валидаций', 'status' => '400']);
        }
        $newImageName = Str::random(4).'-'.time().'-'.Str::random(4).'.'.$request->image_1->extension();
        $request->image_1->move(public_path('images'), $newImageName);

        $user = User::where('token', $token)->first();

        $current = Carbon::now()->addHour(6);

        $type = 'Обычный';

        if ($current->hour % 2 == 0) {
            $type = 'Призовой';
        }

        $statusString = ['Принят', 'Отклонен'];

        $status = Arr::random($statusString);
        $code = '';
        if ($status == $statusString[0] && $type=='Призовой') {
            $code = Str::random(8);
        }


        $receipt = new Receipt;
        $receipt->userID = $user->id;
        $receipt->image = 'images/'.$newImageName;
        $receipt->type = $type;
        $receipt->code = $code;
        $receipt->status = $status;

        $receipt->save();

        return response()->json(['message'=>'Чек успешно загружилась', 'status' => '200']);
    }
}
