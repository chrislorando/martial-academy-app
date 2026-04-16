<x-filament-panels::page>
    <x-filament-panels::header>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ $this->getTitle() }}
        </h2>
    </x-filament-panels::header>

    <x-filament-panels::content>
        <div class="space-y-6">
            <!-- Date Selection -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Select Date
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Choose the date for marking attendance
                        </p>
                    </div>
                    <x-filament::input.date-time-picker
                        wire:model.live="date"
                        :display-format="__('Y-m-d')"
                        :without-time="true"
                        :native="false"
                    />
                </div>
            </div>

            <!-- Attendance Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                {{ $this->table }}
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">Present</p>
                            <p class="text-2xl font-bold text-green-900 dark:text-green-100" id="present-count">-</p>
                        </div>
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-red-800 dark:text-red-200">Absent</p>
                            <p class="text-2xl font-bold text-red-900 dark:text-red-100" id="absent-count">-</p>
                        </div>
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Late</p>
                            <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100" id="late-count">-</p>
                        </div>
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </x-filament-panels::content>

    @script
    <script>
        function updateAttendanceStats() {
            // Count attendance statuses from the table
            const presentCount = document.querySelectorAll('select[value="Present"]').length;
            const absentCount = document.querySelectorAll('select[value="Absent"]').length;
            const lateCount = document.querySelectorAll('select[value="Late"]').length;

            document.getElementById('present-count').textContent = presentCount;
            document.getElementById('absent-count').textContent = absentCount;
            document.getElementById('late-count').textContent = lateCount;
        }

        // Update stats when page loads and when select changes
        document.addEventListener('DOMContentLoaded', updateAttendanceStats);
        document.addEventListener('change', updateAttendanceStats);
        document.addEventListener('livewire:update', updateAttendanceStats);
    </script>
    @endscript
</x-filament-panels::page>
