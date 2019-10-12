<ul class="list-group list-group-flush">
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-5">
                <strong>
                    @lang('people::people.name')
                </strong>
            </div>
            <div class="col-sm">
                {{ $person->name }}
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-5">
                <strong>
                    @lang('people::people.family_name')
                </strong>
            </div>
            <div class="col-sm">
                {{ $person->family_name }}
            </div>
        </div>
    </li>
    @isset($person->gender)
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-5">
                    <strong>
                        @lang('people::people.gender')
                    </strong>
                </div>
                <div class="col-sm">
                    @if($person->gender == 'f')@icon(female) Female 
                    @elseif($person->gender == 'm')@icon(male) Male 
                    @endif
                </div>
            </div>
        </li>
    @endisset
    @isset($person->date_of_birth)
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-5">
                    <strong>
                        @lang('people::people.date_of_birth')
                    </strong>
                </div>
                <div class="col-sm">
                    {{ $person->date_of_birth }}
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-5">
                    <strong>
                        @lang('people::people.age')
                    </strong>
                </div>
                <div class="col-sm">
                    {{ $person->age }}
                </div>
            </div>
        </li>
    @endisset
    @isset($person->police_no)
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-5">
                    <strong>
                        @lang('people::people.police_number')
                    </strong>
                </div>
                <div class="col-sm">
                    {{ $person->police_no_formatted }}
                </div>
            </div>
        </li>
    @endisset
    @isset($person->case_no_hash)
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-5">
                    <strong>
                        @lang('people::people.case_number')
                    </strong>
                </div>
                <div class="col-sm">
                    <em>@lang('app.encrypted')</em>
                </div>
            </div>
        </li>
    @endisset
    @isset($person->nationality)
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-5">
                    <strong>
                        @lang('people::people.nationality')
                    </strong>
                </div>
                <div class="col-sm">
                    {{ $person->nationality }}
                </div>
            </div>
        </li>
    @endisset
    @isset($person->languages)
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-5">
                    <strong>
                        @lang('people::people.languages')
                    </strong>
                </div>
                <div class="col-sm">
                    {!! is_array($person->languages) ? implode('<br>', $person->languages) : $person->languages !!}
                </div>
            </div>
        </li>
    @endisset
    @isset($person->portrait_picture)
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-5">
                    <strong>
                        @lang('people::people.portrait_picture')
                    </strong>
                </div>
                <div class="col-sm">
                    <img src="{{ Storage::url($person->portrait_picture) }}" class="img-fluid">
                </div>
            </div>
        </li>
    @endisset

    {{-- Family --}}
    @if(isset($person->mother) || isset($person->father) || isset($person->partner) || count($person->children) > 0 || count($person->partnersChildren) > 0)
        <li class="list-group-item py-0">
            <div class="row">
                <div class="col-sm-5 py-3">
                    <strong>
                        @lang('people::people.family')
                    </strong>
                </div>
                <div class="col-sm p-0">
                    <div class="list-group list-group-flush m-0 p-0">
                        @if(isset($person->mother))
                            <a href="{{ route($showRouteName, $person->mother) }}" class="list-group-item list-group-item-action">
                                @include('people::person-label', ['person'=> $person->mother, 'suffix' => __('people::people.mother')])
                            </a>
                        @endif
                        @if(isset($person->father))
                            <a href="{{ route($showRouteName, $person->father) }}" class="list-group-item list-group-item-action">
                                @include('people::person-label', ['person'=> $person->father, 'suffix' => __('people::people.father')])
                            </a>
                        @endif
                        @if(isset($person->partner))
                            <a href="{{ route($showRouteName, $person->partner) }}" class="list-group-item list-group-item-action">
                                @include('people::person-label', ['person'=> $person->partner, 'suffix' => __('people::people.partner')])
                            </a>
                        @endif
                        @if(count($person->siblings) > 0)
                            @foreach($person->siblings->sortByDesc('age') as $sibling) 
                                <a href="{{ route($showRouteName, $sibling) }}" class="list-group-item list-group-item-action">
                                    @include('people::person-label', ['person' => $sibling, 'suffix' => __('people::people.sibling')])
                                </a>
                            @endforeach
                        @endif
                        @if(count($person->children) > 0)
                            @foreach($person->children->sortByDesc('age') as $child) 
                                <a href="{{ route($showRouteName, $child) }}" class="list-group-item list-group-item-action">
                                    @include('people::person-label', ['person' => $child, 'suffix' => __('people::people.child')])
                                </a>
                            @endforeach
                        @endif
                        @if(count($person->partnersChildren) > 0)
                            @foreach($person->partnersChildren->sortByDesc('age') as $child) 
                                <a href="{{ route($showRouteName, $child) }}" class="list-group-item list-group-item-action">
                                    @include('people::person-label', ['person' => $child, 'suffix' => __('people::people.partners_child')])
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </li>
    @endif
    
    {{-- Created / updated --}}
</ul>