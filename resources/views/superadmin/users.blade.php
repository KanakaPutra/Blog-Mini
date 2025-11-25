<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 font-sans">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold font-serif text-gray-900 tracking-tight">Manage Users</h1>
                <p class="text-gray-500 mt-1 text-sm">View, search, and manage all registered users.</p>
            </div>

            <!-- Actions: Search & Filter -->
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <!-- Search Form -->
                <form method="GET" action="{{ route('superadmin.users') }}" class="relative w-full sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    @if(request('role'))
                        <input type="hidden" name="role" value="{{ request('role') }}">
                    @endif
                </form>

                <!-- Filter Dropdown -->
                <form method="GET" action="{{ route('superadmin.users') }}" class="w-full sm:w-auto">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <select name="role" onchange="this.form.submit()"
                        class="w-full sm:w-40 border-gray-300 text-gray-700 text-sm rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                        <option value="" {{ request('role') == '' ? 'selected' : '' }}>All Roles</option>
                        <option value="0" {{ request('role') === '0' ? 'selected' : '' }}>User</option>
                        <option value="1" {{ request('role') == 1 ? 'selected' : '' }}>Admin</option>
                        <option value="2" {{ request('role') == 2 ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm flex items-center animate-fade-in-down">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div
                class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm flex items-center animate-fade-in-down">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Desktop Table View (Hidden on Mobile) -->
        <div class="hidden md:block bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                User</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Role</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Joined</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out group">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <div
                                            class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->is_admin == 2)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Super Admin
                                        </span>
                                    @elseif($user->is_admin == 1)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Admin
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            User
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->banned)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-red-600 rounded-full"></span>
                                            Banned
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-green-600 rounded-full"></span>
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    @if($user->is_admin < 2)
                                                            <form
                                                                action="{{ $user->banned ? route('superadmin.users.unban', $user->id) : route('superadmin.users.ban', $user->id) }}"
                                                                method="POST" class="inline-block">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    onclick="return confirm('Are you sure you want to {{ $user->banned ? 'unban' : 'ban' }} {{ $user->name }}?')"
                                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200
                                                                                                                                        {{ $user->banned
                                        ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
                                        : 'bg-red-600 hover:bg-red-700 focus:ring-red-500' }}">
                                                                    @if($user->banned)
                                                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                        </svg>
                                                                        Unban
                                                                    @else
                                                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                                            </path>
                                                                        </svg>
                                                                        Ban
                                                                    @endif
                                                                </button>
                                                            </form>
                                    @else
                                        <span class="text-gray-400 italic text-xs flex items-center justify-end">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                </path>
                                            </svg>
                                            Protected
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                            </path>
                                        </svg>
                                        <p class="text-lg font-medium">No users found</p>
                                        <p class="text-sm text-gray-400">Try adjusting your search or filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View (Hidden on Desktop) -->
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @forelse ($users as $user)
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col gap-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div>
                            @if($user->is_admin == 2)
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">Super
                                    Admin</span>
                            @elseif($user->is_admin == 1)
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">Admin</span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">User</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm border-t border-gray-100 pt-3">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500">Status:</span>
                            @if($user->banned)
                                <span class="text-red-600 font-medium flex items-center"><span
                                        class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1.5"></span>Banned</span>
                            @else
                                <span class="text-green-600 font-medium flex items-center"><span
                                        class="w-1.5 h-1.5 bg-green-600 rounded-full mr-1.5"></span>Active</span>
                            @endif
                        </div>
                        <div class="text-gray-400 text-xs">
                            Joined {{ $user->created_at->format('M d, Y') }}
                        </div>
                    </div>

                    <div class="pt-2">
                        @if($user->is_admin < 2)
                            <form
                                action="{{ $user->banned ? route('superadmin.users.unban', $user->id) : route('superadmin.users.ban', $user->id) }}"
                                method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to {{ $user->banned ? 'unban' : 'ban' }} {{ $user->name }}?')"
                                    class="w-full flex justify-center items-center px-4 py-2 rounded-lg text-sm font-medium text-white transition-colors
                                                        {{ $user->banned ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
                                    {{ $user->banned ? 'Unban User' : 'Ban User' }}
                                </button>
                            </form>
                        @else
                            <button disabled
                                class="w-full px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed flex justify-center items-center">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Protected Account
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center">
                    <p class="text-gray-500">No users found.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>