<?php


class UserOptionsMetadata
{
	public $options;
	public $UseDatUser;
	private $_config;
	private $icon;

	public function __construct(array $headers)
	{
		$loader = new LoaderResources();
		$this->_config = $loader->GetConfig();
	}

	public function Links()
	{
		return ;
	}

	public function InfoApplication()
	{
		return [
			0 => [
				'icon' => [
					'icon' => 'OpenDirectory', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Gestion de archivos', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			],
			1 => [
				'icon' => [
					'icon' => 'Forms', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Consultas y Formularios CRUD', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			],
			2 => [
				'icon' => [
					'icon' => 'TXTFile', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Generacion automatica de reportes', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			],
			3 => [
				'icon' => [
					'icon' => 'Lang', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Plataforma Multi-idioma', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			],
			4 => [
				'icon' => [
					'icon' => 'UserCog', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Perfilacion dinamica', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			],
			5 => [
				'icon' => [
					'icon' => 'Users', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Administracion dinamica de usuarios', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			],
			6 => [
				'icon' => [
					'icon' => 'Search', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Control de auditoria', 'UTF-8'),
				'description' => mb_convert_encoding('login, transacciones sql, control de cambio de passwords', 'UTF-8'),
				'link' => ''
			],
			7 => [
				'icon' => [
					'icon' => 'Mobile', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Diseno adaptable a cualquier pantalla', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			],
			8 => [
				'icon' => [
					'icon' => 'Book', 
					'size' => $this->_config->style->sizeIcons, 'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Diccionario de datos y opciones de menu dinamicos', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			],
			9 => [
				'icon' => [
					'icon' => 'Desktop', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Generador de interfaces', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			],
			10 => [
				'icon' => [
					'icon' => 'Database', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Modelador de base de datos', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			],
			11 => [
				'icon' => [
					'icon' => 'InfoCircle', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Manual de usuario (My Wiki)', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			],
			12 => [
				'icon' => [
					'icon' => 'Calendar', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Calendario con asignacion de tareas', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			],
			13 => [
				'icon' => [
					'icon' => 'RecordDelete', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Borrado logico o fisico de registros', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			],
			14 => [
				'icon' => [
					'icon' => 'MailBulk', 
					'size' => $this->_config->style->sizeIcons, 
					'colortext' => 'text-muted'
				],
				'title' => mb_convert_encoding('Gestion de correo electronico', 'UTF-8'),
				'description' => mb_convert_encoding('descripcion de la tarea', 'UTF-8'),
				'link' => ''
			]
		];

	}
}