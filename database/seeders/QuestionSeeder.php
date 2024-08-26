<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Subject;
use App\Models\Question;

class QuestionSeeder extends Seeder {

    public function run(): void {

        $subjects = Subject::all();
        
        foreach ($subjects as $subject) {
            for ($i = 1; $i <= 20; $i++) {
                // Cria uma nova questão para cada conteúdo
                $question = Question::create([
                    'subject_id' => $subject->id,
                    'topic_id' => null, // Você pode modificar conforme a estrutura dos seus dados
                    'question_text' => "Questão de matemática $i para o conteúdo: {$subject->name}",
                    'comment_text' => "Comentário para a questão de matemática $i"
                ]);
                
                // Cria duas opções (uma correta e outra incorreta)
                DB::table('options')->insert([
                    [
                        'question_id' => $question->id,
                        'option_number' => 1,
                        'option_text' => "Opção correta para a questão $i",
                        'is_correct' => true
                    ],
                    [
                        'question_id' => $question->id,
                        'option_number' => 2,
                        'option_text' => "Opção incorreta para a questão $i",
                        'is_correct' => false
                    ]
                ]);
            }
        }

    }
}
