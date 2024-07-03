@php
    use App\Models\Customer;
@endphp

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
            'heading'     => trans('backpack::base.welcome'),
            'heading_class' => 'display-3 '.(backpack_theme_config('layout') === 'horizontal_overlap' ? ' text-white' : ''),
            'content'     => trans('backpack::base.use_sidebar'),
            'content_class' => backpack_theme_config('layout') === 'horizontal_overlap' ? 'text-white' : '',
            'button_link' => backpack_url('logout'),
            'button_text' => trans('backpack::base.logout'),
        ];


        // $customerCount = Customer::count();
        // $nextMilestone = (ceil($customerCount / 100) * 100);
        // if ($customerCount == $nextMilestone) {
        //     $nextMilestone += 100; // Move to the next milestone if current count matches the milestone
        // }
        // $remainingCustomers = $nextMilestone - $customerCount;
        // $progress = ($customerCount % 100) / 100 * 100; // Calculate progress towards the current milestone

        // $widgets['before_content'][] = [
        //     'type'        => 'progress',
        //     'class'       => 'card text-white bg-primary mb-2',
        //     'value'       => number_format($customerCount),
        //     'description' => 'Registered Customers.',
        //     'progress'    => round($progress), // Round the progress to the nearest integer
        //     'hint'        => number_format($remainingCustomers) . ' more until next milestone (' . $nextMilestone . ').',
        // ];
        


        // $widgets['before_content'][] = [
        //     'type'        => 'progress',
        //     'class'       => 'card text-white bg-warning mb-2',
        //     'value'       => '11.456',
        //     'description' => 'Registered users.',
        //     'progress'    => 57, // integer
        //     'hint'        => '8544 more until next milestone.',
        // ];

    }
@endphp

@section('content')
@endsection
