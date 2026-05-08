<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">質問への回答</h1>
            <a href="{{ $profile->public_url }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-box-arrow-up-right"></i> 公開ページを開く
            </a>
        </div>
    </x-slot>

    @if (session('status') === 'answers-updated')
        <div class="alert alert-success small">回答を保存しました。</div>
    @endif

    <p class="text-muted small mb-4">
        運営が用意した質問にあなたの言葉で答えてください。
        空欄のまま保存すると、その質問は公開ページに表示されません（500文字以内）。
    </p>

    <form method="post" action="{{ route('mypage.answers.update') }}">
        @csrf
        @method('patch')

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                @foreach ($questions as $idx => $q)
                    <div class="mb-4 {{ $idx === count($questions) - 1 ? 'mb-0' : '' }}">
                        <label for="answer-{{ $q->id }}" class="form-label fw-semibold">
                            <span class="text-muted small me-2">Q{{ $loop->iteration }}.</span>
                            {{ $q->body }}
                        </label>
                        <textarea id="answer-{{ $q->id }}"
                                  name="answers[{{ $q->id }}]"
                                  rows="2" maxlength="500"
                                  class="form-control"
                                  placeholder="（空欄のままでもOK）">{{ old("answers.{$q->id}", optional($answers->get($q->id))->body) }}</textarea>
                        <x-input-error :messages="$errors->get(\"answers.{$q->id}\")" />
                    </div>
                @endforeach
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">キャンセル</a>
            <x-primary-button>保存する</x-primary-button>
        </div>
    </form>
</x-app-layout>
