<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pencatatan Transaksi Keuangan') }}
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
                                {{ __('Catat Transaksi Baru') }}
                            </h3>
                        </header>

                        <form method="post" action="{{ route('transactions.store') }}" class="mt-6 space-y-6">
                            @csrf

                            <div>
                                <x-input-label for="date" :value="__('Tanggal')" />
                                <x-text-input id="date" name="date" type="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('date')" />
                            </div>

                            <div>
                                <x-input-label for="category_id" :value="__('Kategori')" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">-- Tanpa Kategori (Uncategorized) --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->type === 'expense' ? 'Pengeluaran' : 'Pemasukan' }})</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                            </div>

                            <div>
                                <x-input-label for="amount" :value="__('Nominal (Rp)')" />
                                <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" placeholder="Contoh: 25000" required />
                                <x-input-error class="mt-2" :messages="$errors->get('amount')" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Keterangan / Catatan')" />
                                <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder="Misal: Nasi goreng + es teh manis"></textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Simpan Transaksi') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>

                <div class="md:col-span-2 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <header class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Riwayat Transaksi') }}
                        </h3>
                    </header>

                    <div class="relative overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Tanggal</th>
                                    <th scope="col" class="px-4 py-3">Kategori</th>
                                    <th scope="col" class="px-4 py-3">Keterangan</th>
                                    <th scope="col" class="px-4 py-3">Nominal</th>
                                    <th scope="col" class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-4 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-4">
                                            @if($transaction->category_id && \App\Models\Category::find($transaction->category_id))
                                                <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    {{ \App\Models\Category::find($transaction->category_id)->name }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-medium rounded bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                                    Tanpa Kategori
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 max-w-xs truncate text-gray-700 dark:text-gray-300">
                                            {{ $transaction->description ?? '-' }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap font-semibold {{ $transaction->type === 'expense' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                            {{ $transaction->type === 'expense' ? '-' : '+' }} Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('Hapus catatan transaksi ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline font-medium text-xs">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                            Belum ada catatan transaksi keuangan.
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