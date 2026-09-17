<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — TamsisFood</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-cream-50 px-4">
    <div class="w-full mx-auto max-w-md rounded-2xl border-2 border-ink-200 bg-cream-100 p-6 card-brutal-hover">
        <div class="mb-6 text-center">
            <span class="mx-auto mb-3 grid h-14 w-14 place-items-center rounded-2xl bg-honey-500 text-2xl font-bold text-white shadow-sm">R</span>
            <h1 class="font-display text-3xl font-normal text-ink-900">Login Admin</h1>
            <p class="mt-1 text-sm text-ink-500">TamsisFood — Jakarta</p>
        </div>

        <form action="{{ route('admin.login') }}" method="POST" class="flex flex-col gap-3">
            @csrf
            <label class="flex flex-col gap-1.5">
                <span class="text-sm font-bold text-ink-800">Password</span>
                <input type="password" name="password" required autocomplete="current-password"
                    class="rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm outline-none transition focus:border-honey-500 focus:ring-2 focus:ring-honey-500/20">
            </label>
            <button type="submit"
                class="mt-2 rounded-2xl border border-ink-200 bg-honey-500 px-6 py-4 text-base font-extrabold text-white shadow-sm transition hover:bg-honey-600 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                Masuk
            </button>
        </form>

        <p class="mt-4 text-center text-xs text-ink-400">Bukan admin? <a href="{{ route('home') }}" class="font-bold text-honey-600 hover:text-honey-700">Kembali ke beranda</a></p>
    </div>
</body>
</html>
