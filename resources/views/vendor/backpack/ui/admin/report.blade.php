@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.list') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none" bp-section="page-header">
        <h1 class="text-capitalize mb-0" bp-section="page-heading">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</h1>
        <p class="ms-2 ml-2 mb-0" id="datatable_info_stack" bp-section="page-subheading">{!! $crud->getSubheading() ?? '' !!}</p>
    </section>
@endsection

@section('content')
      <div class="card card-body">
          <form action="{{ url($crud->route) }}/export" method="GET">

              @include('winex01.backpack-filter::filters.filter_lists')

              <div class="form-group">
                  <a href="{{ url($crud->route) }}" id="remove_filters_button" class="btn btn-secondary">Clear Filters</a>
                  <button type="submit" class="btn btn-success">Download Reports</button>
              </div>

          </form>
      </div>

    {{-- In case widgets have been added to a 'content' group, show those widgets. --}}
    @include(backpack_view('inc.widgets'), [ 'widgets' => app('widgets')->where('group', 'content')->toArray() ])
@endsection

@section('after_styles')
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
  {{-- DONT remove datatables_logic, we need this so the field filter will auto populate, we just include that file here so it will also get updates from backpack --}}
  @include('crud::inc.datatables_logic')

  <script>
    // clear filters
      jQuery(document).ready(function($) {
        $("#remove_filters_button").click(function(e) {
          // remove query string
          crud.updateUrl('{{ url($crud->route) }}');
        });
      });
  </script>
  @stack('crud_list_scripts')
@endsection