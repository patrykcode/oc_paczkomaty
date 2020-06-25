<?php

class ControllerExtensionShippingPaczkomaty extends Controller
{
    public function index()
    {
        $this->load->model('extension/shipping/paczkomaty');

        $data['provinces'] = $this->model_extension_shipping_paczkomaty->getProvince();

        $first = isset($data['provinces'][0]['province']) ? $data['provinces'][0]['province'] : '-- none --';
        $data['citys'] = $this->model_extension_shipping_paczkomaty->getCity($first);

        if (substr(VERSION, 0, 7) <= '2.3.0.2') {
            $json['view'] = $this->load->view('extension/shipping/paczkomaty.tpl', $data, true);
        } else {
            $json['view'] = $this->load->view('extension/shipping/paczkomaty', $data, true);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function city()
    {
        $this->load->model('extension/shipping/paczkomaty');

        $json = array();
        if (!empty($this->request->get['province'])) {
            $json['citys'] = $this->model_extension_shipping_paczkomaty->getCity($this->request->get['province']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function machine()
    {
        $this->load->model('extension/shipping/paczkomaty');

        $json = array();
        if (!empty($this->request->get['citys'])) {
            $json['machines'] = $this->model_extension_shipping_paczkomaty->getMachines($this->request->get['citys']);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function save()
    {
        if (isset($this->session->data['inpost'])) {
            unset($this->session->data['inpost']);
        }
        $json = array();

        if (!empty($this->request->get['machine'])) {
            $paczkomat = $this->db->escape($this->request->get['machine']);
            $this->session->data['inpost'] = $paczkomat;

            if (isset($this->session->data['shipping_methods']['paczkomaty'])) {
                $this->session->data['shipping_methods']['paczkomaty']['quote']['paczkomaty']['title'] = "Paczkomaty - \n[$paczkomat]";
            }

            $json['error'] = false;
        } else {
            $json['error'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function check()
    {
        $json = [
            'paczkomat' => isset($this->session->data['inpost']),
            'paczkomat_' => isset($this->session->data['inpost']) ? $this->session->data['inpost'] : 'brak',
        ];
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
