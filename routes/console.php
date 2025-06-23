<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;


Schedule::command('datasys:import')->dailyAt(time: '0:30');
Schedule::command('datasys:grupo-estoque')->dailyAt(time: '6:30');
Schedule::command('datasys:modalidade-venda')->dailyAt(time: '6:40');
Schedule::command('datasys:plano-habilitacoes')->dailyAt(time: '6:40');


//Schedule::command('datasys:gerar-dados')->dailyAt(time: '2:30');
//Schedule::command('datasys:transfer')->dailyAt(time: '3:30');
