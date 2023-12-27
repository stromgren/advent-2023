<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class Day3Part1 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $data = $this->getData();

        $parts = [];

        $row_count = sizeof($data);
        $column_count = sizeof($data[0]);
        for($row = 0; $row < $row_count; $row++) {
            $current_part_number = null;
            for($column = 0; $column < $column_count; $column++) {
                $symbol = $data[$row][$column];
                if(is_numeric($symbol)) {
                    $current_part_number .= $symbol;
                }

                if($current_part_number && (
                    $symbol === '.'
                    || in_array($symbol, $this->getPossibleSymbols())
                    || $column === $column_count - 1)
                ) {
                    if($this->parsePart($current_part_number, $row, $column - strlen($current_part_number))) {
                        $parts[] = (int)$current_part_number;
                    }

                    $current_part_number = null;
                }
            }
        }

        $sum = array_sum($parts);
        echo "SUM: {$sum}\n";
    }

    private function parsePart($part_number, $row, $column)
    {
        $data = $this->getData();

        $col_start = max($column - 1, 0);
        $col_end = min($col_start + strlen($part_number) + 2, sizeof($data[0]));

        if($row === 0) {
            $above = false;
        } else {
            $above = $this->checkForSymbols($data[$row - 1], $col_start, $col_end);
        }

        $adjacent = $this->checkForSymbols($data[$row], $col_start, $col_end);

        if($row === sizeof($data) - 1) {
            $below = false;
        } else {
            $below = $this->checkForSymbols($data[$row + 1], $col_start, $col_end);
        }

        return $above || $adjacent || $below;
    }

    private function checkForSymbols($data, $start, $end)
    {
        $check = substr(implode($data), $start, $end - $start);
        return Str::contains($check, $this->getPossibleSymbols());
    }

    private function getPossibleSymbols() : array
    {
        static $possible_symbols;

        if(!$possible_symbols) {
            $data = $this->getData();
            $symbols = [];

            foreach($data as $row) {
                foreach($row as $column) {
                    if((int) $column || $column === '0' || $column === '.') {
                        continue;
                    }

                    $symbols[] = $column;
                }
            }

            $possible_symbols = array_unique($symbols);
        }

        return $possible_symbols;
    }

    private function getData() : array
    {
        static $data;

        if(!$data) {
            $rows = explode("\n", file_get_contents(storage_path('app/day3.txt')));
            $data = [];

            foreach($rows as $row) {
                if(!$row) {
                    continue;
                }

                $data[] = str_split($row);
            }
        }

        return $data;
    }
}
