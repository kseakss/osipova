<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Управление конкурсами – Админ‑панель</title>
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
                        Управление конкурсами
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Здесь администратор создаёт и редактирует конкурсы для приёма заявок.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="px-3 py-1.5 text-xs font-semibold rounded-full border border-slate-300 text-slate-700 hover:bg-slate-100"
                    >
                        ← В админ‑панель
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
                <section class="md:col-span-2 bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-5">
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">
                        Список конкурсов
                    </h2>
                    @if ($contests->isEmpty())
                        <p class="text-sm text-slate-500">
                            Конкурсов пока нет. Создайте первый конкурс с помощью формы справа.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-xs border border-slate-200 rounded-xl overflow-hidden">
                                <thead class="bg-slate-50 text-slate-600">
                                    <tr>
                                        <th class="px-3 py-2 text-left font-semibold">ID</th>
                                        <th class="px-3 py-2 text-left font-semibold">Название</th>
                                        <th class="px-3 py-2 text-left font-semibold">Активен</th>
                                        <th class="px-3 py-2 text-left font-semibold">Дедлайн</th>
                                        <th class="px-3 py-2 text-left font-semibold"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($contests as $contest)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-3 py-2 font-mono text-slate-700">
                                                {{ $contest->id }}
                                            </td>
                                            <td class="px-3 py-2 text-slate-700">
                                                {{ $contest->title }}
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold
                                                    {{ $contest->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-50 text-slate-500' }}
                                                ">
                                                    {{ $contest->is_active ? 'активен' : 'неактивен' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-slate-500">
                                                {{ optional($contest->deadline_at)->format('d.m.Y H:i') }}
                                            </td>
                                            <td class="px-3 py-2 text-right space-x-1">
                                                <a
                                                    href="{{ route('admin.contests.edit', $contest) }}"
                                                    class="inline-flex items-center px-2 py-1 text-[11px] font-semibold rounded-full border border-slate-300 text-slate-700 hover:bg-slate-100"
                                                >
                                                    Редактировать
                                                </a>
                                                <form
                                                    action="{{ route('admin.contests.destroy', $contest) }}"
                                                    method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Удалить конкурс и все связанные заявки?');"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center px-2 py-1 text-[11px] font-semibold rounded-full border border-rose-200 text-rose-700 hover:bg-rose-50"
                                                    >
                                                        Удалить
                                                    </button>
                                                </form>
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
                        Новый конкурс
                    </h2>
                    <form method="POST" action="{{ route('admin.contests.store') }}" class="space-y-3 text-sm">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                                Название
                            </label>
                            <input
                                type="text"
                                name="title"
                                value="{{ old('title') }}"
                                required
                                class="w-full rounded-xl border-slate-200 text-sm px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                                Описание
                            </label>
                            <textarea
                                name="description"
                                rows="3"
                                class="w-full rounded-xl border-slate-200 text-sm px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('description') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                                Дедлайн
                            </label>
                            <input
                                type="datetime-local"
                                name="deadline_at"
                                value="{{ old('deadline_at') }}"
                                required
                                class="w-full rounded-xl border-slate-200 text-sm px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>
                        <div class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <span class="text-xs text-slate-700">Сделать конкурс активным</span>
                        </div>

                        @if ($errors->any())
                            <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[11px] text-rose-700">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <button
                            type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2.5 text-sm font-semibold rounded-xl bg-emerald-600 text-white hover:bg-emerald-500 shadow-sm"
                        >
                            Создать конкурс
                        </button>
                    </form>
                </section>
            </main>
        </div>
    </body>
</html>


