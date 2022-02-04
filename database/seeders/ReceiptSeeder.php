<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class ReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = ['Принят','Отклонен'];

        foreach (range(0, 100) as $number) {

            $sec = rand(0, 59);
            $min = rand(0, 59);
            $hour = rand(0, 23);
            $day = rand(1, 28);
            $month = rand(1, 12);
            $year = rand(2021, 2022);

            //$RandomDate = $year.'-'.$month.'-'.$day.'  '.$hour.':'.$min.':'.$sec;
            //$datetime = Carbon::createFromFormat('Y-m-d H:i:s', $RandomDate);
            $datetime = Carbon::create($year, $month, $day, $hour, $min, $sec);
            $type = 'Призовой';

            if ($hour%2 == 0) {
                $type = 'Обычный';
            }

            $statusEnter = Arr::random($status);

            $code = '';
            if($type=='Призовой' && $statusEnter=='Отклонен'){
                $code = Str::random(8);
            }

                DB::table('receipts')->insert([
                    'userID' => rand(1, 100),
                    'image' => 'images/8liO-1643937500-T0oD.jpg',
                    'type' => $type,
                    'code' => $code,
                    'status' => $statusEnter,
                    'created_at' => $datetime,
//                    'updated_at' => $datetime
                ]);
            }
        }
    }
