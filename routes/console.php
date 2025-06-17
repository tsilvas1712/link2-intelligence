<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;


Schedule::command('datasys:import')->dailyAt(time: '0:30');

//Schedule::command('datasys:gerar-dados')->dailyAt(time: '2:30');
//Schedule::command('datasys:transfer')->dailyAt(time: '3:30');
