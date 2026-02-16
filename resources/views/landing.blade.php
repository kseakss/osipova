<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Платформа «Сбор работ на конкурс»</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                background: radial-gradient(circle at top left, #e0f2fe, #f5f3ff 40%, #fee2e2 80%);
            }
        </style>
    </head>
    <body class="min-h-screen flex flex-col text-slate-900">
        <div class="max-w-5xl mx-auto w-full px-4 py-8">
            <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                        Платформа «Сбор работ на конкурс»
                    </h1>
                    <p class="mt-2 text-sm text-slate-600 max-w-xl">
                        Сервис для приёма, проверки и оценки конкурсных работ. Участники загружают свои проекты,
                        жюри выставляет оценки и запрашивает доработки, администратор управляет конкурсами и ролями.
                    </p>
                </div>
                <nav class="flex flex-wrap items-center gap-2">
                    @auth
                        <a href="{{ route('home') }}" class="px-4 py-2 text-xs font-semibold rounded-full bg-slate-900 text-white hover:bg-slate-800 shadow-sm">
                            В личный кабинет
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="px-3 py-1.5 text-xs font-medium rounded-full border border-slate-300 text-slate-700 hover:bg-slate-100"
                            >
                                Выйти
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-semibold rounded-full bg-slate-900 text-white hover:bg-slate-800 shadow-sm">
                            Войти
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-xs font-semibold rounded-full bg-white/80 border border-slate-200 text-slate-800 hover:bg-slate-50">
                            Регистрация
                        </a>
                    @endauth
                </nav>
            </header>

            <main class="grid gap-6 md:grid-cols-3">
                <section class="md:col-span-2 bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-5">
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">
                        Как это работает
                    </h2>
                    <ol class="space-y-3 text-sm text-slate-700">
                        <li>
                            <span class="font-semibold text-slate-900">Участник</span> регистрируется, выбирает активный
                            конкурс и создаёт заявку, прикрепляя до трёх файлов с работой.
                        </li>
                        <li>
                            Файлы автоматически проверяются на тип, размер и имя. Корректные помечаются как
                            <span class="font-mono text-emerald-600">scanned</span>, остальные отклоняются.
                        </li>
                        <li>
                            После отправки заявки жюри просматривает работы, оставляет комментарии и меняет статусы
                            (<span class="font-mono">needs_fix</span>, <span class="font-mono">accepted</span>, <span class="font-mono">rejected</span>).
                        </li>
                        <li>
                            Администратор создаёт конкурсы, управляет сроками и ролями пользователей.
                        </li>
                    </ol>
                </section>

                <section class="space-y-4">
                    <div class="bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-4">
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">
                            Быстрый старт
                        </h3>
                        <ul class="space-y-2 text-sm text-slate-700">
                            <li>
                                <span class="font-semibold">Шаг 1.</span> Зарегистрируйтесь как участник.
                            </li>
                            <li>
                                <span class="font-semibold">Шаг 2.</span> Войдите в личный кабинет.
                            </li>
                            <li>
                                <span class="font-semibold">Шаг 3.</span> Перейдите в интерфейс конкурса и создайте свою первую заявку.
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white/90 backdrop-blur shadow-sm rounded-2xl border border-slate-200 p-4 text-sm text-slate-700">
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">
                            Роли в системе
                        </h3>
                        <ul class="space-y-1.5">
                            <li><span class="font-semibold">Участник</span> – создаёт и редактирует свои заявки, загружает файлы.</li>
                            <li><span class="font-semibold">Жюри</span> – видит все заявки, комментирует и меняет статусы.</li>
                            <li><span class="font-semibold">Администратор</span> – управляет конкурсами и пользователями.</li>
                        </ul>
                    </div>
                </section>
            </main>

            <footer class="mt-8 text-[11px] text-slate-500">
                Для тестирования доступны аккаунты: <span class="font-mono">participant@example.com</span>,
                <span class="font-mono">jury@example.com</span>, <span class="font-mono">admin@example.com</span>, пароль
                <span class="font-mono">password</span>.
            </footer>
        </div>
    </body>
</html>


