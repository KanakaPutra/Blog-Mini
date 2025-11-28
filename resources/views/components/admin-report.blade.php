@props(['reports'])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-8">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Article Reports</h3>
            <p class="text-sm text-gray-500 mt-1">Manage reported articles.</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Article
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reports as $report)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $report->article->title ?? 'Deleted Article' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $report->user->name ?? 'Unknown User' }}</div>
                            <div class="text-xs text-gray-500">{{ $report->user->email ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $report->reason }}</div>
                            <div class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($report->details, 50) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $report->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($report->article)
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('articles.show', $report->article->id) }}" target="_blank"
                                        class="text-indigo-600 hover:text-indigo-900">View</a>

                                    @if($report->article->suspended)
                                        <form action="{{ route('superadmin.articles.unsuspend', $report->article->id) }}"
                                            method="POST" onsubmit="return confirm('Pulihkan artikel ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900">Unsuspend</button>
                                        </form>
                                    @else
                                        <form action="{{ route('superadmin.articles.suspend', $report->article->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Tangguhkan artikel ini? Artikel akan hilang dari dashboard publik.')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Suspend</button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400">Article Deleted</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No reports found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>