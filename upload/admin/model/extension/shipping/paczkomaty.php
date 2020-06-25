<?php

class ModelExtensionShippingPaczkomaty extends Model
{
    public function install()
    {
        $this->db->query('CREATE TABLE `pp_paczkomaty` ( 
            `id` INT NOT NULL AUTO_INCREMENT ,  
            `province` VARCHAR(255) NOT NULL ,  
            `city` VARCHAR(255) NOT NULL ,  
            `machine` VARCHAR(255) NOT NULL ,  
            `postcode` VARCHAR(50) NOT NULL ,  
            `longitude` VARCHAR(50) NOT NULL ,  
            `latitude` VARCHAR(50) NOT NULL ,  
            `desctiption` VARCHAR(255) NOT NULL ,  
            `street` VARCHAR(255) NOT NULL ,  
            `building_number` VARCHAR(50) NOT NULL ,  
            `flat_number` VARCHAR(50) NOT NULL ,  
            `phone_number` VARCHAR(11) NOT NULL ,  
            `opening_hours` VARCHAR(255) NOT NULL ,  
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,    
            PRIMARY KEY  (`id`)) ENGINE = InnoDB;');
    }

    public function uninstall()
    {
        $this->db->query('DROP TABLE IF EXISTS `pp_paczkomaty`;');
    }

    public function clear()
    {
        $this->uninstall();
        $this->install();
    }

    public function addRows($machines)
    {
        $insert = '';
        foreach ($machines as $machine) {
            $insert .= ",\n('".implode("','", array_values($machine))."')";
        }
        $insert = ltrim($insert, ',');

        $this->db->query("INSERT INTO `pp_paczkomaty`(
                `province`,
                `city`,
                `machine`,
                `postcode`,
                `longitude`,
                `latitude`,
                `desctiption`,
                `street`,
                `building_number`,
                `flat_number`,
                `phone_number`,
                `opening_hours`) VALUES $insert");
    }
}
