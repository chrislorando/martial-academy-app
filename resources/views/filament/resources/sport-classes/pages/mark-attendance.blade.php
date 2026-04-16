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
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Member Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Level
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Subscription
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Attendance
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($this->getMembers() as $member)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $member->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $member->level ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->activeSubscription)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ $member->activeSubscription->subscription_type }}
                                </span>
                                @else
                                <span class="text-sm text-gray-400 dark:text-gray-500">No active subscription</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select
                                    wire:change="markAttendance({{ $member->id }}, $event.target.value)"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="">Not Marked</option>
                                    @foreach(\App\Enums\AttendanceStatus::getOptions() as $value => $label)
                                    <option value="{{ $value }}" {{ $member->attendance_status === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No members enrolled in this class
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">Present</p>
                            <p class="text-2xl font-bold text-green-900 dark:text-green-100">
                                {{ $this->getAttendanceStats()['present'] }}
                            </p>
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
                            <p class="text-2xl font-bold text-red-900 dark:text-red-100">
                                {{ $this->getAttendanceStats()['absent'] }}
                            </p>
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
                            <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">
                                {{ $this->getAttendanceStats()['late'] }}
                            </p>
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
        Livewire.on('refresh-attendance-stats', () => {
            Livewire.dispatch('$refresh');
        });
    </script>
    @endscript
</x-filament-panels::page>
