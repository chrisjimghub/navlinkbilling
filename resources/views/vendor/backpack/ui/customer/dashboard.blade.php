@extends(backpack_view('blank'))
@php
    if (backpack_theme_config('show_getting_started')) {
        $widgets['before_content'][] = [
            'type'        => 'view',
            'view'        => backpack_view('inc.getting_started'),
        ];
    } else {
        $widgets['before_content'][] = [
            'type'        => 'jumbotron',
            'heading'     => 'Welcome, '.auth()->user()->customer->last_name.' '.auth()->user()->customer->first_name.'!',
            'content'     => __('Experience seamless payments with Gcash E-Wallet – faster, easier, and more convenient for all your billing needs!'),
            'heading_class' => 'display-6 '.(backpack_theme_config('layout') === 'horizontal_overlap' ? ' text-white' : ''),
            'content_class' => backpack_theme_config('layout') === 'horizontal_overlap' ? 'text-white' : '',
            'button_link' => backpack_url('logout'),
            'button_text' => trans('backpack::base.logout'),
        ];
    }

    if (!empty($contents)) {
        Widget::add()->to('after_content')->type('div')->class('row')->content($contents);
    }

@endphp

@section('content')
    {{-- In case widgets have been added to a 'content' group, show those widgets. --}}
    @include(backpack_view('inc.widgets'), [ 'widgets' => app('widgets')->where('group', 'content')->toArray() ])
@endsection