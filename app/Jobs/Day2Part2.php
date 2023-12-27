<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Day2Part2 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $data = file_get_contents(storage_path('app/day2.txt'));
        preg_match_all('/Game (?<game>\d*):(?<data>.*)/', $data, $matches);

        $game_powers = [];
        foreach($matches['game'] as $index => $game_id) {
            $game_data = $matches['data'][$index];
            $plays = array_filter(explode(';', $game_data));

            $required_cubes = [
                'red' => 0,
                'green' => 0,
                'blue' => 0,
            ];

            foreach($plays as $play) {
                preg_match_all('/(\s?(?<number>\d*)\s(?<color>red|green|blue))/', $play, $play_matches);
                foreach($play_matches['number'] as $number_index => $number) {
                    if($number > $required_cubes[$play_matches['color'][$number_index]]) {
                        $required_cubes[$play_matches['color'][$number_index]] = $number;
                    }
                }
            }

            $game_powers[] = $required_cubes['red'] * $required_cubes['green'] * $required_cubes['blue'];
        }

        $sum = array_sum($game_powers);
        echo "SUM: {$sum}\n";
    }
}
