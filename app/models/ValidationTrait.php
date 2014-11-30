<?php

trait ValidationTrait {
	
	/**
	 * Validation errors
	 *
	 * @var array
	 */
	protected $errors;

	/**
	 * Validate Model data
	 *
	 * @param array $data from input
	 * @return boolean
	 */
	public function validate($data)
	{
		$validate = \Validator::make($data, $this->rules, $this->messages);
		
		if ($validate->fails()) {
			$this->errors = $validate->errors();
			return false;
		}
		
		return true;
	}

	/**
	 * Validation errors
	 *
	 * @return array
	 */
	public function errors()
	{
		return $this->errors;
	}
}
