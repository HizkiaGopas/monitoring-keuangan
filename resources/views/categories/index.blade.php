<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 stream-lining">
            {{ __('Manajemen Kategori Keuangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-900 dark:text-green-300 dark:border-green-800" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <section class="max-w-xl">
                        <header>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Tambah Kategori') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Buat kategori baru untuk memisahkan pos alokasi dana Anda.') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('categories.store') }}" class="mt-6 space-y-6">
                            @csrf

                            <div>
                                <x-input-label for="name" :value="__('Nama Kategori')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="Misal: Makan Siang, Transportasi" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="type" :value="__('Tipe Transaksi')" />
                                <select id="type" name="type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="expense">Pengeluaran (Expense)</option>
                                    <option value="income">Pemasukan (Income)</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('type')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>

                <div class="md:col-span-2 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Daftar Kategori Anda') }}
                        </h3>
                    </header>

                    <div class="relative overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nama Kategori</th>
                                    <th scope="col" class="px-6 py-3">Tipe</th>
                                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $category->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($category->type === 'expense')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Pengeluaran
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Pemasukan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Transaksi terkait akan kehilangan kategorinya.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline font-medium">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            Belum ada kategori. Silakan tambahkan melalui form di sebelah kiri.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>