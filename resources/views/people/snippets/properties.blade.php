<ul class="list-group list-group-flush">
    <li class="list-group-item">
        <div class="row">
            <div class="col-sm-5">
                <strong>
                    @lang('people.name')
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
                    @lang('people.family_name')
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
                        @lang('people.gender')
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
                        @lang('people.date_of_birth')
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
                        @lang('people.age')
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
                        @lang('people.police_number')
                    </strong>
                </div>
                <div class="col-sm">
                    {{ $person->police_no_formatted }}
                </div>
            </div>
        </li>
    @endisset
    @isset($person->nationality)
        <li class="list-group-item">
            <div class="row">
                <div class="col-sm-5">
                    <strong>
                        @lang('people.nationality')
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
                        @lang('people.languages')
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
                        @lang('people.portrait_picture')
                    </strong>
                </div>
                <div class="col-sm">
                    <img src="{{ Storage::url($person->portrait_picture) }}" class="img-fluid img-thumbnail">
                </div>
            </div>
        </li>
    @endisset

    {{-- Created / updated --}}
</ul>