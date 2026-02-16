<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Вход – Платформа «Сбор работ на конкурс»</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                background: radial-gradient(circle at top left, #e0f2fe, #f5f3ff 40%, #fee2e2 80%);
            }
        </style>
    </head>
    <body class="min-h-screen flex items-center justify-center text-slate-900">
        <div class="max-w-md w-full px-4">
            <div class="bg-white/90 backdrop-blur shadow-xl rounded-2xl border border-slate-200 p-6">
                <h1 class="text-2xl font-bold text-slate-900 mb-1">
                    Вход в систему
                </h1>
                <p class="text-sm text-slate-600 mb-4">
                    Используйте email и пароль участника, жюри или администратора.
                </p>

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', 'participant@example.com') }}"
                            required
                            autofocus
                            class="w-full rounded-xl border-slate-200 text-sm px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="you@example.com"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                            Пароль
                        </label>
                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full rounded-xl border-slate-200 text-sm px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="••••••"
                            value="password"
                        >
                    </div>
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            Запомнить меня
                        </label>
                        <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500">
                            Регистрация
                        </a>
                    </div>
                    <button
                        type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2.5 text-sm font-semibold rounded-xl bg-slate-900 text-white hover:bg-slate-800 shadow-sm"
                    >
                        Войти
                    </button>
                </form>

                <div class="mt-4 text-[11px] text-slate-500">
                    Тестовые аккаунты:
                    <div class="mt-1 space-y-0.5">
                        <div>participant@example.com / password</div>
                        <div>jury@example.com / password</div>
                        <div>admin@example.com / password</div>
                    </div>
                </div>

                <div class="mt-4 text-center text-xs text-slate-500">
                    <a href="{{ url('/') }}" class="text-indigo-600 hover:text-indigo-500">
                        Перейти к интерфейсу конкурса
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>


