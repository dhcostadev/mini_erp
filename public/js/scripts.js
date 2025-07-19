$(document).ready(function() {
    $('#cep').on('input', function() {
        let cep = $(this).val().replace(/\D/g, '');
        if (cep.length === 8) {
            $.ajax({
                url: '../products/index.php?action=get_address',
                method: 'POST',
                data: { cep: cep },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#street').val(response.street);
                        $('#neighborhood').val(response.neighborhood);
                        $('#city').val(response.city);
                        $('#state').val(response.state);
                        $('#cep-error').text('');
                    } else {
                        $('#cep-error').text(response.error);
                        $('#street, #neighborhood, #city, #state').val('');
                    }
                },
                error: function() {
                    $('#cep-error').text('Erro ao consultar o CEP');
                    $('#street, #neighborhood, #city, #state').val('');
                }
            });
        } else {
            $('#cep-error').text('CEP deve conter 8 dígitos');
            $('#street, #neighborhood, #city, #state').val('');
        }
    });

    // Máscara de CEP
    $('#cep').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 5) {
            value = value.substring(0, 5) + '-' + value.substring(5, 8);
        }
        $(this).val(value);
    });
});