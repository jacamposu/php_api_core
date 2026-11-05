<?php 

class HomeController 
{
	protected $metadata;
	public $dataInput;
	protected $control;
	protected $model;
	public object $config;
	private array $_headers;
	private HttpRequest $response;
	private LoaderResources $_loader;
	
	public function __construct(object $config, array $headers)
	{
		$this->response = new HttpRequest();
		try
		{						
			$routClass = 'Metadata/HomeMetadata.php';

			if(!file_exists($routClass)) throw new Exception('No existe archivo metadata', 404);
			
			require_once $routClass;
			$this->_loader = new LoaderResources();
			$this->metadata = new HomeMetadata($headers);
			$this->_headers = $headers;
		}
		catch(Exception $ex)
		{
			$this->response->Response(404, $ex->getMessage());
		}
	}


	public function Home ()
	{
		$this->response->VerifyMethod("GET");

		$nav = new NavigationBar($this->metadata, $this->_headers);

		$data = [
			'PageInformation' => [
				"Title" => $this->metadata->title,
                "Subtitle" => $this->metadata->subtitle,
                "IconPage" => $this->metadata->iconPage.' fa-2x text-muted',
				"msgHelpGrid" => $this->metadata->msgHelpGrid,
                "msgHelpForm" => $this->metadata->msgHelpForm,
				'NavBarOptions' => $nav->navbarOptions(true),
				'links' => $this->metadata->AddLinks(),
				'scriptsHead' => $this->metadata->AddScripts(),
				'scriptsFooter' => $this->metadata->AddScriptsFooter(),
                

				"greeting" => "¡Bienvenido!, Administrador Sistema",
				"msgCoorp" => "Reemplazar acá por mensaje corporativo...",
				"msgHelp" => "Hemos creado una apliación que puede ayudarte en éstas cosas:"
				
			]
		];

		$this->response->Response(200, $data);
	}
}