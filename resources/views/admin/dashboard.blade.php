<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Админ‑панель – Платформа «Сбор работ на конкурс»</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                background: radial-gradient(circle at top left, #e0f2fe, #f5f3ff 40%, #fee2e2 80%);
            }
        </style>
    </head>
    <body class="min-h-screen flex flex-col text-slate-900">
        <div class="max-w-7xl mx-auto w-full px-4 py-8">
            <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        Админ‑панель
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">
                        {{ auth()->user()->name }}, здесь вы управляете конкурсами и пользователями.
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
                            Конкурсы
                        </h2>
                        <a
                            href="{{ route('admin.contests.index') }}"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-full bg-emerald-600 text-white hover:bg-emerald-500 shadow-sm"
                        >
                            Управление конкурсами →
                        </a>
                    </div>
                    @if ($contests->isEmpty())
                        <p class="text-sm text-slate-500">
                            Пока нет ни одного конкурса. <a href="{{ route('admin.contests.index') }}" class="text-indigo-600 hover:text-indigo-500">Создайте первый конкурс</a>.
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
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($contests->take(5) as $contest)
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($contests->count() > 5)
                            <p class="mt-3 text-[11px] text-slate-500">
                                Показано 5 из {{ $contests->count() }} конкурсов. <a href="{{ route('admin.contests.index') }}" class="text-indigo-600 hover:text-indigo-500">Показать все</a>.
                            </p>
                        @endif
                    @endif
                </section>

                <section class="bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm font-semibold text-slate-900">
                            Пользователи и роли
                        </h2>
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-full bg-indigo-600 text-white hover:bg-indigo-500 shadow-sm"
                        >
                            Управление пользователями →
                        </a>
                    </div>
                    <div class="overflow-x-auto max-h-[340px]">
                        <table class="min-w-full text-xs border border-slate-200 rounded-xl overflow-hidden">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr>
                                    <th class="px-3 py-2 text-left font-semibold">ID</th>
                                    <th class="px-3 py-2 text-left font-semibold">Имя</th>
                                    <th class="px-3 py-2 text-left font-semibold">Email</th>
                                    <th class="px-3 py-2 text-left font-semibold">Роль</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($users->take(5) as $user)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-3 py-2 font-mono text-slate-700">
                                            {{ $user->id }}
                                        </td>
                                        <td class="px-3 py-2 text-slate-700">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-3 py-2 text-slate-700">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-3 py-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold
                                                @if($user->role === 'admin') bg-purple-50 text-purple-700
                                                @elseif($user->role === 'jury') bg-blue-50 text-blue-700
                                                @else bg-slate-50 text-slate-700
                                                @endif
                                            ">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($users->count() > 5)
                        <p class="mt-3 text-[11px] text-slate-500">
                            Показано 5 из {{ $users->count() }} пользователей. <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-500">Показать все</a>.
                        </p>
                    @endif
                </section>
            </main>
        </div>
    </body>
</html>


