{{-- /var/www/src/resources/views/components/admin/applications/users/unacknowledged-badge.blade.php --}}

@props([
    'dot' => '',
])

@php
    if($dot !== '' && $pendingCount > 0) $pendingCount = -1;
@endphp

<x-ui.badge :count="$pendingCount" />
