/**
 * Bootstrap validate
 * English lang module
 */

$.bt_validate.fn = {
  'required' : function(value) { return (value  != null) && (value != '')},
  'email' : function(value) { return /^[a-z0-9-_\.]+@[a-z0-9-_\.]+\.[a-z]{2,4}$/.test(value) },
  'www' : function(value) { return /^(http:\/\/)|(https:\/\/)[a-z0-9\/\.-_]+\.[a-z]{2,4}$/.test(value) },
  'date' : function(value) { return /^[\d]{2}\/[\d]{2}\/[\d]{4}$/.test(value) },
  'time' : function(value) { return /^[\d]{2}:[\d]{2}(:{0,1}[\d]{0,2})$/.test(value) },
  'datetime' : function(value) { return /^[\d]{2}\/[\d]{2}\/[\d]{4} [\d]{2}:[\d]{2}:{0,1}[\d]{0,2}$/.test(value) },
  'number' : function(value) { return /^[\d]+$/.test(value) },
  'float' : function(value) { return /^([\d]+)|(\d]+\.[\d]+)$/.test(value) },
  'equal' : function(value, eq_value) { return (value == eq_value); },
  'min' : function(value, min) { return Number(value) >= min },
  'max' : function(value, max) { return Number(value) <= max },
  'between' : function(value, min, max) { return (Number(value) >= min) && (Number(value) <= max)},
  'length_min' : function(value, min) { return value.length >= min },
  'length_max' : function(value, max) { return value.length <= max },
  'length_between' : function(value, min, max) { return (value.length >= min) && (value.length <= max)},
  'valid_image_file' : function(value){ return (((/\.(gif|jpg|jpeg|png)$/i).test(value)) || (value == ''))}
}

$.bt_validate.text = {
  'required' : 'No puede estar vac&iacute;o!',
  'email' : 'No es un Email v&aacute;lido',
  'www' : 'Este valor no es una http:// v&aacute;lida',
  'date' : 'Este valor no es una fecha v&aacute;lida',
  'time' : 'The value is not valid time',
  'datetime' : 'The value is not valid datetime',
  'number' : 'Este valor no es un n&uacute;mero v&aacute;lido',
  'float' : 'The value is not valid floating point number',
  'equal' : 'The value must be equal to "%1"',
  'min' : 'El valor debe ser mayor o igual a %1',
  'max' : 'El valor debe ser menor o igual a %1',
  'between' : 'El valor debe estar entre %1 y %2',
  'length_min' : 'M&iacute;nimo %1 caracteres',
  'length_max' : 'M&aacute;ximo %1 caracteres',
  'length_between' : 'La longitud debe ser de %1 hasta %2',
  'valid_image_file' : 'El archivo no tiene una extensi&oacute;n v&aacute;lida para im&aacute;genes'
}