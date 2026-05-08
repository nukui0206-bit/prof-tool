<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnswerController extends Controller
{
    public function edit(): View
    {
        $profile = Auth::user()->profile;
        $questions = Question::active()->get();
        $answers = $profile->answers()->get()->keyBy('question_id');

        return view('mypage.answers.edit', compact('profile', 'questions', 'answers'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'answers' => ['array'],
            'answers.*' => ['nullable', 'string', 'max:500'],
        ], [
            'answers.*.max' => '回答は 500 文字以内で入力してください。',
        ]);

        $profile = Auth::user()->profile;
        $activeQuestionIds = Question::active()->pluck('id')->all();
        $input = $request->input('answers', []);

        DB::transaction(function () use ($profile, $activeQuestionIds, $input) {
            foreach ($activeQuestionIds as $qid) {
                $body = $input[$qid] ?? null;
                if ($body === null || trim($body) === '') {
                    $profile->answers()->where('question_id', $qid)->delete();
                } else {
                    $profile->answers()->updateOrCreate(
                        ['question_id' => $qid],
                        ['body' => $body, 'sort_order' => 0],
                    );
                }
            }
        });

        return redirect()->route('mypage.answers.edit')->with('status', 'answers-updated');
    }
}
