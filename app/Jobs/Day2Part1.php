<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Day2Part1 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $data = file_get_contents(storage_path('app/day2.txt'));
        preg_match_all('/Game (?<game>\d*):(?<data>.*)/', $data, $matches);

        $available_cubes = [
            'red' => 12,
            'green' => 13,
            'blue' => 14,
        ];

        $possible_games = [];
        foreach($matches['game'] as $index => $game_id) {
            $game_data = $matches['data'][$index];
            $plays = array_filter(explode(';', $game_data));
            $game_is_possible = true;

            foreach($plays as $play) {
                preg_match_all('/(\s?(?<number>\d*)\s(?<color>red|green|blue))/', $play, $play_matches);
                foreach($play_matches['number'] as $number_index => $number) {
                    if($available_cubes[$play_matches['color'][$number_index]] < $number) {
                        $game_is_possible = false;
                        break;
                    }
                }
            }

            if($game_is_possible) {
                $possible_games[] = $game_id;
            }
        }

        $sum = array_sum($possible_games);
        echo "SUM: {$sum}\n";
    }
}
