<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Link2 Data Intelligense',
            'email' => 'link2datai@link2b.com.br',
        ]);

        //$filiais = Filial::all();
        //$Vendedores = Vendedor::all();
        //$meses = ['05', '06', '07', '08', '09', '10', '11', '12'];

        /*foreach ($meses as $mes) {

            /*foreach ($filiais as $filial) {
                $meta_filial = new MetasFiliais();

                $meta_filial->filial_id = $filial->id;
                $meta_filial->meta_faturamento = rand(5000.00, 300000.00);
                $meta_filial->meta_acessorios = rand(10000.00, 30000.00);
                $meta_filial->meta_aparelhos = rand(100000.00, 300000.00);
                $meta_filial->meta_pos = rand(10000.00, 30000.00);
                $meta_filial->meta_pre = rand(10000.00, 30000.00);
                $meta_filial->meta_controle = rand(10000.00, 30000.00);
                $meta_filial->meta_gross_pos = rand(100, 3000);
                $meta_filial->meta_gross_pre = rand(100, 3000);
                $meta_filial->meta_gross_controle = rand(100, 3000);

                $meta_filial->mes = $mes;
                $meta_filial->ano = '2024';
                $meta_filial->total_dias_mes = '28';
                $meta_filial->dias_trabalhado = '28';
                $meta_filial->save();
            }*/

        /*foreach ($Vendedores as $vendedor) {
            $meta_vendedor = new MetasVendedores();

            $meta_vendedor->vendedor_id = $vendedor->id;
            $meta_vendedor->meta_faturamento = rand(5000.00, 10000.00);
            $meta_vendedor->meta_acessorios = rand(1000.00, 30000.00);
            $meta_vendedor->meta_aparelhos = rand(5000.00, 10000.00);
            $meta_vendedor->meta_pos = rand(10000.00, 30000.00);
            $meta_vendedor->meta_pre = rand(10000.00, 30000.00);
            $meta_vendedor->meta_controle = rand(10000.00, 30000.00);
            $meta_vendedor->meta_gross_pos = rand(100, 3000);
            $meta_vendedor->meta_gross_pre = rand(100, 3000);
            $meta_vendedor->meta_gross_controle = rand(100, 3000);

            $meta_vendedor->mes = $mes;
            $meta_vendedor->ano = '2024';
            //$meta_vendedor->total_dias_mes = '28';
            //$meta_vendedor->dias_trabalhado = '28';
            $meta_vendedor->save();
        }
    }*/
    }
}
