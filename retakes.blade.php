<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Retake Options') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white dark:bg-gray-800 text-black dark:text-white rounded-lg shadow">
        <h3 class="text-lg font-bold mb-4">Subjects Recommended for Retake</h3>

        @if ($retakeSubjects->isEmpty())
            <p class="text-green-500 font-semibold">No subjects need to be retaken! 🎉</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse rounded-md overflow-hidden shadow-md">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white">
                            <th class="px-4 py-2 text-left">Subject</th>
                            <th class="px-4 py-2 text-left">Grade</th>
                            @if(auth()->user()->is_admin)
                                <th class="px-4 py-2 text-left">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 text-black dark:text-white">
                        @foreach ($retakeSubjects as $subject)
                            <tr class="border-b border-gray-300 dark:border-gray-700">
                                <td class="px-4 py-2">{{ $subject->subject }}</td>
                                <td class="px-4 py-2 text-red-500 font-semibold">{{ $subject->grade }}</td>

                                @if(auth()->user()->is_admin)
                                    <td class="px-4 py-2">
                                        <form method="POST" action="{{ route('grades.approve', $subject->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                                Take Retake
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
