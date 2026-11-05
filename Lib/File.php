<?php

class File{
    private array $records;
    private array $headers;
    private string $separatorTXT;

    public function __construct() 
    {
        $this->separatorTXT = "\t";
    }

    public function ConvertRecords($fields, $result)
    {
        $this->headers = $result->result->columnName;   

        /* CONTENIDO */
        $this->records = [];
        $i = 0;
        foreach($result->result->data as $record)
        {
            $registro = [];
            foreach($fields as $field => $value){
                if($value == 'on'){
                    $registro[$field] = $record->{$field};
                }
            }
            $this->records[$i] = $registro;
            $i++;
        }
    }

    public function ProcessMode($mode)
    {
        switch($mode->options){
            case 'excel':
                return $this->GenerateExcel();
            
            case 'pdf':
                return $this->GeneratePDF();

            case 'txt':
                return $this->GenerateTXT();
        }
    }

    private function GenerateExcel():string
    {
        return "excel generado";
    }

    private function GeneratePDF():string
    {
        return "pdf generado";
    }

    private function GenerateTXT():string
    {
        /* ENCABEZADO */
        $headers = "";
        foreach($this->headers as $header){
            $headers .= $header.$this->separatorTXT;
        }
        $headers = trim($headers,$this->separatorTXT);
        $headers = $headers."\n";

        /* CONTENIDO */
        $content = "";
        foreach($this->records as $record)
        {
            $line = implode($this->separatorTXT, $record);
            $content .= $line."\n";
        }

        // Abrir un flujo de memoria
        $flujo = fopen('php://memory', 'r+');
        fwrite($flujo, $headers);
        fwrite($flujo, $content);
        rewind($flujo);

        // Leer el contenido del flujo y codificarlo en base64
        $contenido_base64 = base64_encode(stream_get_contents($flujo));
        fclose($flujo);
        return $contenido_base64;
    }
}