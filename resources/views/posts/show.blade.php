<x-layout>
    <div class="mx-auto pt-24 max-w-xl px-4 py-6 sm:px-6 lg:px-8 flex min-h-full flex-col justify-center">

        <!-- Title -->
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-gray-900 break-words">
                {{ $post->post_title }}
            </h1>
        </div>

        <!-- Image -->
        @if ($post->image)
        <div class="mb-6 w-full flex justify-center">
            <img
                src="{{ asset('storage/' . $post->image) }}"
                alt="Post image"
                class="max-h-[420px] object-fit rounded-xl">
        </div>
        @endif

        <!-- Meta -->
        <div class="mb-4 flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center gap-2">
                @if ($post->post_status === 'active')
                <span class="inline-flex items-center gap-1 text-green-600">
                    <span class="h-2 w-2 rounded-full bg-green-500"></span>
                    Active
                </span>
                @elseif ($post->post_status === 'inactive')
                <span class="inline-flex items-center gap-1 text-red-600">
                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                    Inactive
                </span>
                @else
                <span class="inline-flex items-center gap-1 text-gray-400">
                    <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                    No status
                </span>
                @endif
            </div>

            <div>
                {{ $post->created_at->format('d M Y · H:i') }}
            </div>
        </div>

        <!-- Description -->
        <div class="prose text-gray-700">
            <p class="whitespace-pre-line break-words">
                {{ $post->post_description }}
            </p>
        </div>

        <!-- Return to Posts List -->
        <div class="mt-10 flex items-center justify-between border-t pt-6">

            <a href="{{ route('posts.index') }}"
                class="text-sm/6 font-semibold text-gray-900">
                ← Back to all posts
            </a>
        </div>

    </div>
</x-layout>