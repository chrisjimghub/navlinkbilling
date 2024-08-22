<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'customer_id' => NULL,
                'name' => 'Super Admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$6QozZgdRa/Y.aBgzURvi5uLMTcfxISjiS9kv5FmJenpv9eLE8vxDG',
                'theme' => NULL,
                'remember_token' => 'XWW5o4N98QZihf9u3jBrlS8fwc5dLoBZ9nsEahUfrUg1kQ86eYzahDlNOt1o',
                'created_at' => '2024-06-10 06:00:00',
                'updated_at' => '2024-10-22 11:46:58',
            ),
            1 => 
            array (
                'id' => 5,
                'customer_id' => NULL,
                'name' => 'Louie James Duncano Andoy',
                'email' => 'james@james.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$IHeLv1bXFJKdgKmeURsuj.KxkNU.E/aL0LpgUKtNnUd7RTPhc7fE.',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:17:50',
                'updated_at' => '2024-10-22 11:17:50',
            ),
            2 => 
            array (
                'id' => 6,
                'customer_id' => NULL,
                'name' => 'Narciso V.  Baguio',
                'email' => 'narciso@narciso.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$/GPwzintkbgxeH2uesl9uugQzt79qtcAUnLtbcuYFCBZ182PHqBbW',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:18:59',
                'updated_at' => '2024-10-22 11:18:59',
            ),
            3 => 
            array (
                'id' => 7,
                'customer_id' => NULL,
                'name' => 'Arwin Silang Bascuguin',
                'email' => 'arwin@arwin.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$t5PAXWUJ999rG5h/pDy5y.yLrgEw3Ov.E6B04abPFk8RSG82ZLeNa',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:19:32',
                'updated_at' => '2024-10-22 11:19:32',
            ),
            4 => 
            array (
                'id' => 8,
                'customer_id' => NULL,
                'name' => 'Junila May Regino Condinato',
                'email' => 'junila@junila.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$qXTrv/YOMV03jvizYjRHtedCH1032w7L1dlWzxG84XDjuh2n.BDzO',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:20:11',
                'updated_at' => '2024-10-22 11:20:11',
            ),
            5 => 
            array (
                'id' => 9,
                'customer_id' => NULL,
                'name' => 'Martin M.  Delos Santos',
                'email' => 'martin@martin.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$uSuSANmAkgEuV0ojQg.7w.wBQnQ5Sugb/yRRi.J86suUdtuFe6uGa',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:20:46',
                'updated_at' => '2024-10-22 11:20:46',
            ),
            6 => 
            array (
                'id' => 10,
                'customer_id' => NULL,
                'name' => 'Remart Singson Diaz',
                'email' => 'remart@remart.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$NLj9luKMVL8y/ZqbdCA3p.ETLniVhIWosEUdvv22fM2sQPvVVY4DK',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:21:19',
                'updated_at' => '2024-10-22 11:21:19',
            ),
            7 => 
            array (
                'id' => 11,
                'customer_id' => NULL,
                'name' => 'Aproniano Aratia III Juanillo',
                'email' => 'apron@apron.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$IK098Yf1RVDkdg0tGeXOj.d2POomk44GEzE9M.XwjXJAxDzx8X8Aa',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:21:53',
                'updated_at' => '2024-10-22 11:21:53',
            ),
            8 => 
            array (
                'id' => 12,
                'customer_id' => NULL,
                'name' => 'Ranel Deximo Noynay',
                'email' => 'ranel@ranel.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$b9kw7I/HzLvsZSav19WjvOojgiMZiHNVzX2VoKhoKIh/mHH.xqluq',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:22:47',
                'updated_at' => '2024-10-22 11:22:47',
            ),
            9 => 
            array (
                'id' => 13,
                'customer_id' => NULL,
                'name' => 'Glene L.  Paredes',
                'email' => 'glen@glen.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$JiwMdhVKTbU0Y75ntjcA1evvTtCs39oKiWyvloqCL1gh3XBhleE0C',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:23:30',
                'updated_at' => '2024-10-22 11:23:30',
            ),
            10 => 
            array (
                'id' => 14,
                'customer_id' => NULL,
                'name' => 'Rose Queen Mananita Sedillo',
                'email' => 'rosequeen@rosequeen.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$NG0ZNrOZ61S/lWlhjFAGgONeXHlK4SwyTCiqoiDJjow5vbBBWaHHG',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:24:38',
                'updated_at' => '2024-10-22 11:24:38',
            ),
            11 => 
            array (
                'id' => 15,
                'customer_id' => NULL,
                'name' => 'John Clarence Gaspan Tabernero',
                'email' => 'john@john.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$r.r5N09CrHePecCHU9Qvl.ADQECQkC4ULvHG2OycP9L./F0nssk0e',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:25:37',
                'updated_at' => '2024-10-22 11:25:37',
            ),
            12 => 
            array (
                'id' => 16,
                'customer_id' => NULL,
                'name' => 'Renevie Pantaleon Tipontipon',
                'email' => 'renevie@renevie.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$3zP6WRDLOxMkSLjYsvhUmu4PabbibD5ONznMOLNPC7qu5ROWMrTKW',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:26:08',
                'updated_at' => '2024-10-22 11:26:08',
            ),
            13 => 
            array (
                'id' => 17,
                'customer_id' => NULL,
                'name' => 'Renan Basilio Velasco',
                'email' => 'renan@renan.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$KVJamdDs7Lkyk9QQY5NcjeSW7c6Fi4TjAcFmUQr1HKGtFLe2/CRG2',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:27:23',
                'updated_at' => '2024-10-22 11:27:23',
            ),
            14 => 
            array (
                'id' => 18,
                'customer_id' => NULL,
                'name' => 'Luciano M.  Jr. Wenceslao',
                'email' => 'luciano@luciano.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$lxZoG5eTltZWE1xlAgVGaugZj.GfnsjVMOSbAji9MQ3/P9W7ptS9a',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:29:11',
                'updated_at' => '2024-10-22 11:29:11',
            ),
            15 => 
            array (
                'id' => 19,
                'customer_id' => NULL,
                'name' => 'Philip Tatoy Panginahug',
                'email' => 'philip@philip.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$jG159eYiut2qB3QhV2/8yO1xjWag5sEUwthbkBVCuLIHQrn.3KhXS',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:29:42',
                'updated_at' => '2024-10-22 11:29:42',
            ),
            16 => 
            array (
                'id' => 20,
                'customer_id' => NULL,
                'name' => 'Raffy Wenceslao Gallarde',
                'email' => 'raffy@raffy.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$EAA5Zz0lu6dn7NJfXB75Z.Cv60AaUONlCNqHuTjfR6TEBC4QNIYYS',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:30:33',
                'updated_at' => '2024-10-22 11:30:33',
            ),
            17 => 
            array (
                'id' => 21,
                'customer_id' => NULL,
                'name' => 'Jemar M. Wenceslao',
                'email' => 'jemar@jemar.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$xPMcdB3aJPlHz3GJzjvLKuoJ0HqX1cljSjOb890APsuJKFXCaiK1O',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:31:23',
                'updated_at' => '2024-10-22 11:31:23',
            ),
            18 => 
            array (
                'id' => 22,
                'customer_id' => NULL,
                'name' => 'Randy Aballe',
                'email' => 'randy@randy.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$G/3hSQoRY2bIe.IMU3.BiOye6YB12YPBkvZKgtNiD0EaNO3ulKRga',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:32:38',
                'updated_at' => '2024-10-22 11:32:38',
            ),
            19 => 
            array (
                'id' => 23,
                'customer_id' => NULL,
                'name' => 'Manny T. Condinato',
                'email' => 'manny@manny.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$9I4P/fXMmq8fT0u8jOcvVuNvS.KAWT8aEBKGMe1/vboXnXxoDazzG',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:33:39',
                'updated_at' => '2024-10-22 11:33:39',
            ),
            20 => 
            array (
                'id' => 24,
                'customer_id' => NULL,
                'name' => 'Ruel M. Argallon',
                'email' => 'ruel@ruel.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$aOC0ZfQKkahVebcYbtDeV.oXXaceY5Bbi1dIC6U6EHQ.PIGgnUcSC',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:34:07',
                'updated_at' => '2024-10-22 11:34:07',
            ),
            21 => 
            array (
                'id' => 25,
                'customer_id' => NULL,
                'name' => 'Jay-ar Dosdos',
                'email' => 'jay@jay.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$Pn1bDwSPG1UGmcIba6DTteG40.4GGI2fZsbND3qpEC8L6KCaAWzJO',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:34:37',
                'updated_at' => '2024-10-22 11:34:37',
            ),
            22 => 
            array (
                'id' => 26,
                'customer_id' => NULL,
                'name' => 'Rhyan Dorog',
                'email' => 'rhyan@rhyan.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$PIxyydGd13z.8DmtuuF7OudqBVid8zrxXjuuCgyYSNZG/5hSIh6aS',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:35:10',
                'updated_at' => '2024-10-22 11:35:10',
            ),
            23 => 
            array (
                'id' => 27,
                'customer_id' => NULL,
                'name' => 'Chris Jim Egot',
                'email' => 'echrisjim@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$uQSYup6cV.hSRd5xllEDDuQXHfuM1yWwCI8NgEdNX/pKp254zRvRq',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:36:17',
                'updated_at' => '2024-10-22 11:36:17',
            ),
            24 => 
            array (
                'id' => 28,
                'customer_id' => NULL,
                'name' => 'James Marion Decio Marces',
                'email' => 'jamesmarion@jamesmarion.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$DMaYgsUQsfagcdkLonH63Op6XH3ZJsUQI4a0DjPdc5q9qC6p5jQ6G',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:36:56',
                'updated_at' => '2024-10-22 11:36:56',
            ),
            25 => 
            array (
                'id' => 29,
                'customer_id' => NULL,
                'name' => 'Adam Arriesgado Abella',
                'email' => 'adam@adam.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$NxGZoxu67YgMb3OTRectReDFA5hEvXQrLAOdGWfj5tNjWmOZ01E/K',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:37:33',
                'updated_at' => '2024-10-22 11:37:33',
            ),
            26 => 
            array (
                'id' => 30,
                'customer_id' => NULL,
                'name' => 'Alvin Tabernero Villanueva',
                'email' => 'alvin@alvin.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$2Sr5Su8oTdtPMplF2.QmtutH/8vwCE2ZLuDBwy3IZ2OUhvhe9BB7a',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:39:26',
                'updated_at' => '2024-10-22 11:39:26',
            ),
            27 => 
            array (
                'id' => 31,
                'customer_id' => NULL,
                'name' => 'Irene Singson Baguio',
                'email' => 'irene@irene.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$SdKu3j9nakfqHdMlxWKO4uXZYyA854Wwlh.ObTXOykSdILvbopVS.',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:40:12',
                'updated_at' => '2024-10-22 11:40:12',
            ),
            28 => 
            array (
                'id' => 32,
                'customer_id' => NULL,
                'name' => 'Narvyl Singson Baguio',
                'email' => 'narvyl@narvyl.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$uAumhccTAngPycJqd.EOG.OoRQBZzUO2yfQCyM1.VOMvZ0KDJGUNa',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:41:01',
                'updated_at' => '2024-10-22 11:41:01',
            ),
            29 => 
            array (
                'id' => 33,
                'customer_id' => NULL,
                'name' => 'Filrose Pasqueti Marces',
                'email' => 'filrose0108@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$uUXjZEM11oTde14.qM9Ra.9IJGNsA1//YxI7k4pISits6CKUMREcO',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:44:22',
                'updated_at' => '2024-10-22 11:44:22',
            ),
            30 => 
            array (
                'id' => 34,
                'customer_id' => NULL,
                'name' => 'Ivy Rose Tompon Losorata',
                'email' => 'ivyrose@ivyrose.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$LDsUNhwN5/hjnnAxw.8apuzwnR1Gu23h3sVR4kn3o5Kzs7Qkfu92u',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:45:25',
                'updated_at' => '2024-10-22 11:45:25',
            ),
            31 => 
            array (
                'id' => 35,
                'customer_id' => 1,
                'name' => 'Egot, Chris Jim',
                'email' => 'jimegot@yahoo.com.ph',
                'email_verified_at' => NULL,
                'password' => '$2y$12$lOIp1HBpe2hgb3v9CyL8HuzO1ciJpEXFA.Y/d3.sO9jTAhHudwdE.',
                'theme' => NULL,
                'remember_token' => NULL,
                'created_at' => '2024-10-22 11:52:48',
                'updated_at' => '2024-10-22 11:52:48',
            ),
        ));
        
        
    }
}