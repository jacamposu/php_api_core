<?php 

class Button
{
	public string $type;
	public string $id;
	public string $class;
	public mixed $actionJs;
	public string $icon;
	public string $text;
	public string $keyField;
	public string $endpoint;
	public string $action;
	public string $mode;

	public function __construct()
	{
        $this->type = 'button';
		$this->id = 'btn-submit';
		$this->class = 'btn btn-primary btn-block mt-2';
		$this->icon = 'fas fa-edit';
	}

	public function Cancel()
	{
		$this->type = 'button';
		$this->id = 'btn-cancel';
		$this->class = 'btn btn-outline-secondary btn-block mt-2';
		$this->icon = 'fas fa-undo';
		$this->actionJs = 'history.back();';
		return $this;
	}

	public function Button()
	{
		$this->type = 'button';
		$this->id = 'btn-submit';
		$this->class = 'btn btn-primary btn-block mt-2';
		$this->icon = 'fas fa-edit';
		return $this;
	}	
}
