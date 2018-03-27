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
        \Form::component('textInput2d', 'components.form.text', ['name', 'value' => null, 'attributes' => [], 'helpBubble' => false]);
        \Form::component('numberInput2d', 'components.form.number', ['name', 'value' => null, 'attributes' => [], 'helpBubble' => false]);
        \Form::component('textareaInput2d', 'components.form.textarea', ['name', 'value' => null, 'attributes' => [], 'helpBubble' => false]);
        \Form::component('dateInput2d', 'components.form.date', ['name', 'value' => null, 'attributes' => [], 'helpBubble' => trans('form.help_date')]);
        \Form::component('datetimeInput2d', 'components.form.datetime', ['name', 'value' => null, 'attributes' => [], 'helpBubble' => trans('form.help_dateTime')]);
        \Form::component('checkboxInput2d', 'components.form.checkbox', ['name', 'value' => null, 'attributes' => [], 'helpBubble' => false]);
        \Form::component('radioInput2d', 'components.form.radio', ['name', 'label', 'value' => 1, 'checked' => false, 'attributes' => [], 'helpBubble' => false]);
        \Form::component('selectInput2d', 'components.form.select', ['name', 'list' => [], 'selected' => null, 'attributes' => [], 'helpBubble' => false]);
        \Form::component('passwordInput2d', 'components.form.password', ['name', 'attributes' => [], 'helpBubble' => false]);

        // Form helper bubble for some field.
        \Form::component('helpBubble', 'components.form.helpBubble', ['content' => '']);
        
        // Buttons
        \Html::component('addButton', 'components.button.btn-add', ['title', 'href', 'classes' => [], 'attributes' => []]);
        \Form::component('submitInput2d', 'components.button.submit', ['attributes' => [], 'title' => trans('form.save')]);

        // Add an urlescape directive to Blade templates.
        \Blade::directive('urlescape', function ($expression) {
            return '<?php echo urlencode(' . $expression . '); ?>';
        });
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
