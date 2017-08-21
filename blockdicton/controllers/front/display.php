<?php

class BlockdictondisplayModuleFrontController extends ModuleFrontController {

    public function initContent() {

        parent::initContent();

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

        $this->setTemplate('display.tpl');
    }
}
