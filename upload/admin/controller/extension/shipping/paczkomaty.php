<?php

class ControllerExtensionShippingPaczkomaty extends Controller
{
    private $error = array();

    public function index()
    {
        if (substr(VERSION, 0, 7) <= '2.3.0.2') {
            $token_name = 'token';
            $prefix = '';
        } else {
            $token_name = 'user_token';
            $prefix = 'shipping_';
        }

        $this->load->language('extension/shipping/paczkomaty');

        $data = [
            'heading_title' => 'Paczkomaty inPost',
            // Text
            'text_shipping' => 'Wysyłka',
            'text_success' => 'Sukces: Zmiany zostały zapisane!',
            'text_edit' => 'Edycja wysyłki Stała stawka',
            'text_none' => ' --- Brak --- ',
            'text_all_zones' => ' --- Wybierz --- ',
            'text_disabled' => 'Wyłączony(a)',
            'text_enabled' => 'Włączony(a)',

            // Entry
            'entry_cost' => 'Koszt',
            'entry_tax_class' => 'Klasa podatków',
            'entry_geo_zone' => 'Strefa geograficzna',
            'entry_status' => 'Status',
            'entry_sort_order' => 'Kolejność wyświetlania',
            ];

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        if (substr(VERSION, 0, 7) <= '2.3.0.2') {
            $back = 'extension/extension';
        } else {
            $back = 'marketplace/extension';
        }
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting($prefix.'paczkomaty', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link($back, $token_name.'='.$this->session->data[$token_name].'&type=shipping', true));
        }
        $data[$token_name] = $this->session->data[$token_name];
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', $token_name.'='.$this->session->data[$token_name], true),
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link($back, $token_name.'='.$this->session->data[$token_name].'&type=shipping', true),
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/shipping/paczkomaty', $token_name.'='.$this->session->data[$token_name], true),
        );

        $data['action'] = $this->url->link('extension/shipping/paczkomaty', $token_name.'='.$this->session->data[$token_name], true);

        $data['cancel'] = $this->url->link($back, $token_name.'='.$this->session->data[$token_name].'&type=shipping', true);

        if (isset($this->request->post[$prefix.'paczkomaty_cost'])) {
            $data['shipping_paczkomaty_cost'] = $this->request->post[$prefix.'paczkomaty_cost'];
        } else {
            $data['shipping_paczkomaty_cost'] = $this->config->get('paczkomaty_cost');
        }

        if (isset($this->request->post[$prefix.'paczkomaty_cost1'])) {
            $data['shipping_paczkomaty_cost1'] = $this->request->post[$prefix.'paczkomaty_cost1'];
        } else {
            $data['shipping_paczkomaty_cost1'] = $this->config->get('paczkomaty_cost1');
        }

        if (isset($this->request->post[$prefix.'paczkomaty_cost2'])) {
            $data['shipping_paczkomaty_cost2'] = $this->request->post[$prefix.'paczkomaty_cost2'];
        } else {
            $data['shipping_paczkomaty_cost2'] = $this->config->get('paczkomaty_cost2');
        }

        if (isset($this->request->post[$prefix.'paczkomaty_tax_class_id'])) {
            $data['shipping_paczkomaty_tax_class_id'] = $this->request->post[$prefix.'paczkomaty_tax_class_id'];
        } else {
            $data['shipping_paczkomaty_tax_class_id'] = $this->config->get($prefix.'paczkomaty_tax_class_id');
        }

        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        if (isset($this->request->post[$prefix.'paczkomaty_geo_zone_id'])) {
            $data['shipping_paczkomaty_geo_zone_id'] = $this->request->post[$prefix.'paczkomaty_geo_zone_id'];
        } else {
            $data['shipping_paczkomaty_geo_zone_id'] = $this->config->get($prefix.'paczkomaty_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post[$prefix.'paczkomaty_status'])) {
            $data['shipping_paczkomaty_status'] = $this->request->post[$prefix.'paczkomaty_status'];
        } else {
            $data['shipping_paczkomaty_status'] = $this->config->get($prefix.'paczkomaty_status');
        }

        if (isset($this->request->post[$prefix.'paczkomaty_sort_order'])) {
            $data['shipping_paczkomaty_sort_order'] = $this->request->post[$prefix.'paczkomaty_sort_order'];
        } else {
            $data['shipping_paczkomaty_sort_order'] = $this->config->get($prefix.'paczkomaty_sort_order');
        }

        if (isset($this->request->post[$prefix.'paczkomaty_apikey'])) {
            $data['shipping_paczkomaty_apikey'] = $this->request->post[$prefix.'paczkomaty_apikey'];
        } else {
            $data['shipping_paczkomaty_apikey'] = $this->config->get($prefix.'paczkomaty_apikey');
        }

        $data['prefix'] = $prefix;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        if (substr(VERSION, 0, 7) <= '2.3.0.2') {
            $this->response->setOutput($this->load->view('extension/shipping/paczkomaty.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('extension/shipping/paczkomaty', $data));
        }
    }

    public function install()
    {
        $this->load->model('extension/shipping/paczkomaty');
        $this->model_extension_shipping_paczkomaty->install();
    }

    public function uninstall()
    {
        $this->load->model('extension/shipping/paczkomaty');
        $this->model_extension_shipping_paczkomaty->uninstall();
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/shipping/paczkomaty')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function upload()
    {
    }

    private function getContent($page = 1, $per_page = 999)
    {
        $url = 'https://api-shipx-pl.easypack24.net/v1/points?per_page='.$per_page.'&page='.$page;
        $content = file_get_contents($url);

        return json_decode($content, true);
    }

    private function addPoints($list)
    {
        if (!empty($list)) {
            $data = [];
            if (isset($list)) {
                foreach ($list as $row) {
                    $data[] = (array) (new Points($row));
                }
            }

            $this->model_extension_shipping_paczkomaty->addRows($data);
        }
    }

    public function refresh()
    {
        $this->load->model('extension/shipping/paczkomaty');
        $this->model_extension_shipping_paczkomaty->clear();
        $list = $this->getContent(1);

        $total_page = isset($list['total_pages']) ? (int) $list['total_pages'] : 0;

        if (isset($list['items'])) {
            $this->addPoints($list['items']);
        }

        if ($total_page) {
            for ($i = 2; $i < $total_page; ++$i) {
                $list = $this->getContent($i);
                if (isset($list['items'])) {
                    $this->addPoints($list['items']);
                }
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode(['result' => true]));
    }

    public function getData($param)
    {
        $data = [];
        if (!empty($param)) {
            foreach ($param as $row) {
                $address = $row->address_details;
                $privince = mb_strtolower($address->province, 'UTF-8');
                $city = mb_strtolower($address->city, 'UTF-8');
                $data[$privince][$city][] = [
                    'post_code' => $address->post_code,
                    'street' => $address->street,
                    'building_number' => $address->building_number,
                    'flat_number' => $address->flat_number,
                    'name' => $row->name,
                    'latitude' => $row->location->latitude,
                    'longitude' => $row->location->longitude,
                ];
            }
        }

        return $data;
    }

    public function getCity($param)
    {
        $data = [];
        if (!empty($param)) {
            foreach ($param as $row) {
                $address = $row->address_details;
                $privince = mb_strtolower($address->province, 'UTF-8');
                if (!in_array($privince, $data)) {
                    $data[] = $privince;
                }
            }
        }

        return $data;
    }

    public function getMachines($param)
    {
    }
}

class Points
{
    public function __construct($data)
    {
        $this->province = isset($data['address_details']['province']) ? addslashes($data['address_details']['province']) : '';
        $this->city = isset($data['address_details']['city']) ? addslashes($data['address_details']['city']) : '';
        $this->machine = isset($data['name']) ? addslashes($data['name']) : '';
        $this->postcode = isset($data['address_details']['post_code']) ? addslashes($data['address_details']['post_code']) : '';
        $this->latitude = isset($data['location']['latitude']) ? addslashes($data['location']['latitude']) : '';
        $this->longitude = isset($data['location']['longitude']) ? addslashes($data['location']['longitude']) : '';
        $this->description = isset($data['location_description']) ? addslashes($data['location_description']) : '';
        $this->street = isset($data['address_details']['street']) ? addslashes($data['address_details']['street']) : '';
        $this->building_number = isset($data['address_details']['building_number']) ? addslashes($data['address_details']['building_number']) : '';
        $this->flat_number = isset($data['address_details']['flat_number']) ? addslashes($data['address_details']['flat_number']) : '';
        $this->phone_number = isset($data['phone_number']) ? addslashes($data['phone_number']) : '';
        $this->opening_hours = isset($data['opening_hours']) ? addslashes($data['opening_hours']) : '';

        return $this;
    }
}
