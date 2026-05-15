<x-filament-panels::page>
    <div class="grid gap-6 md:grid-cols-3">
        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Prestasi Terverifikasi
            </div>

            <div class="mt-2 text-3xl font-bold">
                {{ $this->getTotalPrestasiTerverifikasi() }}
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Menunggu Verifikasi
            </div>

            <div class="mt-2 text-3xl font-bold">
                {{ $this->getTotalPrestasiDiajukan() }}
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Ditolak
            </div>

            <div class="mt-2 text-3xl font-bold">
                {{ $this->getTotalPrestasiDitolak() }}
            </div>
        </x-filament::section>
    </div>

    <x-filament::section>
        <x-slot name="heading">
            Link E-Portfolio Publik
        </x-slot>

        <div class="space-y-4">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Link ini bisa dibagikan untuk menampilkan daftar prestasi yang sudah diverifikasi admin.
            </p>

            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm dark:border-gray-700 dark:bg-gray-800">
                <a href="{{ $this->getPublicUrl() }}" target="_blank" class="text-primary-600 hover:underline">
                    {{ $this->getPublicUrl() }}
                </a>
            </div>

            <div class="flex flex-wrap gap-3">
                <x-filament::button
                    tag="a"
                    href="{{ $this->getPublicUrl() }}"
                    target="_blank"
                    icon="heroicon-o-eye"
                >
                    Lihat Portfolio
                </x-filament::button>

                <x-filament::button
                    tag="a"
                    href="{{ $this->getPrintUrl() }}"
                    target="_blank"
                    color="gray"
                    icon="heroicon-o-printer"
                >
                    Cetak Portfolio
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>