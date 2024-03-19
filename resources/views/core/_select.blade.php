<option value="" disabled selected>-- Pilih --</option>
@foreach ($data as $name => $val)
    <option value="{{ $val }}" {{ (isset($data->value) && $data->value == $val) ? 'selected' : '' }}>{{ $name }}</option>
@endforeach
