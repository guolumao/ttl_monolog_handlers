<?php

namespace Monolog\Handler;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
require_once DRUPAL_ROOT . '/includes/database/database.inc';

class TTLDatabaseHandler extends AbstractProcessingHandler
{
	private $table; 
   
    public function __construct($table, $level = Logger::NOTICE, $bubble = true)
	    {
	    	$this->table = $table;
	       
	        parent::__construct($level, $bubble);
	    }

    protected function write(array $record)
        {
        	$fields = array();
        	$variables = empty($record['context']['variables']) ? '' : serialize($record['context']['variables']);
        	$arr_msg = explode('&*$*&', $record['message']);
        	$fields['uid'] = $record['context']['uid'];
        	$fields['type'] = $record['channel'];
        	$fields['system_description'] = $arr_msg[0];
        	$fields['user_description'] = $arr_msg[1];
        	$fields['variables'] = $variables;
        	$fields['severity'] = constant('WATCHDOG_' . $record['level_name']);
        	$fields['link'] = $record['context']['link'];
        	$fields['request_uri'] = $record['context']['request_uri'];
        	$fields['referer'] = $record['context']['referer'];
        	$fields['hostname'] = $record['context']['ip'];
        	$fields['timestamp'] = date_timestamp_get($record['datetime']);
        	db_insert($this->table)->fields($fields)->execute();
        }
}
