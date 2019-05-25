@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('list.show_title') }}</h2><br>
                <div>
                    <table class="table table-striped table-hover table-reflow">
                        <tr>
                            <th><strong> {{ __('list.name') }}</strong></th>
                            <td><strong> {{ __('list.amount') }}</strong></td>
                            <td></td>
                            <td><strong> {{ __('list.user') }}</strong></td>
                        </tr>
                        @foreach($items as $item)
                            <tr>
                                <td> {{ $item->name }} </td>
                                <td>  {{ $item->amount }} </td>
                                <td>
                                    @if(isset($item->full_name)) &check;
                                    @endif </td>
                                <td>  {{ $item->full_name }} </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
