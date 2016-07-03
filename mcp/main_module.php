<?php

namespace gresnet\categorization\mcp;

class main_module {
    
    var $u_action;
    
    function main($id, $mode)
    {
            global $config, $request, $template, $user;
            $user->add_lang('mcp/common');
            $this->tpl_name = 'template/mcp_main';
            $this->page_title = $user->lang('MCP_CATEGORIZE');
            
            $template->assign_vars(array(
                    'U_ACTION'				=> $this->u_action,
                    /*'ACME_DEMO_GOODBYE'		=> $config['acme_demo_goodbye'],*/
            ));
    }
}

