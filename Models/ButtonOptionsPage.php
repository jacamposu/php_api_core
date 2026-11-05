<?php

class ButtonOptionsPage
{
    public string $buttonName;
    public string $icon;
    public Params $params;
    public string $title;
    public string $type;
    public string $url;
    public string $event;
    public string $color;
    public string $group;
    public string $mode;

    public function SetType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    public function SetIcon(string $icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function SetEvent(string $event)
    {
        $this->event = $event;
        return $this;
    }

    public function SetParams(array $params)
    {
        $this->params = new Params();
        $this->params->url = isset($params['url']) ? $params['url'] : '';
        isset($params['text']) ? $this->params->text = $params['text'] : '';
        isset($params['btnYes']) ? $this->params->btnYes = $params['btnYes'] : '';
        isset($params['btnCancel']) ? $this->params->btnCancel = $params['btnCancel'] : '';
        isset($params['title']) ? $this->params->btnCancel = $params['title'] : '';
        return $this;
    }

    public function SetTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function SetButtonName(string $buttonName)
    {
        $this->buttonName = $buttonName;
        return $this;
    }

    public function SetColor(string $color)
    {
        $this->color = $color;
        return $this;
    }

    public function SetGroup(string $group)
    {
        $this->group = $group;
        return $this;
    }

    public function SetMode(string $mode)
    {
        $this->mode = $mode;
        return $this;
    }
}