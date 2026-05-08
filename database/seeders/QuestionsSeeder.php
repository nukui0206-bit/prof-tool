<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            '最近ハマっていることは？',
            '好きな食べ物は？',
            '無人島に1つだけ持っていくなら？',
            '推しを語って！',
            '一番テンションが上がる曲は？',
            'いま行ってみたい場所は？',
            'ストレス発散方法は？',
            '自分を動物に例えると？',
            '休日の過ごし方は？',
            '影響を受けた人は？',
            '集めているものは？',
            '最後に「ありがとう」と伝えた相手は？',
        ];

        foreach ($questions as $i => $body) {
            Question::updateOrCreate(
                ['body' => $body],
                ['sort_order' => $i + 1, 'is_active' => true],
            );
        }
    }
}
