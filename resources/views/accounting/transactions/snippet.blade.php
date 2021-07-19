    <ul class="list-group list-group-flush shadow-sm">

    {{-- Pictures --}}
    @unless(empty($transaction->receipt_pictures))
        <hr class="mt-0">
            <div class="form-row mx-3 mb-2">
                @foreach($transaction->receipt_pictures as $picture)
                    @if(Storage::exists($picture))
                    <div class="col-auto mb-2">
                        @if(Str::startsWith(Storage::mimeType($picture), 'image/'))
                            <a href="{{ Storage::url($picture) }}" data-fslightbox="gallery">
                                <x-thumbnail :size="config('accounting.thumbnail_size')">
                                    @if(Storage::exists(thumb_path($picture)))
                                        {{ Storage::url(thumb_path($picture)) }}
                                    @else
                                        {{ Storage::url($picture) }}
                                    @endif
                                </x-thumbnail>
                            </a>
                        @else
                            @if(Storage::exists(thumb_path($picture, 'jpeg')))
                                <a href="{{ Storage::url($picture) }}" target="_blank">
                                    <x-thumbnail :size="config('accounting.thumbnail_size')">
                                        {{ Storage::url(thumb_path($picture, 'jpeg')) }}
                                    </x-thumbnail>
                                </a>
                            @else
                                <a href="{{ Storage::url($picture) }}" target="_blank">
                                <span class="display-4" title="{{ Storage::mimeType($picture) }}">
                                    @if(Storage::mimeType($picture) == 'application/pdf')<x-icon icon="file-pdf"/>@else <x-icon icon="file"/>@endif</span></a> {{ bytes_to_human(Storage::size($picture)) }}
                            @endif
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @endunless
