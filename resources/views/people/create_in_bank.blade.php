@extends('layouts.app')

@section('title', __('people.register_person'))

@section('content')

    {!! Form::open(['route' => 'people.store']) !!}

		<div class="card mb-4">
			<div class="card-body">
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsText('family_name', request()->query('family_name'), [ 'required', 'autofocus' ], __('people.family_name'), 'Greek: επώνυμο') }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('name', request()->query('name'), [ 'required' ], __('people.name'), 'Greek: όνομα') }}
                    </div>
					<div class="col-md-auto">
                        {{ Form::genderSelect('gender', null, __('people.gender')) }}
                    </div>
                    <div class="col-md-auto">
                        {{ Form::bsStringDate('date_of_birth', null, [ 'rel' => 'birthdate', 'data-age-element' => 'age' ], __('people.date_of_birth'), 'Greek: ημερομηνία γέννησης ') }}
                    </div>
					<div class="col-md-auto">
                        <p>@lang('people.age')</p>
                        <span id="age">?</span>
                    </div>
                </div>
				<div class="form-row">
					<div class="col-md">
                        {{ Form::bsNumber('police_no', null, ['prepend' => '05/'], __('people.police_number'), 'Greek: Δ.Κ.Α.') }}
					</div>
					<div class="col-md">
                        {{ Form::bsText('case_no', request()->query('case_no'), [ ], __('people.case_number'), 'Greek: Aριθ. Υπ.') }}
					</div>
                    <div class="col-md">
                        {{ Form::bsText('registration_no', null, [], __('people.registration_number')) }}
                    </div>
                    <div class="col-md">
                        {{ Form::bsText('section_card_no', null, [], __('people.section_card_number')) }}
                    </div>
				</div>
				<div class="form-row">
                    <div class="col-md">
                        {{ Form::bsText('nationality', null, ['id' => 'nationality', 'autocomplete' => 'off', 'rel' => 'autocomplete', 'data-autocomplete-source' => json_encode(array_values($countries))], __('people.nationality'), 'Greek: Υπηκοότητα') }}
                    </div>
					<div class="col-md">
                        {{ Form::bsText('remarks', null, [], __('people.remarks')) }}
                    </div>
				</div>
            </div>
        </div>

        <div class="card mb-4 d-none" id="children-container">
            <div class="card-body">
                <template id="child-form-row-template">
                    <div class="form-row">
                        <div class="col-md">
                            {{ Form::bsText('child_family_name[x]', null, [ 'placeholder' => 'Child Family Name' ], '', 'Greek: επώνυμο') }}
                        </div>
                        <div class="col-md">
                            {{ Form::bsText('child_name[x]', null, [ 'placeholder' => 'Child Name'  ], '', 'Greek: όνομα') }}
                        </div>
                        <div class="col-md-auto">
                            {{ Form::genderSelect('child_gender[x]', null, '') }}
                        </div>
                        <div class="col-md-auto">
                            {{ Form::bsStringDate('child_date_of_birth[x]', null, [ 'rel' => 'birthdate', 'data-age-element' => 'age' ], '', 'Greek: ημερομηνία γέννησης ') }}
                        </div>
                        <div class="col-md-auto">
                            <span id="age">?</span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

		<p>
            {{ Form::bsSubmitButton(__('app.register')) }}
            <button type="button" class="btn btn-secondary" id="add-children">@icon(child) Add child</button>
        </p>

        @isset(request()->card_no)
            {{ Form::hidden('card_no', request()->card_no) }}
        @endisset

    {!! Form::close() !!}

@endsection

@section('script')
    var childIndex = 0;
    $(function(){
        // Add children row
        $('#add-children').on('click', function(){
            var content = $($('#child-form-row-template').html()); //;.replace(/\[x\]/g, '[' + childIndex++ + ']');

            // Adapt name attribute
            content.find('input').each(function(){
                $(this).attr('name', $(this).attr('name').replace(/\[x\]/g, '[' + childIndex + ']'));
                if ($(this).attr('id')) {
                    $(this).attr('id', $(this).attr('id').replace(/-x-/g, '-' + childIndex + '-'));
                }
            });
            content.find('label').each(function(){
                if ($(this).attr('for')) {
                    $(this).attr('for', $(this).attr('for').replace(/-x-/g, '-' + childIndex + '-'));
                }
            });
            childIndex++;

            // Set default family name
            var familyName;
            var childFamilyNames = $('#children-container').find('input[name^="child_family_name"]');
            if (childFamilyNames.length > 0) {
                familyName = childFamilyNames.last().val();
            } else {
                familyName = $('input[name="family_name"]').val();
            }
            content.find('input[name^="child_family_name"]').val(familyName);

            // Add row (ensure container is visible)
            $('#children-container')
                .removeClass('d-none')
                .children('div').first().append(content);

            // Focus
            if (childFamilyNames.length > 0) {
                content.find('input[name^="child_name"]').focus();
            } else {
                content.find('input[name^="child_family_name"]').select();
            }
        });
    });
@endsection
