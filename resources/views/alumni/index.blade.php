@extends('layouts.app')

@section('title', 'Alumni Directory')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Alumni Directory</h1>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form id="search-form" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="search" id="search" placeholder="Search by name, email, major, company..." class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ request('search') }}">
                <select name="graduation_year" id="graduation_year" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Years</option>
                    @foreach($graduationYears as $year)
                        <option value="{{ $year }}" {{ request('graduation_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Search</button>
            </div>
        </form>

        <div id="alumni-list">
            @include('alumni.partials.alumni-list', ['alumni' => $alumni])
        </div>
    </div>
</div>

<script>
document.getElementById('search-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams(formData);
    
    fetch('{{ route("alumni.index") }}?' + params.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('alumni-list').innerHTML = html;
    });
});
</script>
@endsection




