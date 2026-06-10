<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ringkasan Finansial') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg border-l-4 border-indigo-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sisa Saldo Saat Ini</p>
                    <p class="mt-2 text-3xl font-bold {{ $balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        Rp{{ number_format($balance, 0, ',', '.') }}
                    </p>
                </div>

                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Pemasukan</p>
                    <p class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">
                        + Rp{{ number_format($totalIncome, 0, ',', '.') }}
                    </p>
                </div>

                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg border-l-4 border-red-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Pengeluaran</p>
                    <p class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">
                        - Rp{{ number_format($totalExpense, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1 p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Proporsi Pengeluaran</h3>
                    
                    @if(count($chartLabels) > 0)
                        <div class="relative flex justify-center items-center h-64">
                            <canvas id="expenseChart"></canvas>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-64 text-sm text-gray-500 dark:text-gray-400">
                            Belum ada data pengeluaran untuk dianalisis.
                        </div>
                    @endif
                </div>

                <div class="lg:col-span-2 p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg flex flex-col justify-center items-center text-center">
                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Semangat Memonitor Keuangan! 🚀</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400 max-w-md">
                        Semua data dihitung secara real-time berdasarkan transaksi yang Anda catat. Gunakan menu navigasi di atas untuk memperbarui catatan Anda.
                    </p>
                </div>
            </div>

        </div>
    </div>

    @if(count($chartLabels) > 0)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('expenseChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode($chartLabels) !!},
                        datasets: [{
                            data: {!! json_encode($chartData) !!},
                            backgroundColor: [
                                '#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#6366F1', '#8B5CF6', '#EC4899'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#374151'
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endif
</x-app-layout>