<?php

namespace gresnet\categorization\mcp;

class main_info {
    function module()
	{
		return array(
			'filename'	=> '\gresnet\categorize\main_module',
			'title'		=> 'MCP_CATEGORIZE',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'MCP_CATEGORIZE_MAIN',
					'auth'	=> 'gresnet/categorization && acl_a_board',
					'cat'	=> array('MCP_CATEGORIZE_MAIN')
				),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
    
}

