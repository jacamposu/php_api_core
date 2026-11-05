<?php

class OptionsEdit
{
    
    public string $buttonName;
    public string $color;
    public string $event;
    public array $group;
    public string $icon;
    public array $params;
    public string $title;
    public string $type;

    public function SetParams(string $action, string $title, string $msg):array
    {
        $this->params = [
            'action' => $action,
            'title' => $title,
            'msg' => $msg
        ];
        return $this->params;
    }

    public function SetGroup(string $field, string $value):array
    {
        $this->group = [
            'field' => $field, 
            'value' => $value
        ];
        return $this->group;
    }
}