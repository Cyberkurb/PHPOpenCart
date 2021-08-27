<?php
class ControllerAccountRecipe extends Controller {
	private $error = array();

	public function index() {
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/recipe', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}
		$this->load->model('account/recipe');

		$this->getList();
	}

	protected function getList() {
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['shoppinglist'] = array();

		$results = $this->model_account_recipe->getShoppingList($this->customer->getId());

		foreach ($results as $result) {
			$data['shoppinglist'][] = array(
                'shoppinglist_id' 	  	=> $result['shoppinglist_id'],
                'group_id'              => $result['shoppinglist_group_id'],
                'group_name'            => $result['group_name'],
                'recipe_name'    	    => $result['recipe_name'],
                'recipe_url'            => $this->url->link('product/product', 'product_id=' . $result['product_id'], true),
                'attribute_name'    	=> $result['attribute_name'],
                'attribute_text'        => $result['attribute_text'],
                'delete'                => $this->url->link('account/recipe/delete', 'shoppinglist_id='. $result['shoopinglist_id'], true),
                'got'                   => $this->url->link('account/recipe/purchase', 'shoppinglist_id='. $result['shoppinglist_id'], true)
			);
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/shopping_list', $data));
	}

	public function manualadd(){
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/recipe', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->document->setTitle($this->language->get('Add Shopping Item'));

		$this->load->model('account/recipe');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$entry = $this->model_account_recipe->addNonRecipe($this->customer->getId(), $this->request->post);
            if($entry !=0){
			 $this->session->data['success'] = 'List Updated';
			 $this->response->redirect($this->url->link('account/recipe', '', true));
            }
            else{
                $this->session->data['warning'] = 'Error on Updating';
            }
		}
		$this->getForm();
	}

	protected function getForm() {
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		$data['action'] = $this->url->link('account/recipe/manualadd', 'shoppinglist_id=' . $this->request->get['shoppinglist_id'], true);

		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$item_info = $this->model_account_recipe->getListItem($this->request->get['shoppinglist_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($item_info)) {
			$data['name'] = $item_info['name'];
		} else {
			$data['name'] = '';
		}

		$data['back'] = $this->url->link('account/recipe', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/item_form', $data));
	}

	protected function validateForm() {
		
		if ((utf8_strlen(trim($this->request->post['name'])) < 3) || (utf8_strlen(trim($this->request->post['name'])) > 250)) {
			$this->error['name'] = "There is a problem with your item.";
		}

		return !$this->error;
	}

	public function add() {
		
		$this->load->model('account/recipe');
		$data['product_id'] = $this->request->post['product_id'];
        $data['customer_id'] = $this->customer->getId();

        $add_recipe = $this->model_account_recipe->addFullRecipe($data);
        $this->response->redirect($this->url->link('account/recipe', '', true));
    }
    public function purchase() {
		
		$this->load->model('account/recipe');
        $data['customer_id'] = $this->customer->getId();
        $data['shoppinglist_id'] = $this->request->get['shoppinglist_id'];
        $data['status'] = 2; //Means that they checked it off their list

        $update_recipe = $this->model_account_recipe->ShoppingListUpdate($data);
        $this->response->redirect($this->url->link('account/recipe', '', true));
    }
    public function delete() {
		
		$this->load->model('account/recipe');
        $data['customer_id'] = $this->customer->getId();
        $data['shoppinglist_id'] = $this->request->get['shoppinglist_id'];
        $data['status'] = 3; //Means that they checked it off their list

        $update_recipe = $this->model_account_recipe->ShoppingListUpdate($data);
        $this->response->redirect($this->url->link('account/recipe', '', true));
    }

}