<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 fw-bold mb-0">マイページ</h1>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            ようこそ、{{ Auth::user()->name }} さん。
            <p class="text-muted small mb-0 mt-2">Phase 1 以降でプロフィール編集・公開URLなどが追加されます。</p>
        </div>
    </div>
</x-app-layout>
