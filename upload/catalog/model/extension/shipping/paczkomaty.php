<?php

class ModelExtensionShippingPaczkomaty extends Model
{
    public function getQuote($address)
    {
        $this->load->language('extension/shipping/paczkomaty');

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."zone_to_geo_zone WHERE geo_zone_id = '".(int) $this->config->get('paczkomaty_geo_zone_id')."' AND country_id = '".(int) $address['country_id']."' AND (zone_id = '".(int) $address['zone_id']."' OR zone_id = '0')");

        if (!$this->config->get('paczkomaty_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $quote_data = array();

            $cost = $this->config->get('paczkomaty_cost');
            $cost1 = $this->config->get('paczkomaty_cost1');
            $cost2 = $this->config->get('paczkomaty_cost2');
            

            //gabaryt A
            if($this->config->get('paczkomaty_cost')){
                $quote_data['paczkomaty'] = array(
                    'code' => 'paczkomaty.paczkomaty',
                    'title' => sprintf($this->language->get('text_description'),'rozmiar 8x38x64cm',(isset($this->session->data['inpost'])?"<br/>[{$this->session->data['inpost']}]":'')),
                    'cost' => $cost,
                    'tax_class_id' => $this->config->get('paczkomaty_tax_class_id'),
                    'text' => $this->currency->format($this->tax->calculate($cost, $this->config->get('paczkomaty_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']),
                );
            }
            

            //gabaryt B
            if($this->config->get('paczkomaty_cost1')){
                $quote_data['paczkomaty1'] = array(
                    'code' => 'paczkomaty.paczkomaty1',
                    'title' => sprintf($this->language->get('text_description'),'rozmiar 19x38x64cm',(isset($this->session->data['inpost'])?"<br/>[{$this->session->data['inpost']}]":'')),
                    'cost' => $cost1,
                    'tax_class_id' => $this->config->get('paczkomaty_tax_class_id'),
                    'text' => $this->currency->format($this->tax->calculate($cost1, $this->config->get('paczkomaty_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']),
                 );
            }
            

            //gabaryt C
            if($this->config->get('paczkomaty_cost2')){
                $quote_data['paczkomaty2'] = array(
                    'code' => 'paczkomaty.paczkomaty2',
                    'title' => sprintf($this->language->get('text_description'),'rozmiar 41x38x64cm',(isset($this->session->data['inpost'])?"<br/>[{$this->session->data['inpost']}]":'')),
                    'cost' => $cost2,
                    'tax_class_id' => $this->config->get('paczkomaty_tax_class_id'),
                    'text' => $this->currency->format($this->tax->calculate($cost2, $this->config->get('paczkomaty_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency']),
                );
            }
            

            $method_data = array(
                'code' => 'paczkomaty',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('paczkomaty_sort_order'),
                'error' => false,
            );
        }

        return $method_data;
    }

    public function getProvince()
    {
        $query = $this->db->query('SELECT DISTINCT province FROM `pp_paczkomaty` WHERE 1  ORDER BY `province`');
        if ($query->num_rows) {
            return $query->rows;
        } else {
            return false;
        }
    }

    public function getCity($province_id)
    {
        $query = $this->db->query("SELECT DISTINCT city FROM `pp_paczkomaty` WHERE province LIKE '".$this->db->escape($province_id)."' ORDER BY `city`");
        if ($query->num_rows) {
            return $query->rows;
        } else {
            return false;
        }
    }

    public function getMachines($city_id)
    {
        $query = $this->db->query("SELECT * FROM `pp_paczkomaty` WHERE city LIKE '".$this->db->escape($city_id)."' GROUP BY machine ORDER BY `machine`");

        if ($query->num_rows) {
            return $query->rows;
        } else {
            return false;
        }
    }
}
