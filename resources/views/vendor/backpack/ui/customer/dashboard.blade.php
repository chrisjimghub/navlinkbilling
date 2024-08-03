@extends(backpack_view('blank'))
Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae eaque deleniti sed magnam laudantium libero vero sequi facere accusantium culpa ut, officiis, tempore sapiente asperiores adipisci. Cupiditate eum odio culpa.



@section('content')
    {{-- In case widgets have been added to a 'content' group, show those widgets. --}}
    @include(backpack_view('inc.widgets'), [ 'widgets' => app('widgets')->where('group', 'content')->toArray() ])
@endsection