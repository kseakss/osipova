<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Заявка #{{ $submission->id }} – Панель жюри</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                background: radial-gradient(circle at top left, #e0f2fe, #f5f3ff 40%, #fee2e2 80%);
            }
        </style>
    </head>
    <body class="min-h-screen flex flex-col text-slate-900">
        <div class="max-w-5xl mx-auto w-full px-4 py-8">
            <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        Заявка #{{ $submission->id }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Конкурс: {{ $submission->contest->title ?? '—' }} | Участник: {{ $submission->user->name }} ({{ $submission->user->email }})
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('jury.dashboard') }}"
                        class="px-3 py-1.5 text-xs font-semibold rounded-full border border-slate-300 text-slate-700 hover:bg-slate-100"
                    >
                        ← К списку заявок
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

            @if (session('status'))
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <main class="grid gap-5 md:grid-cols-3">
                <section class="md:col-span-2 space-y-5">
                    <div class="bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-sm font-semibold text-slate-900">
                                Информация о заявке
                            </h2>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold
                                @class([
                                    'bg-yellow-50 text-yellow-700' => $submission->status === 'draft' || $submission->status === 'needs_fix',
                                    'bg-blue-50 text-blue-700' => $submission->status === 'submitted',
                                    'bg-emerald-50 text-emerald-700' => $submission->status === 'accepted',
                                    'bg-rose-50 text-rose-700' => $submission->status === 'rejected',
                                ])
                            ">
                                {{ $submission->status }}
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-slate-700">
                            <div>
                                <span class="font-semibold">Название:</span> {{ $submission->title }}
                            </div>
                            @if ($submission->description)
                                <div>
                                    <span class="font-semibold">Описание:</span>
                                    <p class="mt-1 text-slate-600">{{ $submission->description }}</p>
                                </div>
                            @endif
                            <div class="text-xs text-slate-500">
                                Создана: {{ $submission->created_at->format('d.m.Y H:i') }}
                                @if ($submission->updated_at != $submission->created_at)
                                    <br>Обновлена: {{ $submission->updated_at->format('d.m.Y H:i') }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-5">
                        <h2 class="text-sm font-semibold text-slate-900 mb-3">
                            Файлы ({{ $submission->attachments->count() }})
                        </h2>
                        @if ($submission->attachments->isEmpty())
                            <p class="text-sm text-slate-500">
                                Файлы не загружены.
                            </p>
                        @else
                            <div class="space-y-2">
                                @foreach ($submission->attachments as $attachment)
                                    <div class="flex items-center justify-between p-2 border border-slate-200 rounded-lg">
                                        <div class="flex-1">
                                            <div class="text-xs font-medium text-slate-700">
                                                {{ $attachment->original_name }}
                                            </div>
                                            <div class="text-[11px] text-slate-500">
                                                {{ number_format($attachment->size / 1024, 2) }} KB
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold ml-2
                                                    @if($attachment->status === 'scanned') bg-emerald-50 text-emerald-700
                                                    @elseif($attachment->status === 'rejected') bg-rose-50 text-rose-700
                                                    @else bg-yellow-50 text-yellow-700
                                                    @endif
                                                ">
                                                    {{ $attachment->status }}
                                                </span>
                                            </div>
                                            @if ($attachment->rejection_reason)
                                                <div class="text-[11px] text-rose-600 mt-1">
                                                    Причина отклонения: {{ $attachment->rejection_reason }}
                                                </div>
                                            @endif
                                        </div>
                                        @if ($attachment->status === 'scanned')
                                            <a
                                                href="{{ route('jury.submissions.downloadAttachment', $attachment) }}"
                                                class="px-2 py-1 text-[11px] font-semibold rounded border border-slate-300 text-slate-700 hover:bg-slate-100"
                                                target="_blank"
                                            >
                                                Скачать
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-5">
                        <h2 class="text-sm font-semibold text-slate-900 mb-3">
                            Комментарии ({{ $submission->comments->count() }})
                        </h2>
                        <div class="space-y-3 mb-3">
                            @forelse ($submission->comments as $comment)
                                <div class="border-l-2 {{ $comment->user->isJury() || $comment->user->isAdmin() ? 'border-blue-200' : 'border-indigo-200' }} pl-3 py-1">
                                    <div class="text-xs font-semibold text-slate-700">
                                        {{ $comment->user->name }}
                                        @if ($comment->user->isJury() || $comment->user->isAdmin())
                                            <span class="text-[10px] text-blue-600 font-normal ml-1">(жюри)</span>
                                        @endif
                                        <span class="text-[11px] text-slate-500 font-normal ml-2">
                                            {{ $comment->created_at->format('d.m.Y H:i') }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-slate-600 mt-1">
                                        {{ $comment->body }}
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">
                                    Комментариев пока нет.
                                </p>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('jury.submissions.addComment', $submission) }}" class="border-t border-slate-200 pt-3">
                            @csrf
                            <textarea
                                name="body"
                                rows="2"
                                required
                                class="w-full rounded-xl border-slate-200 text-sm px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mb-2"
                                placeholder="Добавить комментарий..."
                            ></textarea>
                            <button
                                type="submit"
                                class="px-3 py-1.5 text-xs font-semibold rounded-full bg-indigo-600 text-white hover:bg-indigo-500"
                            >
                                Отправить комментарий
                            </button>
                        </form>
                    </div>
                </section>

                <section class="space-y-5">
                    <div class="bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-5">
                        <h2 class="text-sm font-semibold text-slate-900 mb-3">
                            Изменить статус
                        </h2>
                        @php
                            $allowedTransitions = [
                                'draft' => ['submitted'],
                                'submitted' => ['accepted', 'rejected', 'needs_fix'],
                                'needs_fix' => ['submitted', 'rejected'],
                                'accepted' => [],
                                'rejected' => [],
                            ];
                            $currentAllowed = $allowedTransitions[$submission->status] ?? [];
                        @endphp

                        @if (empty($currentAllowed))
                            <p class="text-xs text-slate-500">
                                Статус нельзя изменить ({{ $submission->status }}).
                            </p>
                        @else
                            <form method="POST" action="{{ route('jury.submissions.changeStatus', $submission) }}" class="space-y-2">
                                @csrf
                                @method('PATCH')
                                <select
                                    name="status"
                                    class="w-full rounded-xl border-slate-200 text-xs px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">Выберите новый статус</option>
                                    @foreach ($currentAllowed as $status)
                                        <option value="{{ $status }}">
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                                <button
                                    type="submit"
                                    class="w-full px-3 py-2 text-xs font-semibold rounded-xl bg-indigo-600 text-white hover:bg-indigo-500"
                                >
                                    Изменить статус
                                </button>
                            </form>
                            <p class="mt-2 text-[11px] text-slate-500">
                                Доступные переходы из {{ $submission->status }}:
                                @foreach ($currentAllowed as $status)
                                    <span class="font-mono">{{ $status }}</span>@if (!$loop->last), @endif
                                @endforeach
                            </p>
                        @endif
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>

