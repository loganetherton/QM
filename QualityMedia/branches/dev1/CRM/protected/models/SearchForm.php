<?php

/**
 * SearchForm class.
 * SearchForm is the data structure for keeping
 * user search form data. It is used by the 'search' action of 'SiteController'.
 */
class SearchForm extends CFormModel
{
	public $search;
	public $zip;
	public $category;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// search keyword is required
			//array('search', 'required'),
			array('zip', 'required'),
		);
	}
	
	
}
