<?php
namespace Morph\Validator;

use Illuminate\Support\ServiceProvider;
use Morph\Validator\Validator as CustomValidator;

class ValidatorServiceProvider extends ServiceProvider {

    public function register() {}

	public function boot() {
		$this->app->validator->resolver( function( $translator, $data, $rules, $messages = array(), $attributes = array() ) {
			return new CustomValidator( $translator, $data, $rules, $messages, $attributes );
		} );
	}

}
