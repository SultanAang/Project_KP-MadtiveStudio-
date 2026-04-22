<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UsersTableSeeder extends Seeder {
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run() {
        DB::table("users")->delete();

        DB::table("users")->insert([
            0 => [
                "id" => 1,
                "name" => "Admin ",
                "email" => "admin@abc.com",
                "email_verified_at" => null,
                "password" => '$2y$12$y.MrxX7OvkVg8t/a2bypLeqV5dDIPQN7MBfWcieXTCmRUMvvd7xP2',
                "remember_token" => null,
                "created_at" => "2026-02-01 08:34:53",
                "updated_at" => "2026-02-01 08:34:53",
                "avatar" => null ,
                "username" => "admin",
                "role" => "admin",
            ],
            1 => [
                "id" => 3,
                "name" => "tim",
                "email" => "tim@gmail.com",
                "email_verified_at" => null,
                "password" => '$2y$12$vRZCKuda4QhM4kS/ENQ3iualV05cRmrCIR6IocDrDmY03nqCmrHAG',
                "remember_token" => null,
                "created_at" => "2026-02-01 08:36:19",
                "updated_at" => "2026-02-01 08:36:19",
                "avatar" => null,
                "username" => "timDoc",
                "role" => "tim_dokumentasi",
            ],
            2 => [
                "id" => 5,
                "name" => "reviewer",
                "email" => "ipsum@abc.com",
                "email_verified_at" => null,
                "password" => '$2y$12$ocVWyikiNKVDM30HDZu/guGZUSr4vChi.AlpQLpViw6MXDtwhCT16',
                "remember_token" => null,
                "created_at" => "2026-02-01 09:42:50",
                "updated_at" => "2026-02-01 09:42:50",
                "avatar" => null,
                "username" => "ipsum",
                "role" => "reviewer_internal",
            ],
            3 => [
                "id" => 6,
                "name" => "Client milku940 Lagi",
                "email" => "milku@gmail.com",
                "email_verified_at" => null,
                "password" => '$2y$12$aH3QzOHOD8rQhPvciaVF1uaOUKGIveyXceSzDt3rIZY3K.4c2uQVe',
                "remember_token" => null,
                "created_at" => "2026-02-01 10:53:05",
                "updated_at" => "2026-03-30 17:07:49",
                "avatar" => null,
                "username" => "milku940",
                "role" => "client",
            ],
            4 => [
                "id" => 8,
                "name" => "Client korek658",
                "email" => "korek@gmail.com",
                "email_verified_at" => null,
                "password" => '$2y$12$HFznOXAGm1O1yVNHBqZh6O1OLvl8TBCNfrO585svixOKGcdl8ImTm',
                "remember_token" => null,
                "created_at" => "2026-02-01 16:42:47",
                "updated_at" => "2026-02-01 16:55:40",
                "avatar" => null,
                "username" => "korek658",
                "role" => "client",
            ],
            5 => [
                "id" => 10,
                "name" => "Client snowman378",
                "email" => "snowman@gmail.com",
                "email_verified_at" => null,
                "password" => '$2y$12$7XX5EXHrkfdRtFOW2MdWP.DFCjAj/j1eKTjMWvjSnU3TVnYTOcYiC',
                "remember_token" => null,
                "created_at" => "2026-03-13 16:29:51",
                "updated_at" => "2026-03-13 16:29:51",
                "avatar" => null,
                "username" => "snowman378",
                "role" => "client",
            ],
            6 => [
                "id" => 11,
                "name" => "Super Admin",
                "email" => "abcd@gmail.com",
                "email_verified_at" => null,
                "password" => '$2y$12$ebubDADHw6TG.4zAFXHsQelX5v37eiCQTCNQ2pjKPq8hsTIdvqKf.',
                "remember_token" => null,
                "created_at" => "2026-03-13 16:30:59",
                "updated_at" => "2026-03-13 16:30:59",
                "avatar" => null,
                "username" => "adminSuper",
                "role" => "admin",
            ],
            7 => [
                "id" => 13,
                "name" => "Azka Fauzan Tawakkal",
                "email" => "kakkaaka@gmail.com",
                "email_verified_at" => null,
                "password" => '$2y$12$A377r.WRVoE2GEdgpA1LJe.eRWsS3A.499SvBr9LBbSb13uIJMLSi',
                "remember_token" => null,
                "created_at" => "2026-03-27 09:09:02",
                "updated_at" => "2026-03-27 09:09:02",
                "avatar" => "avatar/NrxMI4szR71ZJf0k926YeNqiIeZhnQcbE3bwnyBO.jpg",
                "username" => "kkkkk",
                "role" => "admin",
            ],
            8 => [
                "id" => 14,
                "name" => "76 Apel",
                "email" => "apel321@gmail.com",
                "email_verified_at" => null,
                "password" => '$2y$12$dGurI3hHlD.HqLdg7j56leXibEKY4TcPKquzpZXQC0DGf/OJV4wpq',
                "remember_token" => null,
                "created_at" => "2026-03-30 17:09:26",
                "updated_at" => "2026-03-30 17:09:26",
                "avatar" => null,
                "username" => "apel321",
                "role" => "tim_dokumentasi",
            ],
        ]);
    }
}
