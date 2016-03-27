<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        \Form::component('textInput2d', 'components.form.text', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('dateInput2d', 'components.form.date', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('checkboxInput2d', 'components.form.checkbox', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('selectInput2d', 'components.form.select', ['name', 'list' => [], 'selected' => null, 'attributes' => []]);
        \Form::component('passwordInput2d', 'components.form.password', ['name', 'attributes' => []]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
