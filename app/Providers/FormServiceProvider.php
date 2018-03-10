<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Collective\Html\FormFacade as Form;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Form::component('bsText', 'components.form.bsText', [ 'name', 'value' => null, 'attributes' => [], 'label' => null, 'help' => null ]);
        Form::component('bsNumber', 'components.form.bsNumber', [ 'name', 'value' => null, 'attributes' => [], 'label' => null, 'help' => null ]);
        Form::component('bsPassword', 'components.form.bsPassword', [ 'name', 'attributes' => [], 'label' => null, 'help' => null ]);
        Form::component('bsTextarea', 'components.form.bsTextarea', [ 'name', 'value' => null, 'attributes' => [], 'label' => null, 'help' => null ]);
        Form::component('bsDate', 'components.form.bsDate', [ 'name', 'value' => null, 'attributes' => [], 'label' => null, 'help' => null ]);
        Form::component('bsStringDate', 'components.form.bsStringDate', [ 'name', 'value' => null, 'attributes' => [], 'label' => null, 'help' => null ]);
        Form::component('bsCheckbox', 'components.form.bsCheckbox', [ 'name', 'value' => null, 'checked' => null, 'label' => null ]);
        Form::component('bsCheckboxList', 'components.form.bsCheckboxList', [ 'name', 'entries', 'value' => null, 'label' => null, 'help' => null ]);
        Form::component('bsCheckboxInlineList', 'components.form.bsCheckboxInlineList', [ 'name', 'entries', 'value' => null, 'label' => null, 'help' => null ]);
        Form::component('bsRadio', 'components.form.bsRadio', [ 'name', 'value' => null, 'checked' => null, 'label' => null ]);
        Form::component('bsRadioInline', 'components.form.bsRadioInline', [ 'name', 'value' => null, 'checked' => null, 'label' => null ]);
        Form::component('bsRadioList', 'components.form.bsRadioList', [ 'name', 'entries', 'value' => null, 'label' => null, 'help' => null ]);
        Form::component('bsRadioInlineList', 'components.form.bsRadioInlineList', [ 'name', 'entries', 'value' => null, 'label' => null, 'help' => null ]);
        Form::component('bsSelect', 'components.form.bsSelect', [ 'name', 'entries', 'value' => null, 'attributes' => [], 'label' => null, 'help' => null ]);
        Form::component('bsSubmitButton', 'components.form.bsSubmitButton', [ 'label', 'icon' => 'check' ]);
        Form::component('bsDeleteButton', 'components.form.bsDeleteButton', [ 'label' => 'Delete', 'icon' => 'trash', 'confirmation' => 'Do you really want to delete this item?' ]);
        Form::component('bsDeleteForm', 'components.form.bsDeleteForm', [ 'action', 'label' => 'Delete', 'icon' => 'trash', 'confirmation' => 'Do you really want to delete this item?' ]);
        Form::component('bsButtonLink', 'components.form.bsButtonLink', [ 'href', 'label', 'icon', 'class' => 'secondary' ]);

        Form::component('genderSelect', 'components.form.genderSelect', [ 'name', 'value' => null, 'label' => 'Gender' ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
