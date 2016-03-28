<div class="row">
    @if(isset($date->getEventOptions()['url']))
        <a href="{{ $date->getEventOptions()['url'] }}">
    @endif
    <div class="col-xs-12 event event-{{ $date->getShortName() }}">
        <h4 class="title">{{ $date->getTitle() }}</h4>
        <p class="date">
            @if($date->isAllDay())
                {{ $date->getStart()->format('d.m.Y') }}
            @else
                {{ $date->getStart()->format('d.m.Y H:i') }} - {{ $date->getEnd()->format('d.m.Y H:i') }}
            @endif
        </p>
        <span class="date_descr">
            {{ $date->description }}
        </span>
    </div>
    @if(isset($date->getEventOptions()['url']))
        </a>
    @endif
</div>