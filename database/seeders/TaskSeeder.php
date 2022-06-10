<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i < 11; $i++){
            Task::create([
                'name' => 'Tarefa ' . $i,
                'description' => 'Descrição da tarefa ' . $i,
                'file_url' => ''
            ]);
        }
    }
}
