@extends('layouts.app')

@section('title', 'Image Monitoring Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Smart Image Monitoring Dashboard</h1>
        <p class="text-gray-600">Real-time monitoring of smart image system performance and coverage</p>
    </div>

    <!-- Metrics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Cache Hit Rate -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 9v7h7l7 7v7h7M8 21a2 2 0 002-2h-7a2 2 0 01-2-2H4a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Cache Hit Rate</h3>
                    <div class="mt-2">
                        <p class="text-3xl font-bold {{ $metrics['cache_hit_rate']['current'] > 85 ? 'text-green-600' : ($metrics['cache_hit_rate']['current'] > 75 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ number_format($metrics['cache_hit_rate']['current'], 1) }}%
                        </p>
                        <p class="text-sm text-gray-600">Target: 85%</p>
                        <p class="text-sm {{ $metrics['cache_hit_rate']['status'] === 'success' ? 'text-green-600' : ($metrics['cache_hit_rate']['status'] === 'warning' ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ ucfirst($metrics['cache_hit_rate']['status']) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Load Impact -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l-2 2h7l7 7v7h7M8 21a2 2 0 002-2h-7a2 2 0 01-2-2H4a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Page Load Impact</h3>
                    <div class="mt-2">
                        <p class="text-3xl font-bold {{ $metrics['page_load_impact']['current'] <= 2.0 ? 'text-green-600' : 'text-yellow-600' }}">
                            +{{ number_format($metrics['page_load_impact']['impact'], 1) }}s
                        </p>
                        <p class="text-sm text-gray-600">Target: &lt;2.0s</p>
                        <p class="text-sm {{ $metrics['page_load_impact']['status'] === 'success' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ ucfirst($metrics['page_load_impact']['status']) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Smart Coverage -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0l3.586 3.586a2 2 0 012.828 0L16 16m-2-2l4.586-4.586a2 2 0 012.828 0l3.586 3.586a2 2 0 012.828 0L16 16m-2-2l4.586-4.586a2 2 0 012.828 0l3.586 3.586a2 2 0 012.828 0L16 16z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Smart Coverage</h3>
                    <div class="mt-2">
                        <p class="text-3xl font-bold {{ $metrics['smart_coverage']['percentage'] > 90 ? 'text-green-600' : ($metrics['smart_coverage']['percentage'] > 75 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ number_format($metrics['smart_coverage']['products_with_smart']) }}/{{ number_format($metrics['smart_coverage']['total_products']) }}
                        </p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($metrics['smart_coverage']['percentage'], 1) }}%</p>
                        <p class="text-sm text-gray-600">Target: 90%</p>
                        <p class="text-sm {{ $metrics['smart_coverage']['percentage'] >= 90 ? 'text-green-600' : ($metrics['smart_coverage']['percentage'] >= 75 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ ucfirst($metrics['smart_coverage']['status']) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Usage -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.938c-1.342 0-2.684-1.403-2.684-1.403-2.684h3c-1.342 0-2.684 1.403-2.684 2.684v3c0 1.657 3.314 3.314 3.314h3c1.657 0 3.314-3.314 3.314-3.314v-3c0-1.657-3.314-3.314-3.314-3.314h-3z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">API Usage</h3>
                    <div class="mt-2">
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($metrics['api_usage']['daily_calls']) }}</p>
                        <p class="text-sm text-gray-600">Daily API Calls</p>
                        <p class="text-sm text-green-600">{{ $metrics['api_usage']['rate_limit_status'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Alerts -->
    @if(!empty($alerts))
    <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-8 8 8 0 100 8zm3.707-8.293l-3.414 3.414a1 1 0 01-1.414 1.414L9.172 4H6.828a1 1 0 01-.707-.293L2.293 2.293A1 1 0 00-.586.858l2.414 2.414a1 1 0 001.414 1.414L15.172 4H8.828a1 1 0 01.707-.293L17.707 11.586A1 1 0 001.414-1.414L16 13.172V12.828a1 1 0 00.293-.707l2.414-2.414A1 1 0 00-.586-1.586L10 11.828a1 1 0 01-.707-.586l-1.414-1.414A1 1 0 00-.293-.707l1.414-1.414A1 1 0 00-.586-.858L10 11.828a1 1 0 01-.293-.707l1.414 1.414a1 1 0 001.414 1.414L11.172 13.172V12.828a1 1 0 00.293-.707l-1.414-1.414A1 1 0 00-.586-1.586L10 13.172z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-red-800">Active Alerts</h3>
                <div class="mt-2">
                    @foreach($alerts as $alert)
                    <div class="mb-2 p-3 rounded-md border border-red-300 {{ $alert['type'] === 'critical' ? 'bg-red-100' : 'bg-yellow-100' }}">
                        <div class="flex">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $alert['type'] === 'critical' ? 'bg-red-800' : 'bg-yellow-800' }} text-white">
                                {{ strtoupper($alert['type']) }}
                            </span>
                            <p class="ml-2 text-sm font-medium {{ $alert['type'] === 'critical' ? 'text-red-800' : 'text-yellow-800' }}">
                                {{ $alert['message'] }}
                            </p>
                        </div>
                        <div class="mt-1 text-xs {{ $alert['type'] === 'critical' ? 'text-red-700' : 'text-yellow-700' }}">
                            <strong>Action:</strong> {{ $alert['action'] }}
                            <br>
                            <small><em>{{ $alert['timestamp'] }}</em></small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Source Status -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Image Source Status</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Unsplash</span>
                    <span class="text-sm {{ $metrics['api_usage']['sources_status']['unsplash']['status'] === 'healthy' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $metrics['api_usage']['sources_status']['unsplash']['status'] }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $metrics['api_usage']['sources_status']['unsplash']['response_time'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Pexels</span>
                    <span class="text-sm {{ $metrics['api_usage']['sources_status']['pexels']['status'] === 'healthy' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $metrics['api_usage']['sources_status']['pexels']['status'] }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $metrics['api_usage']['sources_status']['pexels']['response_time'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Pixabay</span>
                    <span class="text-sm {{ $metrics['api_usage']['sources_status']['pixabay']['status'] === 'healthy' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $metrics['api_usage']['sources_status']['pixabay']['status'] }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $metrics['api_usage']['sources_status']['pixabay']['response_time'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Coverage -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($coverage_data as $category => $data)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 capitalize mb-4">{{ $category }}</h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Products with Smart Images:</span>
                    <span class="text-lg font-bold text-gray-900">{{ $data['with_smart_images'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total in Category:</span>
                    <span class="text-lg font-bold text-gray-900">{{ $data['total'] }}</span>
                </div>
                <div class="mt-3">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $data['coverage_percentage'] }}%"></div>
                    </div>
                    <p class="text-sm text-gray-600 text-center mt-1">{{ number_format($data['coverage_percentage'], 1) }}% Coverage</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Performance Trends -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Performance Trends (7 Days)</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Cache Hit Rate</h4>
                <div class="flex items-end space-x-1">
                    @foreach($performance_trends['cache_hit_rate']['7_days'] as $value)
                        <div class="text-xs text-gray-500 w-8 text-center">{{ $value }}%</div>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Page Load Time</h4>
                <div class="flex items-end space-x-1">
                    @foreach($performance_trends['page_load_time']['7_days'] as $value)
                        <div class="text-xs text-gray-500 w-8 text-center">{{ $value }}s</div>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-2">Smart Coverage</h4>
                <div class="flex items-end space-x-1">
                    @foreach($performance_trends['smart_coverage']['7_days'] as $value)
                        <div class="text-xs text-gray-500 w-8 text-center">{{ $value }}%</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection