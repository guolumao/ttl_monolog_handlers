<?php

namespace Monolog\Handler;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

require_once DRUPAL_ROOT . '/includes/mail.inc';

class TTLContribEmailHandler extends AbstractProcessingHandler
{
    private $module;
    private $key;
    private $to;
    private $language;
    private $from;
    private $params = array();

    public function __construct($module, $key, $to, $language, $params, $from, $level = Logger::ERROR, $bubble = true){
        $this->module = $module;
        $this->key = $key;
        $this->to = $to;
        $this->language = $language;
        $this->params = $params;
        $this->from = $from; 

        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
        {
            drupal_mail($this->module, $this->key, $this->to, $this->language, $this->params, $this->from, TRUE);
        }
}
