<div class="form-group {{ $filter['class-col'] ?? 'col-2' }}">
    <label for="{{ $filter['name'] }}">{{ $filter['label'] }}</label>
    <select id="{{ $filter['name'] }}" name="{{ $filter['name'] }}" class="form-control">
        <option value="">-</option>

        @foreach($filter['options'] as $value => $label)
            <option value="{{ $value }}" {{ Request::get($filter['name']) == $value ? 'selected' : '' }}>{{ __($label) }}</option>
        @endforeach
    </select>
</div>