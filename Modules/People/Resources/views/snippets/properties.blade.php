<ul class="list-group list-group-flush">
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
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-5">
                <strong>
                    @lang('app.created')
                </strong>
            </div>
            <div class="col-sm">
                {{ $person->created_at }}
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-5">
                <strong>
                    @lang('app.last_updated')
                </strong>
            </div>
            <div class="col-sm">
                {{ $person->updated_at }}
            </div>
        </div>
    </li>
</ul>