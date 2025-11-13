<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6 font-sans">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-serif font-semibold text-gray-800">Manage Users</h1>

            <!-- Filter Dropdown -->
            <form method="GET" action="{{ route('superadmin.users') }}">
                <select name="role"
                        onchange="this.form.submit()"
                        class="border-gray-300 text-gray-700 text-sm rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="" {{ request('role') == '' ? 'selected' : '' }}>All Roles</option>
                    <option value="0" {{ request('role') === '0' ? 'selected' : '' }}>User</option>
                    <option value="1" {{ request('role') == 1 ? 'selected' : '' }}>Admin</option>
                    <option value="2" {{ request('role') == 2 ? 'selected' : '' }}>Super Admin</option>
                </select>
            </form>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-md shadow-sm">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-md shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Table Container -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $index => $user)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-gray-700">{{ $users->firstItem() + $index }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $user->email }}</td>
                                <td class="px-6 py-4 font-medium">
                                    @if($user->is_admin == 2)
                                        <span class="text-red-600 font-semibold">Super Admin</span>
                                    @elseif($user->is_admin == 1)
                                        <span class="text-blue-600 font-semibold">Admin</span>
                                    @else
                                        <span class="text-gray-600">User</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->banned)
                                        <span class="text-red-600 font-semibold">Banned</span>
                                    @else
                                        <span class="text-green-600 font-semibold">Active</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->is_admin < 2)
                                        <form action="{{ $user->banned ? route('superadmin.users.unban', $user->id) : route('superadmin.users.ban', $user->id) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="px-3 py-1 rounded-md text-sm font-medium text-white transition
                                                           {{ $user->banned ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }}"
                                                    onclick="return confirm(`Yakin ingin {{ $user->banned ? 'unban' : 'ban' }} user ini?`)">
                                                {{ $user->banned ? 'Unban' : 'Ban' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-sm italic">Protected</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-left text-gray-500">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t bg-gray-50">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
