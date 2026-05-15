<x-filament-panels::page>
    <form wire:submit.prevent class="space-y-6">
        {{ $this->form }}
    </form>

    <div class="grid gap-6 md:grid-cols-4">
        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Prestasi</div>
            <div class="mt-2 text-3xl font-bold">{{ $this->getTotalPrestasi() }}</div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">Diajukan</div>
            <div class="mt-2 text-3xl font-bold text-warning-600">{{ $this->getTotalDiajukan() }}</div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">Diverifikasi</div>
            <div class="mt-2 text-3xl font-bold text-success-600">{{ $this->getTotalDiverifikasi() }}</div>
        </x-filament::section>

        <x-filament::section>
            <div class="text-sm text-gray-500 dark:text-gray-400">Ditolak</div>
            <div class="mt-2 text-3xl font-bold text-danger-600">{{ $this->getTotalDitolak() }}</div>
        </x-filament::section>
    </div>

    <x-filament::section>
        <x-slot name="heading">
            Aksi Laporan
        </x-slot>

        <div class="flex flex-wrap gap-3">
            <x-filament::button
                tag="a"
                href="{{ $this->getCetakUrl() }}"
                target="_blank"
                icon="heroicon-o-printer"
            >
                Cetak Laporan
            </x-filament::button>

            <x-filament::button
                tag="a"
                href="{{ $this->getExportUrl() }}"
                target="_blank"
                color="success"
                icon="heroicon-o-arrow-down-tray"
            >
                Export CSV
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-panels::page>