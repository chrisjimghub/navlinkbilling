{{-- select --}}
@php
	$current_value = old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '';
    $entity_model = $crud->getRelationModel($field['entity'],  - 1);
    $field['allows_null'] = $field['allows_null'] ?? $entity_model::isColumnNullable($field['name']);

    //if it's part of a relationship here we have the full related model, we want the key.
    if (is_object($current_value) && is_subclass_of(get_class($current_value), 'Illuminate\Database\Eloquent\Model') ) {
        $current_value = $current_value->getKey();
    }

    if (!isset($field['options'])) {
        $options = $field['model']::all();
    } else {
        $options = call_user_func($field['options'], $field['model']::query());
    }
@endphp

@include('crud::fields.inc.wrapper_start')

    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <span class="input-group-text">{!! $field['prefix'] !!}</span> @endif
        <select
            name="{{ $field['name'] }}"
            data-init-function="bpFieldInitSelect2Element"
            @include('crud::fields.inc.attributes', ['default_class' => 'form-control form-select'])
            >

            @if ($field['allows_null'])
                <option value="">-</option>
            @endif

            @if (count($options))
                @foreach ($options as $connected_entity_entry)
                    @if($current_value == $connected_entity_entry->getKey())
                        <option value="{{ $connected_entity_entry->getKey() }}" selected>{{ $connected_entity_entry->{$field['attribute']} }}</option>
                    @else
                        <option value="{{ $connected_entity_entry->getKey() }}">{{ $connected_entity_entry->{$field['attribute']} }}</option>
                    @endif
                @endforeach
            @endif
        </select>
        @if(isset($field['suffix'])) <span class="input-group-text">{!! $field['suffix'] !!}</span> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

@include('crud::fields.inc.wrapper_end')

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <!-- include select2 css-->
        <link href="{{ basset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ basset('https://cdn.jsdelivr.net/npm/select2-bootstrap-theme@0.1.0-beta.10/dist/select2-bootstrap.min.css') }}" rel="stylesheet" />
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- include select2 js-->
        <script src="{{ basset('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js') }}"></script>

        <script>
            function bpFieldInitSelect2Element(element) {
                // element will be a jQuery wrapped DOM node
                if (!element.hasClass("select2-hidden-accessible")) 
                {
                    let $isFieldInline = element.data('field-is-inline');
                    
                    element.select2({
                        theme: "bootstrap",
                        dropdownParent: $isFieldInline ? $('#inline-create-dialog .modal-content') : document.body
                    });
                }
            }
        </script>
    @endpush

@endif