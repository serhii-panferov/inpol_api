<?php

namespace Database\Seeders;

use App\Models\ReservationQueues;
use Illuminate\Database\Seeder;

class ReservationQueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reservationQueues = [
            '9483073d-97fb-47e7-a126-8a5ce809e568' => [
                [
                    "localization" => "ul. Marszałkowska 3/5, 00-624 Warszawa",
                    "prefix" => "X",
                    "id" => "3ab99932-8e53-4dff-9abf-45b8c6286a99",
                    "polish" => "X\u00A0-\u00A0Wnioski\u00A0o\u00A0POBYT\u00A0CZASOWY\u00A0",
                    "english" => "X - applications for TEMPORARY STAY  ",
                    "russian" => "X - заявки на ВРЕМЕННОЕ ПРЕБЫВАНИЕ",
                    "ukrainian" => "X - заявки на ТИМЧАСОВЕ ПРЕЖИЖЕННЯ",
                ],
                [
                    "localization"=> "Al. Jerozolimskie 28, 00-024 Warszawa",
                    "prefix" => "F",
                    "id" => "c93674d6-fb24-4a85-9dac-61897dc8f060",
                    "polish" => "F\u00A0-\u00A0Wnioski\u00A0o\u00A0POBYT\u00A0CZASOWY\u00A0Al. Jerozolimskie 28",
                    "english" => "F - applications for TEMPORARY STAY Al. Jerozolimskie 28",
                    "russian" => "F - заявки на ВРЕМЕННОЕ ПРЕБЫВАНИЕ Иерусалимские 28",
                    "ukrainian" => "F - заявки на ТИМЧАСОВЕ ПРЕЖИВЛЕННЯ Иерусалимские 28",
                ],
                [
                    "localization"=> "pl. Bankowy 3/5 00-950 Warszawa",
                    "prefix" => "G",
                    "id" => "f0992a78-802d-40e7-9bd0-c0d8d46a71fd",
                    "polish" => "G - Wnioski\u00A0o\u00A0POBYT\u00A0CZASOWY\u00A0\u00A0-\u00A0małżeństwa\u00A0i\u00A0pobyty\u00A0rodzinne\u00A0Pl.Bankowy\u00A03/5\u00A0wejście\u00A0G",
                    "english" => "G - Applications for TEMPORARY STAY - marriages and family stays 3/5 Bankowy Square entrance G",
                    "russian" => "G - Заявления на ВРЕМЕННОЕ ПРЕБЫВАНИЕ - брак и семейное проживание, 3/5, Банковская площадь, вход G",
                    "ukrainian" => "G - Заявки на тимчасове проживання - шлюби та сімейні проживання 3/5 вхід на площу Банкові G / G",
                ],
            ],
            '9e8b5224-45d3-45dd-a802-cbd869b1ca9a' => [
                [
                    "localization" => "ul. Krucza 5/11, 00-548 Warszawa",
                    "prefix" => "S",
                    "id" => "770a491c-4776-45fc-8667-fa0859030f42",
                    "polish" => "S - Wnioski\u00A0o\u00A0zezwolenie\u00A0na\u00A0POBYT\u00A0STAŁY\u00A0oraz\u00A0REZYDENTA\u00A0DŁUGOTERMINOWEGO\u00A0UE\u00A0Krucza\u00A05/11\u00A0Warszawa",
                    "english" => "S - Applications for a PERMANENT RESIDENCE and EU LONG-TERM RESIDENT Krucza 5/11 Warsaw",
                    "russian" => "S - Заявления на получение ПМЖ и ДОЛГОСРОЧНОГО РЕЗИДЕНТА ЕС Krucza 5/11 Варшава",
                    "ukrainian" => "S - Заявки на ПОСТІЙНУ РЕЗИДЕНЦІЮ та ДОЛГОСРОЧНУ РЕЗИДЕНТУ ЄС Krucza 5/11 Варшава",
                ],
            ],
        ];
        ReservationQueues::updateOrCreateMany($reservationQueues);
    }
}
