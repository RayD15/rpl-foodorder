@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')
    <div class="mb-6">
        <h1 class="font-display text-3xl font-normal text-ink-900">Pengaturan</h1>
        <p class="text-sm text-ink-500">Konfigurasi aplikasi.</p>
    </div>

    <div class="max-w-xl rounded-2xl border-2 border-ink-200 bg-cream-100 p-6 card-brutal-hover">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            @method('PUT')

            <div>
                <label class="flex flex-col gap-1.5">
                    <span class="text-sm font-bold text-ink-800">Nomor WhatsApp Admin</span>
                    <div class="flex items-center rounded-xl border-2 border-ink-200 bg-cream-50 px-4 py-3 text-sm shadow-sm transition focus-within:border-honey-500 focus-within:ring-2 focus-within:ring-honey-500/20 focus-within:shadow-md">
                        <span class="text-ink-400 font-bold mr-2">+62</span>
                        <span class="text-ink-200">|</span>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $whatsappNumber) }}"
                            placeholder="81234567890" required
                            class="w-full border-none bg-transparent outline-none text-sm px-2">
                    </div>
                </label>
                <p class="mt-2 text-xs text-ink-400">
                    Nomor internasional tanpa <code>+</code>, tanpa strip dan spasi. Contoh: <code>6281234567890</code>.
                    <br>Ketik angka setelah <code>62</code>, mulai dari <code>8</code>.
                </p>
                @error('whatsapp_number') <p class="mt-2 text-xs font-bold text-honey-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                class="mt-2 inline-flex items-center justify-center gap-2 rounded-2xl border border-ink-200 bg-honey-500 px-6 py-3.5 text-sm font-extrabold text-white shadow-sm transition hover:bg-honey-600 active:translate-x-0.5 active:translate-y-0.5 active:shadow-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                Simpan Pengaturan
            </button>
        </form>
    </div>
@endsection
