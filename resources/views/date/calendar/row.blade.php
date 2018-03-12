<div class="row">
    <div class="col-xs-12">
        <?php $calendar->setCallbacks(['eventAfterAllRender' => 'dateFilters.applyAllFromCookie']); ?>
        {!! $calendar->calendar() !!}
        {!! $calendar->script() !!}
    </div>
</div>