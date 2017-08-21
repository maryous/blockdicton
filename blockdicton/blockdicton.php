<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class BlockDicton extends Module {

    public function __construct() {

        // Variables du module
        $this->name = 'blockdicton';
        $this->tab = 'front_office_features';

        $this->version = '1.0';
        $this->author = 'Hasnae joudar';

        $this->need_instance = 0;

        $this->ps_versions_compliancy = array(
            'min' => '1.5',
            'max' => _PS_VERSION_
        );

        parent::__construct();

        // Affichage admin
        $this->displayName = $this->l('Dicton');
        $this->description = $this->l('Un jour, Un dicton !');
        $this->confirmUninstall = $this->l('Êtes vous certain de vouloir désinstaller ?');
    }

    public function install() {

        // Configuration de la table
        Configuration::updateValue('BLOCKDICTON_TABLE', 'dicton');

        // Variable de la table
        $table = Configuration::get('BLOCKDICTON_TABLE');

        // Création de la table
        $created = Db::getInstance()->Execute('CREATE TABLE ' . _DB_PREFIX_ . $table . ' (
            `mois` int(11) NOT NULL,
            `jour` int(11) NOT NULL,
            `saint` varchar(255) NOT NULL,
            `genre` boolean NOT NULL,
            `dicton` text NOT NULL,
            `conseil` text NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1');

        // Présaisie de 3 dictons en fonction d'aujourd'hui
        if ($created) {

            $date = new DateTime();

            Db::getInstance()->insert($table, array(
                'mois' => $date->format('n'),
                'jour' => $date->format('j'),
                'saint' => "Samson",
                'genre' => 1,
                'dicton' => "Il est trop tard pour acheter du bois, quand l\'homme souffle sur ses doigts.",
                'conseil' => "Il est temps de planter des arbres :)"
            ));

            $date->add(new DateInterval('P1D'));

            Db::getInstance()->insert($table, array(
                'mois' => $date->format('n'),
                'jour' => $date->format('j'),
                'saint' => 'Malo',
                'genre' => 1,
                'dicton' => "Qui voit Ouessant voit son sang.",
                'conseil' => "Il est temps de planter des fleurs :)"
            ));

            $date->add(new DateInterval('P1D'));

            Db::getInstance()->insert($table, array(
                'mois' => $date->format('n'),
                'jour' => $date->format('j'),
                'saint' => 'Brieuc',
                'genre' => 1,
                'dicton' => "Mieux vaut sagesse que richesse.",
                'conseil' => "Il est temps de planter des pommiers :)"
            ));
        }

        // Retour afin de vérifier si l'installation s'est bien passée
        return parent::install() &&
                $this->registerHook('leftColumn') &&
                $this->registerHook('header') && $created;
    }

    public function uninstall() {

        // Variable de la table
        $table = _DB_PREFIX_ . Configuration::get('BLOCKDICTON_TABLE');

        // Essai de désinstallation des données du parent
        $parent = parent::uninstall();

        // Suppression de la table
        if ($parent) {
            $deleted = Db::getInstance()->Execute('DROP TABLE ' . $table);
        }

        // Retour afin de vérifier si la désinstalltion s'est bien passée
        return $parent && configuration::deleteByName('BLOCKDICTON_TABLE') && $deleted;
    }

    public function hookDisplayLeftColumn($params) {

        // Variable de la table
        $table = _DB_PREFIX_ . Configuration::get('BLOCKDICTON_TABLE');

        // Récupération du moi et du jour actuelle
        $mois = (int) date('n');
        $jour = (int) date('j');

        // Requète pour récupérer le dicton, le saint et le conseil
        $sql = 'SELECT * FROM ' . $table . ' WHERE mois = ' . $mois . ' AND jour = ' . $jour;

        if ($row = Db::getInstance()->getRow($sql)) {

            // ajout du genre
            $saint = ($row['genre'] ? 'Saint' : 'Sainte') . ' ' . $row['saint'];

            // Passage de variable à smarty
            $this->context->smarty->assign(array(
                'BDictonSaint' => $saint,
                'BDictonDicton' => $row['dicton'],
                'BDictonConseil' => $row['conseil'],
                'BDictonLink' => $this->context->link->getModuleLink('blockdicton', 'display')
            ));
        }

        // Affichage du template
        return $this->display(__FILE__, 'blockdicton.tpl');
    }

    public function hookDisplayRightColumn($params) {

        // Effectuer comme sur la colonne de gauche
        return $this->hookDisplayLeftColumn($params);
    }
    public function hookDisplayTop($params) {

        // Effectuer comme sur la colonne de gauche
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookDisplayHeader() {

        // Ajout du css
        $this->context->controller->addCSS($this->_path . 'views/css/blockdicton.css', 'all');
    }

}
