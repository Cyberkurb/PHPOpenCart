<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		// Analytics
		$this->load->model('setting/extension');
		$this->load->model('account/customer');
		$this->session->data['irclickid'] = '0';
        if($this->customer->getId()){
            $detailcustomer_info = $this->model_account_customer->getCustomer($this->customer->getId());
            $data['sha_email'] = sha1($detailcustomer_info['email']);
            $data['customer_id'] = $this->customer->getId();
        }
        else{
            $data['sha_email'] = '';
            $data['customer_id'] = '';
        }

		$data['analytics'] = array();

        if(!isset($this->session->data['irclickid'])){
			if($this->session->data['irclickid'] == '0'){
				if(isset($this->request->get['irclickid'])){
					$this->session->data['irclickid'] = $this->request->get['irclickid'];
				}
				else{
					$this->session->data['irclickid'] = '0';
				}
			}
			else{
				if(isset($this->request->get['irclickid'])){
					$this->session->data['irclickid'] = $this->request->get['irclickid'];
				}
				else{
					$this->session->data['irclickid'] = '0';
				}
			}
		}
		else{
			if($this->session->data['irclickid'] == '0'){
				if(isset($this->request->get['irclickid'])){
					$this->session->data['irclickid'] = $this->request->get['irclickid'];
				}
				else{
					$this->session->data['irclickid'] = '0';
				}
			}
			else{
				if(isset($this->request->get['irclickid'])){
					$this->session->data['irclickid'] = $this->request->get['irclickid'];
				}
				else{
					$this->session->data['irclickid'] = '0';
				}
			}
		}
        

		$analytics = $this->model_setting_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts('header');
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
		
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
		

                //=== ISL iBlogs
                $this->load->model('setting/setting');
                $this->config->load('isenselabs/iblogs');
                $module = $this->config->get('iblogs');

                $setting = $this->model_setting_setting->getSetting($module['code'], $this->config->get('config_store_id'));
                $module['setting'] = array_replace_recursive(
                    $module['setting'],
                    !empty($setting[$module['code'] . '_setting']) ? $setting[$module['code'] . '_setting'] : array()
                );

                $data['iblogs_metas'] = array();

                if ($module['setting']['status']) {
                    // OpenGraph
                    $iblogs_post_id = isset($this->request->get['post_id']) ? (int)$this->request->get['post_id'] : 0;
                    if ($iblogs_post_id && isset($this->session->data['iblogs_posts'])) {
                        $post = $this->session->data['iblogs_posts'];
                        unset($this->session->data['iblogs_posts']);

                        if ($iblogs_post_id == $post['post_id']) {
                            $this->load->model('tool/image');

                            $post['thumbnail'] = $post['image'] ? $post['image'] : $this->config->get('config_logo');
                            $post['thumbnail'] = $this->model_tool_image->resize($post['thumbnail'], 200, 200);

                            // General
                            $data['iblogs_metas']['property'] = array(
                                'og:type'        => 'blog',
                                'og:title'       => $post['title'],
                                'og:url'         => $post['canonical'],
                                'og:image'       => str_replace(' ', '%20', $post['thumbnail']),
                                'og:site_name'   => $this->config->get('config_name'),
                                'og:description' => $post['meta_description'] ? $post['meta_description'] : $post['excerpt']
                            );

                            // FB app id :: https://developers.facebook.com/tools/debug/
                            if ($module['setting']['post_view']['comment'] == 'facebook' && $module['setting']['post_view']['comment_facebook']) {
                                $data['iblogs_metas']['property']['fb:app_id'] = $module['setting']['post_view']['comment_facebook'];
                            }

                            // Titter card :: https://cards-dev.twitter.com/validator
                            $data['iblogs_metas']['name'] = array(
                                'twitter:card'        => 'summary',
                                'twitter:title'       => $post['title'],
                                'twitter:url'         => $post['canonical'],
                                'twitter:image'       => str_replace(' ', '%20', $post['thumbnail']),
                                'og:site_name'        => $this->config->get('config_name'),
                                'twitter:description' => $post['meta_description'] ? $post['meta_description'] : $post['excerpt']
                            );
                        }
                    }
                }
                //=== ISL iBlogs :: end
            
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		$data['menu'] = $this->load->controller('common/menu');

		return $this->load->view('common/header', $data);
	}
}
