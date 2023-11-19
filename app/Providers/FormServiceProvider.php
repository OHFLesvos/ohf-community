<?php

namespace App\Providers;

use Collective\Html\FormFacade as Form;
use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Return immediately if run in a console.
        // This fixes an issue during setup where the app key needs to be generated
        // but it fails due to below form macros requiring an app key aready being present.
        if (app()->runningInConsole()) {
            return;
        }

        Form::component('bsText', 'components.form.bsText', ['name', 'value' => null, 'attributes' => [], 'label' => null, 'help' => null]);
        Form::component('bsRadioList', 'components.form.bsRadioList', ['name', 'entries', 'value' => null, 'label' => null, 'help' => null]);
        Form::component('bsSelect', 'components.form.bsSelect', ['name', 'entries', 'value' => null, 'attributes' => [], 'label' => null, 'help' => null]);
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
