@extends('layouts.app')

@section('title'){{ trans('sheet.index_title') }}@endsection

@section('content')
    <div class="row" id="{{ trans('nav.sheet') }}">
        <div class="col-xs-12">
            <h1>{{ trans('sheet.index_title') }}</h1>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    {!! Form::textInput2d('search') !!}
                </div>
                @if(Auth::user()->isAdmin('sheet'))
                <div class="col-xs-12 col-md-6">
                    <br>
                    <a href="{{ route('sheet.create') }}" title="{{ trans('nav.sheet_create') }}" class="btn btn-2d">
                        <i class="fa fa-plus"></i>&nbsp;{{ trans('nav.sheet_create') }}
                    </a>
                </div>
                @endif
            </div>
            <br>

            <div class="panel panel-2d">
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th style="width: 11%;">{{ trans('sheet.label') }}</th>
                            <th style="width: 30%;">{{ trans('sheet.borrowed') }}</th>
                            <th style="width: 30%;">{{ trans('sheet.lost') }}</th>
                            <th style="width: 30%;">{{ trans('sheet.bought') }}</th>
                            <th style="width: 30%;">{{ trans('sheet.available') }}</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sheets as $sheet)
                            <tr class="sheet-row">
                                <td class="sheetlabel">{{ HTML::linkAction('SheetController@show', $sheet->label, [$sheet->id], ['style' => 'padding: 0']) }}</td>
                                <td class="borrowed">
                                    {{ Form::open(['action' => ['SheetController@ajaxUpdate', $sheet->id], 'method' => 'PUT', 'class' => 'borrow-form', 'style' => "display: inline-block", 'data-sheet-id' => $sheet->id, 'data-amount' => $sheet->amount, 'data-borrowed-count' => $sheet->borrowed->count()]) }}

                                        <span id="borrowed-count">{{ $sheet->borrowed->count()}} / {{  $sheet->amount }}</span>
                                        <button type="button" class="btn btn-xs btn-2d search-activator">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                        {{ Form::text('borrowed', '', ['class' => 'ajax-search form-control form-control-2d', 'style' => "display:none", 'list' => 'userlist']) }}
                                    {{  Form::close() }}
                                </td>
                                <td class="lost">
                                    {{ $sheet->lost->count()}} / {{  $sheet->amount }}

                                </td>
                                <td class="bought">
                                    {{ $sheet->bought->count()}} / {{  $sheet->amount }}

                                </td>
                                <td class="availableCount">
                                    {{ $sheet->availableCount }} / {{  $sheet->amount }}

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <datalist id="userlist">

    </datalist>
@endsection


@section('js')
    <script type="text/javascript">
        var users = {!! $users->toJson() !!};
        function filterRowsByLabel(rows, label) {
            if (label.length === 0 || !(label.trim())) {
                $(rows).show().parents('table, .panel').show();
                return;
            }

            var match = false;

            // Hide all rows.
            $(rows).hide().parents('table, .panel').hide();

            // Get all rows which label of the search terms and show them.
            $(rows).filter(function () {
                rowlabel = $(this).find('.sheetlabel').text().toLowerCase();
                return rowlabel.indexOf(label) >= 0;
            }).show().parents('table, .panel').show();
        }

        $(document).ready(function () {
            // Populate the datalist

            var $datalist = $('datalist#userlist');
            users.forEach(function(item) {
                // Create a new <option> element.
                var option = document.createElement('option');
                // Set the value using the item in the JSON array.
                option.textContent = item['first_name'] + ' ' + item['last_name'];
                option.setAttribute('data-id', item['id']);
                // Add the <option> element to the <datalist>.
                $datalist.append(option);
            });

            // Make the search react to inputs (with timeout delay).
            $('#search').bind('input propertychange', function (e) {
                // If it's the propertychange event, make sure it's the value that changed.
                if (window.event && event.type == 'propertychange' && event.propertyName != 'value')
                    return;

                var input = $(this).val();

                // Clear any previously set timer before setting a fresh one
                window.clearTimeout($(this).data('timeout'));
                $(this).data('timeout', setTimeout(function () {
                    filterRowsByLabel($('.sheet-row'), input);
                }, 500));
            });

            $('.search-activator').click(function(){
                var $brudda = $(this).siblings('.ajax-search').first();
                var visible = $brudda.is(':visible');
                $('.ajax-search').hide();
                if (visible){
                    $brudda.hide();
                } else {
                    $brudda.show();
                    $brudda.focus();
                }
            });


            $('.borrow-form').submit(function(){
                var $form = $(this);

                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: $(this).serialize(), // serializes the form's elements.
                    dataType: 'json',
                    success: function(data)
                    {
                        var amount = parseInt($form.data('amount'));
                        var borrowedCount = parseInt($form.attr('data-borrowed-count'));
                        $.notify(data.message, {'class': 'error', 'autoHide': false}) // show response from the php script.
                        borrowedCount = borrowedCount + 1;
                        var selector = 'form[data-sheet-id="' + data.sheetId +'"]';
                        $form = $(selector);
                        $form.attr('data-borrowed-count', borrowedCount);
                        $form.find('#borrowed-count').first().text(borrowedCount + ' / ' + amount);
                    },
                    error: function(data) {
                        $data = data.responseJSON;
                        if ($data.error){
                            $.notify($data.message, {'class': 'notifyjs-bootstrap-error'}) // show response from the php script.
                        }
                    }
                });


                event.preventDefault();
            })


        });
    </script>
@endsection