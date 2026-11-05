<?php 
/**
 * Controlador encargado de cargar la metadata del controlador
 * lang
 * config
 * 
 */

class MainGenericController
{
	protected HttpRequest $_response;
	private CrudController $_crud;
	private LoaderResources $_loader;
	private int $_responseCode;
	private string $_model;
	private object $_lang;
	private string $_fileMetadata;
	private array $_headers;
	private object $_config;
	private object $_metadata;
	
	
	public function __construct(object $config, array $headers, string $page)
	{
		$this->_config = $config;
		$this->_headers = $headers;
		$this->_fileMetadata = $page.'Metadata';
		$this->_model = $page.'Model';
		$this->_responseCode = 200;		
		$this->_loader = new LoaderResources();
		$this->LoadMetadata();
		$this->_response = new HttpRequest();
		$this->_crud = new CrudController($this->_config, $this->_metadata, $this->_headers);
		$this->_lang = $this->_loader->GetLang($headers['user_lang']);		
	}

	public function LoadMetadata():void
	{
		$routClass = "Metadata/{$this->_fileMetadata}.php";
		if(file_exists($routClass))
		{
			require_once $routClass;
			$this->_metadata = new $this->_fileMetadata($this->_headers);
		}
	}

	/* [GET METHOD] */
	public function MainInfoPage():void
	{
		try 
		{
			$this->_response->VerifyMethod("GET");
			$data = $this->_crud->GetInfoPage();
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
		$this->_response->Response($this->_responseCode, $data);
	}

	/* [GET METHOD] */
	public function MainReturnDataTable():void
	{
		try 
		{
			$this->_response->VerifyMethod("GET");

			$headers = new HttpDatabase($this->_config);
			$headers->SetModel($this->_model)->SetResoruceModel('All');
			
			$start = 0;
			if(isset($this->_headers['page']) && $this->_headers['page'] > 1)
			{
				$start = (($this->_config->app->rowsByPage * $this->_headers['page']) - $this->_config->app->rowsByPage ) + 1;
				$headers->start_from = $start;
			}	
			
			if(isset($this->_headers['orderby']))
				$headers->SetParams(['orderby' => $this->_headers['orderby']]);

			$result = $headers->Get();

			if(empty($result)) throw new Exception("La consulta no arrojó ningún resultado.", 500);

			if($result->statusCode == 500)
			{
				$error = [
					'status' => 'error',
					'message' => $result->result->error->message,
					'query' => $result->result->error->query,
					'hashError' => $result->result->error->hashError
				];
				throw new Exception(json_encode($error),$result->statusCode);
			}
			
			$headers2 = new HttpDatabase($this->_config);
			$headers2->SetModel($this->_model)->SetResoruceModel('Total');
			$totalRecords = $headers->Get();

			$data = $this->_crud->GetDataTable($result, $totalRecords, $start);
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
		$this->_response->Response($this->_responseCode, $data);
	}

	/* [GET METHOD] */
	public function MainFormAdd(array $fields):void
	{
		try 
		{
			$this->_response->VerifyMethod("GET");
			$headers = new HttpDatabase($this->_config);
			$headers->SetModel($this->_model)
				->SetResoruceModel('FormAdd');
			$resultForm = $headers->Get();

			$headers2 = new HttpDatabase($this->_config);
			$headers2->SetModel($this->_model)
				->SetResoruceModel('list')
				->SetResourceGeneric('all')
				->SetParams(["fieldsList" => $fields]);
			$resultList = $headers2->Get();

			$resultListSaved = new stdClass();

			$data = $this->_crud->GetDataForm('Add', $resultForm, $resultList, $resultListSaved);
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
		$this->_response->Response($this->_responseCode, $data);
	}

	/* [GET METHOD] */
	public function MainFormEdit(array $fields, array $fields2):void
	{
		try 
		{
			$this->_response->VerifyMethod("GET");

			$this->ValidateHeader('id');
			
			$params[':keyfield'] = $this->_headers['id'];	
			$headers = new HttpDatabase($this->_config);
			$headers->SetModel($this->_model)->SetResoruceModel('FormUpdate')->SetParams($params);
			$resultForm = $headers->Get();
			
			$params = ["fieldsList" => $fields];
			$headers2 = new HttpDatabase($this->_config);
			$headers2->SetModel($this->_model)->SetResoruceModel('list')->SetResourceGeneric('all')->SetParams($params);
			$resultList = $headers2->Get();
			
			$params = [':keyfield' => $this->_headers['id'], "fieldsList" => $fields2];		
			$headers3 = new HttpDatabase($this->_config);
			$headers3->SetModel($this->_model)->SetResoruceModel('list')->SetResourceGeneric('save')->SetParams($params);
			$resultListSaved = $headers3->Get();
			
			$data = $this->_crud->GetDataForm('Edit', $resultForm, $resultList, $resultListSaved);
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
		$this->_response->Response($this->_responseCode, $data);
	}

	/* [GET METHOD] */
	public function MainListView():void
	{
		try 
		{
			$this->_response->VerifyMethod("GET");
			$this->ValidateHeader('id');
			
			$params = [
				':keyfield' => $this->_headers['id']
			];
			$headers = new HttpDatabase($this->_config);
			$headers->SetModel($this->_model)->SetResoruceModel('Row')->SetParams($params);
			$record = $headers->Get();

			$data = $this->_crud->oneRecord($record);
			$this->_response->Response(200, $data);
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
			$this->_response->Response($this->_responseCode, $data);
		}
	}

	/* [POST METHOD] */
	public function MainAddRecord($body):void
	{
		try 
		{
			$this->_response->VerifyMethod("POST");
			$this->_crud->ValidateProfile('create');

			$validationForm = $this->_crud->ValidateForm($body);
			if(!is_null($validationForm)) $this->_response->Response(400, $validationForm);

			$headers = new HttpDatabase($this->_config);
			$headers->SetModel($this->_model)->SetResoruceModel('Insert');
			$result = $headers->Post((array) $body);

			if((isset($result->statusCode) && $result->statusCode == 200) && $result->result->InsertedRecords > 0)
			{
				$data =  ["response" => str_replace("{{count}}", $result->result->InsertedRecords, $this->_lang->Crud->recordInserted)];
			}
			else
			{
				$this->_responseCode = 400;
				$data =  ["response" => str_replace("{{CODE}}", $result->result->error, $this->_lang->Crud->NonInserted)];
			}
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
		$this->_response->Response($this->_responseCode, $data);
	}

	/* [PUT METHOD] */
	// actualizar usuario
	public function MainEditRecord($body):void
	{
		try 
		{
			$this->_response->VerifyMethod("POST");

			$this->_crud->ValidateProfile('update');
			
			$validationForm = $this->_crud->ValidateForm($body);
			if(!is_null($validationForm)) $this->_response->Response($this->_responseCode, $validationForm);

			$headers = new HttpDatabase($this->_config);
			$headers->SetModel($this->_model)->SetResoruceModel('Update');
			$result = $headers->Put((array) $body);

			if((isset($result->statusCode) && $result->statusCode == 200) && $result->result->UpdatedRecords > 0)
			{
				$data =  ["response" => str_replace("{{count}}", $result->result->UpdatedRecords, $this->_lang->Crud->recordUpdated)];
			}
			else
			{
				$this->_responseCode = 400;
				$data =  ["response" => str_replace("{{CODE}}", $result->result->error, $this->_lang->Crud->NonUpdated)];
			}
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
		$this->_response->Response($this->_responseCode, $data);
	}

	/* [DELETE METHOD] */
	public function MainDeleteRecord():void
	{
		try 
		{
			if($this->_config->app->deleteType == 'logical')
			{
				$body = [":keyfield" => $this->_headers['id']];

				$this->_crud->ValidateProfile('delete');

				$headers = new HttpDatabase($this->_config);
				$headers->SetModel($this->_model)->SetResoruceModel('LogicalDelete');
				$result = $headers->Put((array) $body);
								
				$data = $this->_crud->LogicalDelete($result);
			}
			else
			{
				$params = [":keyfield" => $this->_headers['id']];

				$headers = new HttpDatabase($this->_config);
				$headers->SetModel($this->_model)->SetResoruceModel('Delete')->SetParams($params);
				$result = $headers->Delete();

				$data = $this->_crud->Delete($result);
			}
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
		$this->_response->Response($this->_responseCode, $data);
	}

	/* [GET METHOD] */
	public function MainModalColumns():void
	{
		try 
		{
			$this->_response->VerifyMethod("GET");
			$headers = new HttpDatabase($this->_config);
			$headers->SetModel($this->_model)->SetResoruceModel('All');
			$resultColumns = $headers->Get();

			$data = $this->_crud->GetDataModalColumns($resultColumns);
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
		$this->_response->Response($this->_responseCode, $data);
	}

	/* [GET METHOD] */
	public function MainModalSearch():void
	{
		try 
		{
			$this->_response->VerifyMethod("GET");
			$headers = new HttpDatabase($this->_config);
			$headers->SetModel($this->_model)->SetResoruceModel('Search');
			$resultSearch = $headers->Get();

			$headers2 = new HttpDatabase($this->_config);
			$headers2->SetModel($this->_model)->SetResoruceModel('DdlList');
			$resultList = $headers->Get();

			$data = $this->_crud->GetDataModalSearch($resultSearch, $resultList);
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
		$this->_response->Response($this->_responseCode, $data);
	}

	/* [GET METHOD] */
	public function MainModalMassiveContent():void
	{
		try 
		{
			$this->_response->VerifyMethod("GET");
			$data = [
				
			];
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
		$this->_response->Response($this->_responseCode, $data);
	}

	/* [GET METHOD] */
	public function MainModalDownloads():void
	{
		try 
		{
			$this->_response->VerifyMethod("GET");
			$headers = new HttpDatabase($this->_config);
			$headers->SetModel($this->_model)->SetResoruceModel('All');
			$resultDownloads = $headers->Get();

			$data = $this->_crud->GetDataModalDownloads($resultDownloads);
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
		$this->_response->Response($this->_responseCode, $data);
	}

	/* [GET METHOD] */
	public function MainDownloadReport():void
	{	
		try
		{
			$this->ValidateHeader('mode');
			$this->ValidateHeader('fields');

			$mode = json_decode($this->_headers['mode']);
			$fields = json_decode($this->_headers['fields']);

			$headers = new HttpDatabase($this->_config);
			$headers->SetModel($this->_model)->SetResoruceModel('All');
			
			$start = 0;
			if(isset($this->_headers['page']) && $this->_headers['page'] > 1)
			{
				$start = (($this->_config->app->rowsByPage * $this->_headers['page']) - $this->_config->app->rowsByPage ) + 1;
				$headers->start_from = $start;
			}	
			
			if(isset($this->_headers['orderby']))
				$headers->SetParams(['orderby' => $this->_headers['orderby']]);

			$result = $headers->Get();

			$file = new File();
			$file->ConvertRecords($fields, $result);
			$response = $file->ProcessMode($mode);

			$data = [
				"message" => $this->_lang->Success->FileGenerated,
				"fileBase64" => $response,
				"format" => $mode->options,
				"filename" => 'Report_'.str_replace('Model', '', $this->_model)."_".date("Ymd_his")
			];
		}
		catch(Exception $e)
		{
			$this->_responseCode = $e->getCode();
			$data = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
		finally
		{
			$this->_response->Response($this->_responseCode, $data);
		}
	}


	public function MainGetList():void
	{
		try 
		{
			$this->_response->VerifyMethod("GET");
			$this->ValidateHeader('id');
			$this->ValidateHeader('field');
			
			$params = [':keyfield' => $this->_headers['id'], 'fieldsList' => [$this->_headers['field']]];
			$headers2 = new HttpDatabase($this->_config);
			$headers2->SetModel($this->_model)->SetResoruceModel('list')->SetResourceGeneric('all')->SetParams($params);
			$result = $headers2->Get();

			$data = $result->result->{$this->_headers['field']};

			$options = [];
			for($i = 0; $i < count($data->data); $i++)
			{
				$options[$i] = [
					"value" => $data->data[$i]->{$data->columnName[0]},
					"text" => $data->data[$i]->{$data->columnName[1]}
				];
			}

			$response = [
				"fieldName" => $this->_headers['field'],
				"value" => "",
				"disabled" => "",
				"options" => $options,
				"metadata" => []
			];
		} 
		catch (Exception $e) 
		{
			$this->_responseCode = $e->getCode();
			$response = ['error' => ['group' => 'exception', 'message' => $e->getMessage()]];
		}
		$this->_response->Response($this->_responseCode, $response);
	}


	private function ValidateHeader($parameter)
	{
		if(!isset($this->_headers[$parameter]) || empty($this->_headers[$parameter])) 
			throw new Exception(str_replace("{{parameter}}", $parameter, $this->_lang->Validate->emptyParameter), 404);
	}
}