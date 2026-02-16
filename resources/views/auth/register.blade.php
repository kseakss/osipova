<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Регистрация – Платформа «Сбор работ на конкурс»</title>
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
                    Регистрация участника
                </h1>
                <p class="text-sm text-slate-600 mb-4">
                    После регистрации вы будете входить как участник и сможете создавать заявки.
                </p>

                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                            Имя
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            class="w-full rounded-xl border-slate-200 text-sm px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Иван Петров"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
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
                            placeholder="Минимум 6 символов"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">
                            Повторите пароль
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            class="w-full rounded-xl border-slate-200 text-sm px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>
                    <button
                        type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2.5 text-sm font-semibold rounded-xl bg-emerald-600 text-white hover:bg-emerald-500 shadow-sm"
                    >
                        Зарегистрироваться
                    </button>
                </form>

                <div class="mt-4 text-center text-xs text-slate-500">
                    Уже есть аккаунт?
                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500">
                        Войти
                    </a>
                </div>

                <div class="mt-3 text-center text-xs text-slate-500">
                    <a href="{{ url('/') }}" class="text-indigo-600 hover:text-indigo-500">
                        Перейти к интерфейсу конкурса
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>


