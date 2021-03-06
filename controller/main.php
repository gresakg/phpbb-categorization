<?php

namespace gresnet\categorization\controller;

class main {

    protected $request;
    
    protected $user;
    
    protected $model;
    
    public function __construct(\phpbb\request\request $request,
                                \phpbb\user $user,
                                \phpbb\auth\auth $auth,
                                \gresnet\categorization\model\Categorization $model) {
        $this->request = $request;
        $this->user = $user;
        $this->model= $model;
        $this->auth = $auth;
        
    }
    
    public function post() {
        $this->auth->acl($this->user->data);
        if ( ! $this->auth->acl_getf_global('m_') ) {
            throw new \phpbb\exception\http_exception(404,"Strani ni mogoče najti.");
        }
        $post = $this->request->get_super_global(\phpbb\request\request_interface::POST);
        $this->process($post);
        //return;
        $response = new \Symfony\Component\HttpFoundation\RedirectResponse($post['categorize_url']);
        $response->send();
    }
    
    protected function process($post) {
        $categories = array();
        $primarna = false;
        $i=0;
        foreach($post['category'] as $cat) {
            $categories[$i]['topic_id'] = $post['categorize_topic_id'];
            $categories[$i]['category_id'] = $cat;
            if($post['glavna'] == $cat) {
                $categories[$i]['primarna'] = 1;
                $primarna = true;
            } else  {
                $categories[$i]['primarna'] = 0;
            }  
            $i++;
        }
        if(!$primarna) {
            $categories[$i]['topic_id'] = $post['categorize_topic_id'];
            $categories[$i]['category_id'] = $post['glavna'];
            $categories[$i]['primarna'] = 1;
        }
        $this->model->store_categorization($categories);
        
    }
    
}