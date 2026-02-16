<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Личный кабинет – Платформа «Сбор работ на конкурс»</title>
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
                        Личный кабинет
                    </h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Добро пожаловать, {{ auth()->user()->name }}.
                        Ваша роль: <span class="font-semibold">{{ auth()->user()->role }}</span>.
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

            <main class="grid gap-4 md:grid-cols-3">
                <section class="md:col-span-2 bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-5">
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">
                        Работа с конкурсом
                    </h2>
                    @if (auth()->user()->isParticipant())
                        <p class="text-sm text-slate-600 mb-3">
                            Как участник вы можете создавать заявки, загружать файлы и отправлять работы на проверку.
                        </p>
                        <a
                            href="{{ url('/') }}"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full bg-indigo-600 text-white hover:bg-indigo-500"
                        >
                            Перейти к интерфейсу участника
                        </a>
                    @elseif (auth()->user()->isJury())
                        <p class="text-sm text-slate-600 mb-3">
                            Как жюри вы можете просматривать все заявки, оставлять комментарии и менять статусы.
                        </p>
                        <a
                            href="{{ url('/') }}"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full bg-indigo-600 text-white hover:bg-indigo-500"
                        >
                            Перейти к панели жюри
                        </a>
                    @elseif (auth()->user()->isAdmin())
                        <p class="text-sm text-slate-600 mb-3">
                            Как администратор вы управляете конкурсами и ролями пользователей.
                        </p>
                        <a
                            href="{{ url('/') }}"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full bg-indigo-600 text-white hover:bg-indigo-500"
                        >
                            Перейти к админ‑панели
                        </a>
                    @else
                        <p class="text-sm text-slate-600">
                            Ваша роль не имеет специальных прав в интерфейсе. Обратитесь к администратору.
                        </p>
                    @endif
                </section>

                <section class="bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-5">
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">
                        Быстрые ссылки
                    </h2>
                    <ul class="space-y-2 text-sm text-slate-700">
                        <li>
                            <a href="{{ url('/') }}" class="text-indigo-600 hover:text-indigo-500">
                                Интерфейс конкурса
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500">
                                Страница входа
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500">
                                Регистрация нового участника
                            </a>
                        </li>
                    </ul>
                </section>
            </main>
        </div>
    </body>
</html>


