<div class="row">
    <div class="col-xs-12">
        <?php $calendar->setCallbacks(['eventAfterAllRender' => 'function() {dateFilters.applyAllFilters(false); }',
        'eventAfterRender' => 'function(event, element, view) {$(element).data("filters", event.applicableFilters); }']); ?>
        {{-- We use eventAfterRender to attach data-tags to calendar elements in order to be able to filter them with the same functions used in list view. --}}
        {!! $calendar->calendar() !!}
        {!! $calendar->script() !!}
    </div>
</div>