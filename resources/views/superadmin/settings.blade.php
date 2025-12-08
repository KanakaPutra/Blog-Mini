<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-6 space-y-8">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="animate__animated animate__fadeInDown">
                <h1 class="text-3xl font-bold font-serif text-gray-900 tracking-tight">Super Admin Settings</h1>
                <p class="text-gray-500 mt-1 text-lg">Manage application settings, users, and content categories.</p>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Total Users -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl hover:shadow-md transition-shadow duration-300 border border-gray-100 animate__animated animate__fadeInUp"
                style="animation-delay: 0.1s;">
                <div class="p-6 flex items-center">
                    <div class="p-3 rounded-full bg-indigo-50 text-indigo-600 mr-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $userCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Admins -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl hover:shadow-md transition-shadow duration-300 border border-gray-100 animate__animated animate__fadeInUp"
                style="animation-delay: 0.2s;">
                <div class="p-6 flex items-center">
                    <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Admins</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $adminCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Super Admins -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl hover:shadow-md transition-shadow duration-300 border border-gray-100 animate__animated animate__fadeInUp"
                style="animation-delay: 0.3s;">
                <div class="p-6 flex items-center">
                    <div class="p-3 rounded-full bg-yellow-50 text-yellow-600 mr-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Super Admins</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $superAdminCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 animate__animated animate__fadeInLeft"
                style="animation-delay: 0.4s;">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Articles per User</h3>
                <div class="relative h-64">
                    <canvas id="articlesPerUserChart"></canvas>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 animate__animated animate__fadeInRight"
                style="animation-delay: 0.4s;">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">User Role Distribution</h3>
                <div class="relative h-64 flex justify-center">
                    <canvas id="userRoleChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Category Management Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate__animated animate__fadeInUp"
            style="animation-delay: 0.5s;">
            <div
                class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h3 class="text-lg font-semibold text-gray-800">Category Management</h3>

                <!-- Add Category Form -->
                <form action="{{ route('categories.store') }}" method="POST" class="flex w-full sm:w-auto gap-2">
                    @csrf
                    <div class="relative flex-grow sm:flex-grow-0">
                        <input type="text" name="name" placeholder="New Category Name"
                            class="w-full sm:w-64 pl-4 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-sm"
                            required>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add
                    </button>
                </form>
            </div>

            @error('name')
                <div class="px-6 pt-2">
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                </div>
            @enderror

            <!-- Categories Table -->
            <!-- Categories Table Partial -->
            <div id="category-list-container">
                @include('superadmin.partials.categories-table')
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const container = document.getElementById('category-list-container');

                    container.addEventListener('click', function (e) {
                        const link = e.target.closest('a');
                        if (link && link.getAttribute('href')) {
                            e.preventDefault();
                            const url = link.getAttribute('href');

                            // Add opacity to indicate loading
                            container.style.opacity = '0.5';

                            fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                                .then(response => response.text())
                                .then(html => {
                                    container.innerHTML = html;
                                    container.style.opacity = '1';
                                    window.history.pushState(null, '', url);
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    container.style.opacity = '1';
                                });
                        }
                    });
                });
            </script>
        </div>

        <!-- Admin Report Section -->
        <x-admin-report :reports="$reports" />

    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Common Chart Options
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            family: "'Inter', sans-serif",
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        family: "'Inter', sans-serif",
                        size: 13
                    },
                    bodyFont: {
                        family: "'Inter', sans-serif",
                        size: 13
                    }
                }
            }
        };

        // Chart Configuration and Initialization
        window.initialiseSettingsCharts = () => {
            // 1. Destroy existing charts reliably using Chart.js registry
            const existingArticlesChart = Chart.getChart('articlesPerUserChart');
            if (existingArticlesChart) {
                existingArticlesChart.destroy();
            }

            const existingRoleChart = Chart.getChart('userRoleChart');
            if (existingRoleChart) {
                existingRoleChart.destroy();
            }

            // 2. Wait for layout stability (critical for correct dimension calculation)
            setTimeout(() => {
                const articlesCanvas = document.getElementById('articlesPerUserChart');
                const roleCanvas = document.getElementById('userRoleChart');

                // Guard clause: ensure elements exist (e.g., if navigating away quickly)
                if (!articlesCanvas || !roleCanvas) return;

                // Double check destruction inside timeout just in case of race conditions
                const raceCheckArticles = Chart.getChart('articlesPerUserChart');
                if (raceCheckArticles) raceCheckArticles.destroy();
                
                const raceCheckRole = Chart.getChart('userRoleChart');
                if (raceCheckRole) raceCheckRole.destroy();

                // Chart Artikel per User
                const articlesPerUserCtx = articlesCanvas.getContext('2d');
                window.articlesChart = new Chart(articlesPerUserCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($articlePerUser->keys()),
                        datasets: [{
                            label: 'Articles Published',
                            data: @json($articlePerUser->values()),
                            backgroundColor: 'rgba(79, 70, 229, 0.7)', // Indigo-600
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
                            barThickness: 20,
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(243, 244, 246, 1)', // Gray-100
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    precision: 0,
                                    font: {
                                        family: "'Inter', sans-serif"
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        family: "'Inter', sans-serif"
                                    }
                                }
                            }
                        }
                    }
                });

                // Chart User Role Distribution
                const userRoleCtx = roleCanvas.getContext('2d');
                window.roleChart = new Chart(userRoleCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['User', 'Admin', 'Super Admin'],
                        datasets: [{
                            data: [{{ $normalUserCount }}, {{ $adminCount }}, {{ $superAdminCount }}],
                            backgroundColor: [
                                'rgba(16, 185, 129, 0.8)', // Emerald-500
                                'rgba(59, 130, 246, 0.8)', // Blue-500
                                'rgba(245, 158, 11, 0.8)' // Amber-500
                            ],
                            borderColor: '#ffffff',
                            borderWidth: 2,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        ...commonOptions,
                        cutout: '65%',
                    }
                });

                // Force resize to ensure correct rendering dimensions
                window.articlesChart.resize();
                window.roleChart.resize();

            }, 300); // Small delay for animation frame/layout settlement
        };

        // Initialize on first load (Hard Refresh)
        window.addEventListener('load', window.initialiseSettingsCharts);

        // Initialize on Livewire navigation (Soft Navigate)
        document.addEventListener('livewire:navigated', window.initialiseSettingsCharts);
    </script>
</x-app-layout>