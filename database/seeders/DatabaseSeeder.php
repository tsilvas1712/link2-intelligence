<?php

namespace Database\Seeders;

use App\Models\Filial;
use App\Models\MetasFiliais;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Link2 Data Intelligence',
            'email' => 'link2datai@link2b.com.br',
        ]);*/

        $filiais = Filial::all();

        foreach ($filiais as $filial) {
            $meta_filial = new MetasFiliais();

            $meta_filial->filial_id = $filial->id;
            $meta_filial->meta_faturamento = 300000.00;
            $meta_filial->meta_acessorios = 20000.00;
            $meta_filial->meta_aparelhos = 300000.00;
            $meta_filial->meta_pos = 1000.00;
            $meta_filial->meta_pre = 1000.00;
            $meta_filial->meta_controle = 1000.00;
            $meta_filial->meta_gross_pos = 500;
            $meta_filial->meta_gross_pre = 500;
            $meta_filial->meta_gross_controle = 500;

            $meta_filial->mes = '11';
            $meta_filial->ano = '2024';
            $meta_filial->save();
        }
    }
}
