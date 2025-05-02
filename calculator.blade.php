<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('CGPA Calculator') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- CGPA Display -->
                @if(isset($gpa))
                    <div class="text-center mb-6">
                        <h1 class="text-white text-4xl font-bold">CGPA: {{ number_format($gpa, 2) }}</h1>
                    </div>

                    @if($gpa < 2)
                        <div class="text-center mb-6">
                            <p class="text-red-400 text-lg font-semibold">Your CGPA is below 2.00</p>
                            <a href="{{ route('grades.retakes') }}"
                               class="inline-block mt-3 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                View Retake Options
                            </a>
                        </div>
                    @endif
                @endif

                <!-- Add Grade Form -->
                <form method="POST" action="{{ route('grades.store') }}" class="flex flex-wrap gap-4 justify-center mb-8">
                    @csrf
                    <input type="text" name="subject" placeholder="Subject" required
                           class="border p-2 rounded-md w-48 dark:bg-gray-700 dark:text-white" />
                    <input type="number" step="0.01" min="0" max="4" name="grade"
                           placeholder="GPA (0.00 - 4.00)" required
                           class="border p-2 rounded-md w-48 dark:bg-gray-700 dark:text-white" />
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-md">
                        Add Grade
                    </button>
                </form>

                <!-- Grades Table -->
                <h3 class="text-lg font-bold mb-4 text-white">Your Grades</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse rounded-md overflow-hidden shadow-md">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
                                <th class="px-4 py-2 text-left">Subject</th>
                                <th class="px-4 py-2 text-left">Grade</th>
                                <th class="px-4 py-2 text-left">Retake Required?</th>
                                <th class="px-4 py-2 text-left">Edit</th>
                                <th class="px-4 py-2 text-left">Delete</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 text-black dark:text-white">
                            @foreach ($grades as $g)
                                <tr class="border-b border-gray-300 dark:border-gray-700">
                                    <td class="px-4 py-2">{{ $g->subject }}</td>
                                    <td class="px-4 py-2">{{ $g->grade }}</td>
                                    <td class="px-4 py-2">
                                        @if ($g->grade < 2)
                                            <span class="text-red-500 font-semibold">Yes</span>
                                        @else
                                            <span class="text-green-500 font-semibold">No</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <form method="POST" action="{{ route('grades.update', $g->id) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" step="0.01" min="0" max="4" name="grade"
                                                   value="{{ $g->grade }}"
                                                   class="w-20 px-2 py-1 rounded bg-gray-200 dark:bg-gray-700 dark:text-white text-sm">
                                            <button type="submit"
                                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-2">
                                        <form method="POST" action="{{ route('grades.destroy', $g->id) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this grade?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- CGPA Footer -->
                <h3 class="mt-6 font-bold text-lg text-white">CGPA: {{ number_format($gpa, 2) }}</h3>

            </div>
        </div>
    </div>
</x-app-layout>
