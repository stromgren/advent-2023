<?php

function only_numbers($mixed)
{
    return preg_replace('/[^0-9]/', '', $mixed);
}
