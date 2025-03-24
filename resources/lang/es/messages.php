<?php

return [
    'ok' => 'La información se cargó con éxito',

    'user_register_ok' => 'Usuario registrado correctamente',
    'user_register_error' => 'Error al registrar el usuario',

    'user_login_ok' => 'Usuario logueado correctamente',
    'user_login_error' => 'Error al loguear el usuario',
    'user_logout_ok' => 'Usuario deslogueado correctamente',

    'error_id_required' => 'El campo id es requerido',
    'error_id_integer' => 'El campo id debe ser un entero',
    'error_name_required' => 'El campo nombre es requerido',
    'error_name_string' => 'El campo nombre debe ser un texto',
    'error_email_required' => 'El campo email es requerido',
    'error_email_email' => 'El campo email debe ser un correo electrónico válido',
    'error_password_required' => 'El campo password es requerido',
    'error_password_string' => 'El campo password debe ser un texto',
    'error_password_min' => 'El campo password debe tener al menos 6 caracteres',
    'error_email_unique' => 'El email ya está en uso',
    'error_email_not_found' => 'El email no se encuentra registrado',
    'error_email_password' => 'El email o la contraseña son incorrectos',

    'error_token' => 'Token inválido o expirado',

    'company_not_found' => 'Compañia no encontrada',
    'company_created' => 'Compañia creada correctamente',
    'company_updated' => 'Compañia actualizada correctamente',
    'company_deleted' => 'Compañia eliminada correctamente',
    'company_create_error' => 'Error al crear la compañia',
    'company_update_error' => 'Error al actualizar la compañia',
    
    'contact_name_required' => 'El nombre del contacto es obligatorio.',
    'contact_name_string' => 'El nombre del contacto debe ser una cadena de texto.',
    'contact_name_max' => 'El nombre del contacto no debe exceder los 255 caracteres.',
    'contact_email_required' => 'El correo electrónico del contacto es obligatorio.',
    'contact_email_email' => 'El correo electrónico del contacto debe ser una dirección válida.',
    'contact_email_unique' => 'El correo electrónico del contacto ya está en uso.',
    'contact_phone_string' => 'El teléfono del contacto debe ser una cadena de texto.',
    'contact_phone_max' => 'El teléfono del contacto no debe exceder los 20 caracteres.',
    'contact_company_id_required' => 'El ID de la compañía es obligatorio.',
    'contact_company_id_exists' => 'La compañía seleccionada no existe.',

    'contact_not_found' => 'Contacto no encontrado.',
    'contact_created' => 'Contacto creado correctamente.',
    'contact_updated' => 'Contacto actualizado correctamente.',
    'contact_deleted' => 'Contacto eliminado correctamente.',
    'contact_create_error' => 'Error al crear el contacto.',
    'contact_update_error' => 'Error al actualizar el contacto.',
    'contact_address_string' => 'La dirección del contacto debe ser una cadena de texto.',
    'contact_address_max' => 'La dirección del contacto no debe exceder los 255 caracteres.',

    'note_content_required' => 'El contenido de la nota es obligatorio.',
    'note_content_string' => 'El contenido de la nota debe ser una cadena de texto.',
    'noteable_type_required' => 'El tipo de entidad (noteable_type) es obligatorio.',
    'noteable_type_string' => 'El tipo de entidad (noteable_type) debe ser una cadena de texto.',
    'noteable_type_invalid' => 'El tipo de entidad (noteable_type) no es válido. Los valores permitidos son: :values.',
    'noteable_id_required' => 'El ID de la entidad (noteable_id) es obligatorio.',
    'noteable_id_integer' => 'El ID de la entidad (noteable_id) debe ser un número entero.',
    'note_not_found' => 'La nota no fue encontrada.',
    'note_created' => 'Nota creada correctamente.',
    'note_updated' => 'Nota actualizada correctamente.',
    'note_deleted' => 'Nota eliminada correctamente.',
    'note_create_error' => 'Error al crear la nota.',
    'note_update_error'=> 'Error actualizando la nota.',

];