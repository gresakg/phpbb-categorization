<?php

namespace gresnet\categorization\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface {

    protected $template;
    
    protected $user;
    
    protected $model;
    
    protected $acl;


    
    public function __construct(\phpbb\template\template $template,
                                \phpbb\user $user,
                                \gresnet\categorization\model\Categorization $model,
                                \phpbb\auth\auth $acl) {
        $this->template = $template;
        $this->user = $user;
        $this->model = $model;
        $this->acl = $acl;
        
    }

    /**
     * Event subscription
     * @return array of event to listener methods mappings
     */
    public static function getSubscribedEvents() {
        return array(
            'core.viewtopic_assign_template_vars_before' => 'assign_variables',
            'core.user_setup' => 'mod_check',
           );
    }
    
    public function assign_variables($e) {
       $topic_categories = $this->get_selected_categories($e['topic_id']);
       if(count($topic_categories) > 0) {
           $categorized = true;
       } else {
           $categorized = false;
       }
       $categories = $this->get_forum_categories($e['forum_id']);
       $this->template->assign_vars(array(
           'categorization' => true,
           'topic_categories' => $topic_categories, 
           'is_categorized' => $categorized,
           'categories' => $categories));
    }
    
    protected function get_selected_categories($topic_id) {
        $categories = $this->model->get_topic_categories($topic_id);
        return $categories;

    }
    
    protected function get_forum_categories($forum_id = false) {
        $categories = $this->model->get_forum_categories($forum_id);
        $result = array(); 
        $parents = $this->get_children($categories,0);
        foreach($parents as $parent) {
            $result[] = $parent;
            $children = $this->get_children($categories, $parent['category_id']);
            foreach($children as $child) {
                $result[] = $child;
                $grandchildren = $this->get_children($categories,$child['category_id']);
                foreach($grandchildren as $grandchild) {
                    $result[] = $grandchild;
                }
            }
        }
        
        return $result;
    }
    
    protected function get_children($categories, $category_id = 0) {
        $result = array();
        foreach($categories as $cat) {
            if($cat['parent_id'] == $category_id) {
                $result[] = $cat;
            }
        }
        return $result;
    }
    
    public function mod_check()
    {
        $this->acl->acl($this->user->data);

        if ( $this->acl->acl_getf_global('m_') )
        {
            $this->template->assign_vars(array(
                'USER_IS_MOD'	=> 1,
            ));	
        }

    }
}
