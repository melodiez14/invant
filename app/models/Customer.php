<?php

class Customer extends Eloquent {
	protected $table    = 'customers';
	protected $fillable = ['code', 'name', 'address_primary', 'address_secondary', 'city', 'zip', 'phone', 'fax', 'contact'];
}