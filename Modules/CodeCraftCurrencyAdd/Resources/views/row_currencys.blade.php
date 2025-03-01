<tr>

	<td>
        <div class='form-group'>
            <input type='text' name='currency_alias[]' 
            class='form-control' value="{{$currency->country}} - {{$currency->currency}}"
            placeholder= "{{ __('codecraftcurrencyadd::lang.alias') }}" required>
        </div>
    </td>
    <td>
        {{$currency->country}} - {{$currency->currency}} [{{$currency->id}} ]
        <input type="hidden" name="currency_product[]" value="{{$currency->id}}">
	</td>
	<td>
        <div class='form-group'>
            <input type='number' name='currency_taza[]' 
            class='form-control' 
            placeholder="{{ __('codecraftcurrencyadd::lang.type_taza') }}" 
            step="0.000000000000001" 
            min="0" 
            required>
        </div>    
    </td>


		
	<td>
		<button type="button" class="btn btn-danger btn-xs remove_currency_product"><i class="fa fa-times"></i></button>
	</td>
</tr>

<script>
$(document).ready(function() {
    // Agrega un controlador de eventos al botón de eliminar
    $(document).on('click', '.remove_currency_product', function() {
        // Elimina la fila correspondiente al botón de eliminar
        $(this).closest('tr').remove();
    });
});
</script>
