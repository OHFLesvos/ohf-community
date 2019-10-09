<div class="card my-4">
    <div class="card-header d-flex justify-content-between">
        @lang('people::people.relationships')
        @isset($editRouteName)
            <a href="{{ route($editRouteName, $person) }}" class="btn btn-sm btn-secondary">@icon(edit) @lang('app.edit')</a>
        @endisset
    </div>
    <div class="list-group list-group-flush">
        @if(isset($person->mother))
            <a href="{{ route($showRouteName, $person->mother) }}" class="list-group-item list-group-item-action">
                @include('people::person-label', ['person'=> $person->mother, 'prefix' => __('people::people.mother')])
            </a>
        @endif
        @if(isset($person->father))
            <a href="{{ route($showRouteName, $person->father) }}" class="list-group-item list-group-item-action">
                @include('people::person-label', ['person'=> $person->father, 'prefix' => __('people::people.father')])
            </a>
        @endif
        @if(isset($person->partner))
            <a href="{{ route($showRouteName, $person->partner) }}" class="list-group-item list-group-item-action">
                @include('people::person-label', ['person'=> $person->partner, 'prefix' => __('people::people.partner')])
            </a>
        @endif
        @if(count($person->siblings) > 0)
            @foreach($person->siblings->sortByDesc('age') as $sibling) 
                <a href="{{ route($showRouteName, $sibling) }}" class="list-group-item list-group-item-action">
                    @include('people::person-label', ['person' => $sibling, 'prefix' => __('people::people.sibling')])
                </a>
            @endforeach
        @endif
        @if(count($person->children) > 0)
            @foreach($person->children->sortByDesc('age') as $child) 
                <a href="{{ route($showRouteName, $child) }}" class="list-group-item list-group-item-action">
                    @include('people::person-label', ['person' => $child, 'prefix' => __('people::people.child')])
                </a>
            @endforeach
        @endif
        @if(count($person->partnersChildren) > 0)
            @foreach($person->partnersChildren->sortByDesc('age') as $child) 
                <a href="{{ route($showRouteName, $child) }}" class="list-group-item list-group-item-action">
                    @include('people::person-label', ['person' => $child, 'prefix' => __('people::people.partners_child')])
                </a>
            @endforeach
        @endif
    </div>
</div>
