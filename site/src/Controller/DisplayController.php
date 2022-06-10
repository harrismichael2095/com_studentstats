<?php

namespace MichaelHarris\Component\studentstats\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;

/**
 * @package     Joomla.Site
 * @subpackage  com_studentstats
 *
 * @copyright   Copyright (C) 2020 John Smith. All rights reserved.
 * @license     GNU General Public License version 3; see LICENSE
 */

/**
 * studentstats Component Controller
 * @since  0.0.2
 */
class DisplayController extends BaseController {
    
    public function display($cachable = false, $urlparams = array()) {        
        $document = Factory::getDocument();
        $document->setTitle('Student Statistics');
        $app = Factory::getApplication();
        $menu = $app->getMenu();
        $items = $menu->getMenu();
        foreach ($items as $item) {
            if($item->title == 'Student Statistics') {
                $menu->setActive($item->id);
            }
        }
        $viewName = $this->input->getCmd('view', 'login');
        $viewFormat = $document->getType();
        $view = $this->getView($viewName, $viewFormat);
        $view->document = $document;
        $view->display();
    }
    
}