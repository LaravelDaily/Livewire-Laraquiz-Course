<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h6 class="text-xl font-bold">My Results</h6>

                    <table class="mt-4 table w-full table-view">
                        <tbody class="bg-white">
                            @if(auth()->user()?->is_admin)
                                <tr class="w-28">
                                    <th class="border border-solid bg-slate-100 px-6 py-3 text-left text-sm font-semibold uppercase text-slate-600">User</th>
                                    <td class="border border-solid px-6 py-3">{{ $test->user->name ?? '' }} ({{ $test->user->email ?? '' }})</td>
                                </tr>
                            @endif
                            <tr class="w-28">
                                <th class="border border-solid bg-slate-100 px-6 py-3 text-left text-sm font-semibold uppercase text-slate-600">Date</th>
                                <td class="border border-solid px-6 py-3">{{ $test->created_at ?? '' }}</td>
                            </tr>
                            <tr class="w-28">
                                <th class="border border-solid bg-slate-100 px-6 py-3 text-left text-sm font-semibold uppercase text-slate-600">Result</th>
                                <td class="border border-solid px-6 py-3">
                                    {{ $test->result }} / {{ $total_questions }}
                                    @if($test->time_spent)
                                        (time: {{ intval($test->time_spent / 60) }}:{{ gmdate('s', $test->time_spent) }}
                                        minutes)
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
