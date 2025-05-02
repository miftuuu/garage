<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg">
        <h3 class="text-lg font-bold mb-4 text-black dark:text-white">All Student Grades</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse rounded-md overflow-hidden shadow bg-gray-800 text-white">
                <thead class="bg-gray-700 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left font-bold">Student Name</th>
                        <th class="px-6 py-3 text-left font-bold">Subject</th>
                        <th class="px-6 py-3 text-left font-bold">Grade</th>
                        <th class="px-6 py-3 text-left font-bold">Retake Required?</th>
                        <th class="px-6 py-3 text-left font-bold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grades as $grade)
                        <tr class="border-b border-gray-600">
                            <td class="px-6 py-4">{{ $grade->user->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4">{{ $grade->subject }}</td>
                            <td class="px-6 py-4">{{ $grade->grade }}</td>
                            <td class="px-6 py-4">
                                @if ($grade->grade < 2)
                                    <span class="text-red-400 font-semibold">Yes</span>
                                @else
                                    <span class="text-green-400 font-semibold">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($grade->grade < 2 && !$grade->is_approved)
                                    <form action="{{ route('grades.approve', $grade->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                            Approve Retake
                                        </button>
                                    </form>
                                @elseif($grade->is_approved)
                                    <span class="text-green-400 font-semibold">Approved</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
