<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Day1 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $number_map = [
        'one' => '1',
        'two' => '2',
        'three' => '3',
        'four' => '4',
        'five' => '5',
        'six' => '6',
        'seven' => '7',
        'eight' => '8',
        'nine' => '9',
    ];

    public function handle(): void
    {
        $data = file_get_contents(storage_path('app/day1.txt'));
        $rows = explode("\n", $data);

        $sum = 0;
        foreach($rows as $row) {
            if(!$row) {
                continue;
            }

            echo "{$row}\n";
            preg_match_all('/(?=(0|1|2|3|4|5|6|7|8|9|one|two|three|four|five|six|seven|eight|nine))/', $row, $matches);
            $numbers = [];
            foreach($matches[1] as $match) {
                if((int) $match) {
                    $numbers[] = $match;
                    continue;
                }

                $numbers[] = $this->number_map[$match];
            }

            $numbers = implode('', $numbers);
            echo "{$numbers}\n";

            $numbers = str_split(only_numbers($numbers));
            $first = $numbers[0];
            $last = $numbers[sizeof($numbers) - 1];

            $number = (int) "{$first}{$last}";

            echo "{$number}\n";
            echo "----------------\n";

            $sum += $number;
        }

        echo "SUM: {$sum}\n";
    }
}
