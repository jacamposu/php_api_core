<?php

class HttpDatabase
{
	public array $params;
	public string $model;
	public string $resource;
	public string $method;
	public string $type_result;
	public string $resource_model;
	public string $start_from;
	public array $_session;
	public object $_config;
	public bool $withoutlimit;

	public function __construct($config)
	{
		$this->_config = $config;
		$this->params = [];
		$this->type_result = 'assoc';
		$this->start_from = 0;
		$this->withoutlimit = false;
		$this->_session = [
			'app' => $config->app->name, 
			'lang' => $config->app->lang, 
			'type' => $config->app->ProfileDefault
		];
	}

	public function SetModel(string $model)
	{
		$this->model = $model;
		return $this;
	}

	public function SetResoruceModel(string $resource_model)
	{
		$this->method = $resource_model;
		return $this;
	}

	public function SetResourceGeneric(string $resource_generic)
	{
		$this->resource = $resource_generic;
		return $this;
	}

	public function SetParams(array $newParams)
	{
		$this->params = $newParams;
		return $this;
	}

	public function SetWithoutLimit()
	{
		$this->withoutlimit = true;
		return $this;
	}

	public function Get(): mixed
	{
		$http = new HpptClient($this->_config);

	    $result = $http
				->SetUrl($this->_config->url->wsDatabase)
				->SetHeaders($this)
				->Get()
				->decode();

		$http->close();

		return $result;
	}

	public function Post(array $body): mixed
	{
		$http = new HpptClient($this->_config);

		$result = $http
					->SetUrl($this->_config->url->wsDatabase)
					->SetHeaders($this)
					->Post(['params'=> $body])
					->decode();
	
		$http->close();
		
		return $result;
	}

	public function Put(array $body): mixed
	{
		$http = new HpptClient($this->_config);

		$result = $http
					->SetUrl($this->_config->url->wsDatabase)
					->SetHeaders($this)
					->Put(['params'=> $body])
					->decode();

		$http->close();
		
		return $result;
	}

	public function Delete(): mixed
	{
		$http = new HpptClient($this->_config);

	    $result = $http
				->SetUrl($this->_config->url->wsDatabase)
				->SetHeaders($this)
				->Delete()
				->decode();

		$http->close();

		return $result;
	}

	public function SP()
	{
		$http = new HpptClient($this->_config);
		$result = $http
					->SetUrl($this->_config->url->wsDatabaseSP)
					->SetHeaders($this)
					->Get()
					->execute();

		$http->close();

		return $result;
	}
}