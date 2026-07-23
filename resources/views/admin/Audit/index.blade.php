@extends('layouts.admin')

@section('title', 'Data Audit - Admin')

@section('content')
    <div x-data="{ activeTab: new URLSearchParams(location.search).get('tab') || 'audit' }" class="space-y-4">
    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'audit'; setTimeout(() => { $('.dataTable').DataTable().columns.adjust().responsive.recalc(); }, 50);"
                    :class="activeTab === 'audit' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                Audit Internal
            </button>

            <button @click="activeTab = 'kunjungan'; setTimeout(() => { $('.dataTable').DataTable().columns.adjust().responsive.recalc(); }, 50);"
                    :class="activeTab === 'kunjungan' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                Kunjungan Lapangan
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div x-show="activeTab === 'audit'" x-cloak>
        @include('components.audit.tabelAuditInternal')
    </div>

    <div x-show="activeTab === 'kunjungan'" x-cloak>
        @include('components.audit.tabelKunjunganLapangan')
    </div>
</div>
@endsection
