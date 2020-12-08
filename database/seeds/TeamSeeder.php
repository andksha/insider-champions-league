<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Arsenal',
            'Aston Villa',
            'Brighton & Hove Albion',
            'Burnley',
            'Chelsea',
            'Crystal Palace',
            'Everton',
            'Fulham',
            'Leeds United',
            'Leicester City',
            'Liverpool',
            'Manchester City',
            'Manchester United',
            'Newcastle United',
            'Sheffield United',
            'Southampton',
            'Tottenham Hotspur',
            'West Bromwich Albion',
            'West Ham United',
            'Wolverhampton Wanderers',
        ];

        $teams = [];
        $formula = fn ($i) => 80 - $i + mt_rand(1, 10);

        for ($i = 0; $i < 20; $i++) {
            $name = $names[$i];
            $attack = $formula($i);
            $middle = $formula($i);
            $defense = $formula($i);

            $overall = (int) (($attack + $middle + $defense) / 3);

            $teams[] = [
                'name' => $name,
                'attack' => $attack,
                'middle' => $middle,
                'defense' => $defense,
                'overall' => $overall,
            ];
        }

        DB::table('teams')->insert($teams);
    }
}
