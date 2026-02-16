<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Управление пользователями – Админ‑панель</title>
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
                        Управление пользователями
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Здесь администратор управляет пользователями и их ролями в системе.
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

            <main class="bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-5">
                <h2 class="text-sm font-semibold text-slate-900 mb-3">
                    Список пользователей
                </h2>
                @if ($users->isEmpty())
                    <p class="text-sm text-slate-500">
                        Пользователей пока нет.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs border border-slate-200 rounded-xl overflow-hidden">
                            <thead class="bg-slate-50 text-slate-600">
                                <tr>
                                    <th class="px-3 py-2 text-left font-semibold">ID</th>
                                    <th class="px-3 py-2 text-left font-semibold">Имя</th>
                                    <th class="px-3 py-2 text-left font-semibold">Email</th>
                                    <th class="px-3 py-2 text-left font-semibold">Текущая роль</th>
                                    <th class="px-3 py-2 text-left font-semibold">Изменить роль</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($users as $user)
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
                                        <td class="px-3 py-2">
                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.updateRole', $user) }}"
                                                class="inline-flex items-center gap-2"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <select
                                                    name="role"
                                                    class="text-xs rounded-lg border-slate-200 px-2 py-1 focus:border-indigo-500 focus:ring-indigo-500"
                                                    onchange="this.form.submit()"
                                                >
                                                    <option value="participant" {{ $user->role === 'participant' ? 'selected' : '' }}>
                                                        participant
                                                    </option>
                                                    <option value="jury" {{ $user->role === 'jury' ? 'selected' : '' }}>
                                                        jury
                                                    </option>
                                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>
                                                        admin
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </main>
        </div>
    </body>
</html>

