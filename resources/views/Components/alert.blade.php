@props(['type' => 'info', 'message'])

@php
$colors = [
'success' => 'bg-green-100 text-green-800',
'error' => 'bg-red-100 text-red-800',
'info' => 'bg-blue-100 text-blue-800',
];

$colorClass = $colors[$type] ?? $colors['info'];
@endphp

<div
  x-data="{ show: true }"
  x-show="show"
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 translate-y-2"
  x-transition:enter-end="opacity-100 translate-y-0"
  x-transition:leave="transition ease-in duration-300"
  x-transition:leave-start="opacity-100 translate-y-0"
  x-transition:leave-end="opacity-0 translate-y-2"
  x-init="setTimeout(() => show = false, 3000)"
  class="fixed top-5 right-5 z-50 p-4 rounded shadow-lg {{ $colorClass }}">
  {{ $message }}
</div>

<!-- Alpine.js for the alert component -->
<script src="//unpkg.com/alpinejs" defer></script>