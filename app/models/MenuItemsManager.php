<?php

/**
 * MenuItemsManager base class.
 */
class MenuItemsManager extends Nette\Object {
    
    /**
     * Database handling.     
     */
    public $database;
    public function __construct(Nette\Database\Connection $database) {
        $this->database = $database;
    }

    /**
     * Get all menuItems for current user ($idusers).
     * 
     * @param int $idusers
     * @return menuItems for current user 
     */
    public function getMenuItemsByIdusers($idusers) {
        if (is_numeric($idusers)) {
            $menuItems = $this->database->query('SELECT id, itemId, itemName, itemPublished FROM menuItems WHERE idusers=? ORDER BY itemId ASC', $idusers)->fetchAll();                                    
            return $menuItems;
        } else {            
            throw new \Nette\Application\ToolException('Unable to get MenuItems.
                    Wrong input. method: getMenuitemsByIdusers($idusers)', 500);
        }
    }
    
    /**
     * Get all published menuItems for current user ($idusers).
     * 
     * @param int $idusers
     * @return published menuItems for current user 
     */
    public function getPublishedMenuItemsByIdusers($idusers) {
        if (is_numeric($idusers)) {
            $menuItems = $this->database->query('SELECT id, itemId FROM menuItems WHERE idusers=? ORDER BY itemId ASC', $idusers)->fetchAll();                                             
            return $menuItems;               
        } else {            
            throw new \Nette\Application\ToolException('Unable to get MenuItems.
                    Wrong input. method: getPublishedMenuItemsByIdusers($idusers)', 500);
        }
    }
    
    /**
     * Get all published menuItems for current user ($subdomain).
     * 
     * @param string $subdomain
     * @return list of menuItems 
     */
    public function getPublishedMenuItemsBySubdomain($subdomain) {
        if (is_string($subdomain)) {
            $menuItems = $this->database->query('SELECT menuItems.itemId,  menuItems.itemName, menuItems.itemNameRouteCs from menuItems LEFT JOIN users ON menuItems.idusers = users.id WHERE subdomain=? AND itemPublished="yes" ORDER BY menuItems.itemId ASC', $subdomain)->fetchAll();            
            return $menuItems;           
        } else {            
            throw new \Nette\Application\ToolException('Unable to get MenuItems.
                    Wrong input. method: getPublishedMenuItemsBySubdomain($subdomain)', 500);
        }
    }    
    
    /**
     * Get menuItem by $itemId for current user ($idusers).
     * 
     * @param int $idusers
     * @param int $itemId
     * @return menuItem 
     */
    public function getMenuItemById($idusers, $itemId) {
        if (is_numeric($idusers) && is_numeric($itemId)) {
            $menuItem = $this->database->query('SELECT * FROM menuItems WHERE idusers=? AND itemId=?', $idusers, $itemId)->fetch();            
//                            table('menuItems')
//                            ->where('idusers', $idusers)
//                            ->where('itemId', $itemId)->fetch();
            return $menuItem;                        
        } else {            
            throw new \Nette\Application\ToolException('Unable to get MenuItem.
                    Wrong input. method: getMenuItemById($idusers, $itemId)', 500);
        }
    }

    /**
     * Update menuItem defined by menuItem $id.
     * 
     * @param data $dataArray 
     */
    public function updateItem($dataArray) {
        if ($dataArray) {
            $this->database->exec('UPDATE menuItems SET itemName=?, itemContent=?, itemNameRouteCs=? WHERE id=?', $dataArray[1], $dataArray[2], $dataArray[3], $dataArray[0]);            
        } else {            
            throw new \Nette\Application\ToolException('Unable to update current menuItem.
                    Wrong input. method: updateItem($dataArray)', 500);
        }
    }

    /**
     * Publish menuItem defined by menuItem $id.
     * 
     * @param data $dataArray 
     */
    public function publishItem($dataArray) {
        if ($dataArray) {
            $this->database->exec('UPDATE menuItems SET itemPublished=? WHERE id=?', $dataArray[1], $dataArray[0]);            
        } else {            
            throw new \Nette\Application\ToolException('Unable to publish current menuItem.
                    Wrong input. method: publishItem($dataArray)', 500);
        }
    }
   
    /**
     * Save last changes datetime for current menuItem (menuItem $id).
     * 
     * @param int $id 
     */
    public function saveLastChangesMenuItem($id) {
        if (is_numeric($id)) {
            $changeDateTime = date("Y-m-d H:i:s");
            $this->database->exec('UPDATE menuItems SET lastChange=? WHERE id=?', $changeDateTime, $id);                               
        } else {            
            throw new \Nette\Application\ToolException('Unable to update menuItem data.
                    Wrong input. method: saveLastChangesMenuItem($id)', 500);
        }
    }   
    
    /**
     * Add new menuItems set (6 menuItems).
     * 
     * @param int $idusers 
     */
    public function addNewMenuItemsSet($idusers){
        if (is_numeric($idusers)) {
            $this->database->exec('INSERT INTO menuItems', array(               
               'idusers' => $idusers,
               'itemId' => 1,
               'itemName' => 'Úvod',
               'itemContent' => '
                <p>
                        <span style="color:#ff0000;"><em>Na prvn&iacute; str&aacute;nce je vhodn&eacute; stručn&eacute; uv&eacute;st z&aacute;kladni informace o V&aacute;s, Va&scaron;&iacute; ambulanci, poskytovan&yacute;ch služb&aacute;ch. Např.:</em></span></p>
                <p>
                        &nbsp;</p>
                <h2>
                        <strong>V&iacute;tejte na na&scaron;ich str&aacute;nk&aacute;ch!</strong></h2>
                <p>
                        &nbsp;</p>
                <p>
                        Na&scaron;e ambulance poskytuje zdravotn&iacute; p&eacute;či již od roku 2000. Na&scaron;im c&iacute;lem jsou spokojen&iacute; pacienti, kter&yacute;m se vždy dostane odpovědn&eacute; p&eacute;če.</p>
                <p>
                        &nbsp;</p>
                <p>
                        Pokud m&aacute;te nějak&eacute; zdrvotn&iacute; pot&iacute;že nev&aacute;hejte n&aacute;s nav&scaron;t&iacute;vit na adrese uveden&eacute; v sekci Kontakt. Jme V&aacute;m k dispozici každ&yacute; v&scaron;edn&iacute; den od 7:00 do 16:00, viz Ordinačn&iacute; hodiny (n&iacute;že)</p>
                <p>
                        &nbsp;</p>
                <p>
                        &nbsp;</p>
                <p>
                        <span style="color:#ff0000;"><em>Můžete tak&eacute; uv&eacute;st např.</em></span></p>
                <h1>
                        <img alt="" src="http://mudrweb.cz/images/commonGallery/galerie/ilustracni_obrazky/3Dman/3DMUZ_004.jpg" style="width: 147px; height: 252px; float: right;" /></h1>
                <h2>
                        <strong>Ordinačn&iacute; hodiny</strong></h2>
                <table border="0" cellpadding="1" cellspacing="1" style="width: 309px; height: 128px">
                        <tbody>
                                <tr>
                                        <td>
                                                Ponděl&iacute;</td>
                                        <td>
                                                7-12</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                13-16</td>
                                </tr>
                                <tr>
                                        <td>
                                                &Uacute;ter&yacute;</td>
                                        <td>
                                                7-12</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                13-16</td>
                                </tr>
                                <tr>
                                        <td>
                                                Středa</td>
                                        <td>
                                                7-12</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                13-16</td>
                                </tr>
                                <tr>
                                        <td>
                                                Čtvrtek</td>
                                        <td>
                                                7-12</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                13-16</td>
                                </tr>
                                <tr>
                                        <td>
                                                P&aacute;tek</td>
                                        <td>
                                                7-12</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                13-16</td>
                                </tr>
                                <tr>
                                        <td>
                                                Sobota</td>
                                        <td>
                                                -</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                -</td>
                                </tr>
                                <tr>
                                        <td>
                                                Neděle</td>
                                        <td>
                                                -</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                -</td>
                                </tr>
                        </tbody>
                </table>
                <p>
                        &nbsp;</p>                   
               ',              
               'itemPublished' => 'yes', 
               'itemNameRouteCs' => 'uvod'
            ));       

            $this->database->exec('INSERT INTO menuItems', array(               
               'idusers' => $idusers,
               'itemId' => 2,
               'itemName' => 'O nás',
               'itemContent' => '
                <h1>
                        Person&aacute;l</h1>
                <p>
                        &nbsp;</p>
                <p>
                        <em><span style="color:#ff0000;">Zde můžete um&iacute;stit V&aacute;&scaron; stručn&yacute; životopis, jak rovněž životopisy Va&scaron;ich zaměstnaců.</span></em></p>
                <p>
                        &nbsp;</p>
                <p>
                        <em><span style="color:#ff0000;">Pro zpestřen&iacute; Va&scaron;&iacute; prezentace je vhodn&eacute; um&iacute;stit Va&scaron;i fotografii, popř. fotografie Va&scaron;&iacute;ch zaměstnanců.</span></em></p>                   
               ',              
               'itemPublished' => 'yes', 
               'itemNameRouteCs' => 'o-nas'
            ));                   
            
            $this->database->exec('INSERT INTO menuItems', array(               
               'idusers' => $idusers,
               'itemId' => 3,
               'itemName' => 'Služby',
               'itemContent' => '
                <h1>
                        Služby</h1>
                <p>
                        &nbsp;</p>
                <p>
                        <span style="color:#ff0000;"><em>Vložte popis poskytovan&yacute;ch služeb, popř&iacute;padě cen&iacute;k. Můžete popis doplnit ilustračn&iacute;mi obr&aacute;zky z na&scaron;&iacute; galerie.</em></span></p>                   
               ',              
               'itemPublished' => 'no', 
               'itemNameRouteCs' => 'sluzby'
            ));                

            $this->database->exec('INSERT INTO menuItems', array(               
               'idusers' => $idusers,
               'itemId' => 4,
               'itemName' => 'Smluvní pojišťovny',
               'itemContent' => '
                <h1>
                        Smluvn&iacute; poji&scaron;ťovny</h1>
                <p>
                        na&scaron;e pracovi&scaron;tě je smluvn&iacute;m partnerem n&aacute;sleduj&iacute;c&iacute;h poji&scaron;ťoven:</p>
                <p>
                        &nbsp;</p>
                <p>
                        <span style="color:#ff0000;"><em>(nehod&iacute;c&iacute; smažte)</em></span></p>
                <table border="0" cellpadding="1" cellspacing="1" style="width: 700px; height: 336px;">
                        <tbody>
                                <tr>
                                        <td>
                                                <h3>
                                                        <a href="http://www.ozp.cz/" target="_blank">Oborov&aacute; zdravotn&iacute; poji&scaron;ťovna zaměstnanců bank, poji&scaron;ťoven a stavebnictv&iacute; (207)</a></h3>
                                                <h3>
                                                        &nbsp;</h3>
                                                <h3>
                                                        <a href="http://www.rbp-zp.cz/" target="_blank">Rev&iacute;rn&iacute; bratrsk&aacute; pokladna (213)</a></h3>
                                                <h3>
                                                        &nbsp;</h3>
                                                <h3>
                                                        <a href="http://www.vozp.cz/default.aspx" target="_blank">Vojensk&aacute; zdravotn&iacute; poji&scaron;ťovna ČR (201)</a></h3>
                                                <h3>
                                                        &nbsp;</h3>
                                                <h3>
                                                        <a href="http://www.vzp.cz/cms/internet/cz/" target="_blank">V&scaron;eobecn&aacute; zdravotn&iacute; poji&scaron;ťovna ČR (111)</a></h3>
                                                <h3>
                                                        &nbsp;</h3>
                                                <h3>
                                                        <a href="http://www.zpskoda.cz/cs/" target="_blank">Zaměstnaneck&aacute; poji&scaron;ťovna &Scaron;KODA (209)</a></h3>
                                                <h3>
                                                        &nbsp;</h3>
                                                <h3>
                                                        <a href="http://www.zpagel.cz/" target="_blank">Zdravotn&iacute; poji&scaron;ťovna AGEL (227)</a></h3>
                                                <h3>
                                                        &nbsp;</h3>
                                                <h3>
                                                        <a href="http://www.zpma.cz/" target="_blank">Zdravotn&iacute; poji&scaron;ťovna METAL-ALIANCE (217)</a></h3>
                                                <h3>
                                                        &nbsp;</h3>
                                                <h3>
                                                        <a href="http://www.zpmvcr.cz/" target="_blank">Zdravotn&iacute; poji&scaron;ťovna ministerstva vnitra ČR (211)</a></h3>
                                                <h3>
                                                        &nbsp;</h3>
                                                <h3>
                                                        <a href="http://www.mediazp.cz/" target="_blank">Zdravotn&iacute; poji&scaron;ťovna M&eacute;dia (228)</a></h3>
                                                <h3>
                                                        &nbsp;</h3>
                                                <h3>
                                                        <a href="http://www.cnzp.cz/" target="_blank">Česk&aacute; n&aacute;rodn&iacute; zdravotn&iacute; poji&scaron;ťovna (222)</a></h3>
                                                <h3>
                                                        &nbsp;</h3>
                                                <h3>
                                                        <a href="http://www.hzp.cz/main/index.php" target="_blank">Česk&aacute; průmyslov&aacute; zdravotn&iacute; poji&scaron;ťovna (205)</a></h3>
                                        </td>
                                        <td>
                                                <img alt="" src="http://mudrweb.cz/images/commonGallery/galerie/ilustracni_obrazky/3Dman/3DMUZ_008.jpg" style="width: 346px; height: 346px;" /></td>
                                </tr>
                        </tbody>
                </table>
                <p>
                        &nbsp;</p>
               ',              
               'itemPublished' => 'yes', 
               'itemNameRouteCs' => 'smluvni-pojistovny'
            ));               
            
            $this->database->exec('INSERT INTO menuItems', array(               
               'idusers' => $idusers,
               'itemId' => 5,
               'itemName' => 'Kontakt',
               'itemContent' => '
                <h1>
                        Kontakt</h1>
                <table border="0" cellpadding="1" cellspacing="1" style="width: 650px; height: 268px;">
                        <tbody>
                                <tr>
                                        <td>
                                                <p>
                                                        &nbsp;</p>
                                                <p>
                                                        <span style="color:#ff0000;"><em>Uveďte kontaktn&iacute; informace.</em></span></p>
                                                <p>
                                                        <span style="color:#ff0000;"><em>Tip.: přidejte obr&aacute;zek budovy nebo mapu např. pomoc&iacute; serveru www.mapy.cz</em></span></p>
                                        </td>
                                        <td>
                                                <img alt="Náhled mapy" src="/images/mapacz.jpg" style="width: 350px; height: 210px;" /></td>
                                </tr>
                        </tbody>
                </table>
                <h1>
                        &nbsp;</h1>
                <h1>
                        Ordinačn&iacute; hodiny</h1>
                <table border="0" cellpadding="1" cellspacing="1" style="width: 310px; height: 136px;">
                        <caption>
                                &nbsp;</caption>
                        <tbody>
                                <tr>
                                        <td>
                                                Ponděl&iacute;</td>
                                        <td>
                                                7-12</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                13-16</td>
                                </tr>
                                <tr>
                                        <td>
                                                &Uacute;ter&yacute;</td>
                                        <td>
                                                7-12</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                13-16</td>
                                </tr>
                                <tr>
                                        <td>
                                                Středa</td>
                                        <td>
                                                7-12</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                13-16</td>
                                </tr>
                                <tr>
                                        <td>
                                                Čtvrtek</td>
                                        <td>
                                                7-12</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                13-16</td>
                                </tr>
                                <tr>
                                        <td>
                                                P&aacute;tek</td>
                                        <td>
                                                7-12</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                13-16</td>
                                </tr>
                                <tr>
                                        <td>
                                                Sobota</td>
                                        <td>
                                                -</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                -</td>
                                </tr>
                                <tr>
                                        <td>
                                                Neděle</td>
                                        <td>
                                                -</td>
                                        <td>
                                                &nbsp;</td>
                                        <td>
                                                -</td>
                                </tr>
                        </tbody>
                </table>
                <p>
                        &nbsp;</p>
               ',              
               'itemPublished' => 'yes', 
               'itemNameRouteCs' => 'kontakt'
            ));                
            
            $this->database->exec('INSERT INTO menuItems', array(               
               'idusers' => $idusers,
               'itemId' => 6,
               'itemName' => 'Volná položka',
               'itemContent' => '
                <p>...</p>
               ',              
               'itemPublished' => 'no', 
               'itemNameRouteCs' => 'volna-polozka'
            ));             
            
//            $i = 2;
//            for ($i = 2; $i < 7; $i++) {
//               $itemNameRouteCs = 'polozka' . $i;
//               $this->database->exec('INSERT INTO menuItems', array(               
//                   'idusers' => $idusers,
//                   'itemId' => $i,
//                   'itemName' => 'Položka' . $i,
//                   'itemContent' => '',              
//                   'itemPublished' => 'no',                
//                   'itemNameRouteCs' => $itemNameRouteCs
//               )); 
//            }            
        } else {            
            throw new \Nette\Application\ToolException('Unable to add new menuItems set.
                    Wrong input. method: addNewMenuItemsSet($idusers)', 500);
        }        
    }
}
