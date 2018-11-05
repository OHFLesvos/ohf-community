                <div class="logo">
                    <img src="{{ public_path('img/logo_card.png') }}">
                </div>
                {{-- @isset($helper->person->portrait_picture)
                    <div class="portrait">
                        <img src="{{ Storage::path($helper->person->portrait_picture) }}">
                    </div>
                @endisset --}}
                <div class="title">
                    <div class="name">
                        @isset($helper->person->nickname)
                            {{ $helper->person->nickname }}
                        @else
                            {{ $helper->person->name }}
                        @endisset
                    </div>
                    <div class="responsibilities">
                        @if(is_array($helper->responsibilities) && count($helper->responsibilities) > 0)
                            {{ implode(', ', $helper->responsibilities) }}
                        @endif
                    </div>
                </div>
                <div class="issued">
                    Issued: {{ Carbon\Carbon::today()->toDateString() }}
                </div>