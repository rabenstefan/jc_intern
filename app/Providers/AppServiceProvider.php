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
        // Easily accessible form-inputs with right styling etc.
        \Form::component('textInput2d', 'components.form.text', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('textareaInput2d', 'components.form.textarea', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('dateInput2d', 'components.form.date', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('datetimeInput2d', 'components.form.datetime', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('checkboxInput2d', 'components.form.checkbox', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('selectInput2d', 'components.form.select', ['name', 'list' => [], 'selected' => null, 'attributes' => []]);
        \Form::component('passwordInput2d', 'components.form.password', ['name', 'attributes' => []]);
        
        // Buttons
        \Html::component('addButton', 'components.button.btn-add', ['title', 'href', 'classes' => [], 'attributes' => []]);
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
