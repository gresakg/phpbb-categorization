<?php

namespace gresnet\categorization\controller;

class main {

    protected $request;
    
    protected $user;
    
    protected $model;
    
    public function __construct(\phpbb\request\request $request,
                                \phpbb\user $user,
                                \gresnet\categorization\model\Categorization $model) {
        $this->request = $request;
        $this->user = $user;
        $this->model= $model;
        
    }
    
    public function post() {
        $post = $this->request->get_super_global(\phpbb\request\request_interface::POST);
        $this->process($post);
        //return;
        $response = new \Symfony\Component\HttpFoundation\RedirectResponse($post['categorize_url']);
        $response->send();
    }
    
    protected function process($post) {
        $categories = array();
        $i=0;
        foreach($post['category'] as $cat) {
            $categories[$i]['topic_id'] = $post['categorize_topic_id'];
            $categories[$i]['category_id'] = $cat;
            $categories[$i]['primarna'] = ($post['glavna']==$cat)?1:0;
            $i++;
        }
        $this->model->store_categorization($categories);
        
    }
    
}