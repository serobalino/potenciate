$(document).ready(function() {
    $('#frm_nuevo').bootstrapValidator({
        message: 'Valor ingresado invalido.',
        fields: {
            descripcion: {
                message: 'El campo descripción no es válido.',
                validators: {
                    notEmpty: {
                        message: 'El campo descripción es necesario.'
                    },
                    stringLength: {
                        min: 3,
                        max: 60,
                        message: 'El campo descripción tiene mínimo 3 letras.'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z\.\s\ñ\Ñ0-9]+$/,
                        message: 'No se admiten caracteres especiales.'
                    }
                }
            },
            lugar: {
                message: 'Debe elejir o crear un lugar.',
                validators: {
                    regexp: {
                        regexp: /^[0-9\s]+$/,
                        message: 'No se admiten caracteres especiales.'
                    }
                }
            },
			tipo: {
                message: 'El tipo de evento es necesario.',
                validators: {
                    notEmpty: {
                        message: 'El tipo de evento es necesario.'
                    },
                    stringLength: {
                        min: 4,
                        max: 20,
                        message: 'El tipo de evento tiene maximo 20 caracteres.'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z\.\s\ñ\Ñ-]+$/,
                        message: 'Puede separar con "-" o espacio en blanco.'
                    }
                }
            },
            ponente: {
                validators: {
                    regexp: {
                        regexp: /^[a-zA-Z\.\s\ñ\Ñ]+$/,
                        message: 'No se admiten caracteres especiales.'
                    }
                }
            },
			cupo: {
                validators: {
					regexp: {
                        regexp: /^[1-9\s]+$/,
                        message: 'Solo se aceptan números naturales.'
                    }
                }
            },
			fecha: {
                validators: {
                    notEmpty: {
                        message: 'Elija una fecha.'
                    }
                }
            },
			hora_inicio: {
                validators: {
                    notEmpty: {
                        message: 'Elija una hora de inicio.'
                    }
                }
            },
			hora_fin: {
                validators: {
                    notEmpty: {
                        message: 'Elija una hora de fin.'
                    }
                }
            }
        }
    });
});