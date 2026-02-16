<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Кабинет участника – Платформа «Сбор работ на конкурс»</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                background: radial-gradient(circle at top left, #e0f2fe, #f5f3ff 40%, #fee2e2 80%);
            }
        </style>
    </head>
    <body class="min-h-screen flex flex-col text-slate-900">
        <div class="max-w-6xl mx-auto w-full px-4 py-8">
            <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        Кабинет участника
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">
                        {{ auth()->user()->name }}, здесь вы можете создавать заявки и отслеживать их статусы.
                    </p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-full bg-slate-900 text-white hover:bg-slate-800 shadow-sm"
                    >
                        Выйти
                    </button>
                </form>
            </header>

            <main class="grid gap-5 md:grid-cols-3">
                <section class="md:col-span-2 bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm font-semibold text-slate-900">
                            Мои заявки
                        </h2>
                        <a
                            href="{{ route('participant.submissions.create') }}"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-full bg-emerald-600 text-white hover:bg-emerald-500 shadow-sm"
                        >
                            + Создать заявку
                        </a>
                    </div>
                    @if ($submissions->isEmpty())
                        <p class="text-sm text-slate-500">
                            У вас пока нет заявок. <a href="{{ route('participant.submissions.create') }}" class="text-indigo-600 hover:text-indigo-500">Создайте первую заявку</a>, выбрав конкурс.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-xs border border-slate-200 rounded-xl overflow-hidden">
                                <thead class="bg-slate-50 text-slate-600">
                                    <tr>
                                        <th class="px-3 py-2 text-left font-semibold">ID</th>
                                        <th class="px-3 py-2 text-left font-semibold">Конкурс</th>
                                        <th class="px-3 py-2 text-left font-semibold">Название</th>
                                        <th class="px-3 py-2 text-left font-semibold">Статус</th>
                                        <th class="px-3 py-2 text-left font-semibold">Создана</th>
                                        <th class="px-3 py-2 text-left font-semibold"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($submissions as $submission)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-3 py-2 font-mono text-slate-700">
                                                {{ $submission->id }}
                                            </td>
                                            <td class="px-3 py-2 text-slate-700">
                                                {{ $submission->contest->title ?? '—' }}
                                            </td>
                                            <td class="px-3 py-2 text-slate-700">
                                                {{ $submission->title }}
                                            </td>
                                            <td class="px-3 py-2">
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
                                            </td>
                                            <td class="px-3 py-2 text-slate-500">
                                                {{ optional($submission->created_at)->format('d.m.Y H:i') }}
                                            </td>
                                            <td class="px-3 py-2">
                                                <a
                                                    href="{{ route('participant.submissions.show', $submission) }}"
                                                    class="text-xs text-indigo-600 hover:text-indigo-500 font-semibold"
                                                >
                                                    Открыть →
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>

                <section class="bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-5">
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">
                        Доступные конкурсы
                    </h2>
                    @if ($contests->isEmpty())
                        <p class="text-sm text-slate-500">
                            Сейчас нет активных конкурсов. Обратитесь к администратору.
                        </p>
                    @else
                        <ul class="space-y-3 text-sm text-slate-700">
                            @foreach ($contests as $contest)
                                <li class="border border-slate-200 rounded-xl px-3 py-2 bg-slate-50/60">
                                    <div class="font-semibold text-slate-900">
                                        {{ $contest->title }}
                                    </div>
                                    <div class="text-xs text-slate-600 mt-0.5">
                                        Дедлайн: {{ optional($contest->deadline_at)->format('d.m.Y H:i') }}
                                    </div>
                                    @if ($contest->description)
                                        <div class="mt-1 text-xs text-slate-600">
                                            {{ $contest->description }}
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <p class="mt-3 text-[11px] text-slate-500">
                        Создание и редактирование заявок выполняется через интерфейс API/форм, реализованный отдельно.
                    </p>
                </section>
            </main>
        </div>
    </body>
</html>


