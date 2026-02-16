<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Редактировать заявку #{{ $submission->id }} – Кабинет участника</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                background: radial-gradient(circle at top left, #e0f2fe, #f5f3ff 40%, #fee2e2 80%);
            }
        </style>
    </head>
    <body class="min-h-screen flex flex-col text-slate-900">
        <div class="max-w-3xl mx-auto w-full px-4 py-8">
            <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        Редактировать заявку #{{ $submission->id }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Текущий статус: <span class="font-semibold">{{ $submission->status }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('participant.submissions.show', $submission) }}"
                        class="px-3 py-1.5 text-xs font-semibold rounded-full border border-slate-300 text-slate-700 hover:bg-slate-100"
                    >
                        ← Назад
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-full bg-slate-900 text-white hover:bg-slate-800 shadow-sm"
                        >
                            Выйти
                        </button>
                    </form>
                </div>
            </header>

            <main class="bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-5">
                <form method="POST" action="{{ route('participant.submissions.update', $submission) }}" class="space-y-4 text-sm">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                            Конкурс
                        </label>
                        <input
                            type="text"
                            value="{{ $submission->contest->title ?? '—' }}"
                            disabled
                            class="w-full rounded-xl border-slate-200 text-sm px-3 py-2 shadow-sm bg-slate-50 text-slate-500"
                        >
                        <p class="mt-1 text-[11px] text-slate-500">
                            Конкурс нельзя изменить после создания заявки.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                            Название работы *
                        </label>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $submission->title) }}"
                            required
                            class="w-full rounded-xl border-slate-200 text-sm px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        @error('title')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                            Описание
                        </label>
                        <textarea
                            name="description"
                            rows="4"
                            class="w-full rounded-xl border-slate-200 text-sm px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >{{ old('description', $submission->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if ($errors->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="flex items-center justify-between gap-2">
                        <button
                            type="submit"
                            class="inline-flex justify-center items-center px-4 py-2.5 text-sm font-semibold rounded-xl bg-emerald-600 text-white hover:bg-emerald-500 shadow-sm"
                        >
                            Сохранить изменения
                        </button>
                        <a
                            href="{{ route('participant.submissions.show', $submission) }}"
                            class="text-xs text-slate-500 hover:text-slate-700"
                        >
                            Отмена
                        </a>
                    </div>
                </form>
            </main>
        </div>
    </body>
</html>

