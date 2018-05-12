<?php
namespace CrudView\Action;

use CrudView\Dashboard\Dashboard;
use Crud\Action\BaseAction;
use Crud\Traits\ViewTrait;

class DashboardAction extends BaseAction
{
    use ViewTrait;

    protected $_defaultConfig = [
        'enabled' => true,
        'view' => null,
    ];

    /**
     * HTTP GET handler
     *
     * @return void|\Cake\Network\Response
     */
    protected function _get()
    {
        $pageTitle = $this->getConfig('scaffold.page_title', __d('CrudView', 'Dashboard'));
        $this->setConfig('scaffold.page_title', $pageTitle);
        $this->setConfig('scaffold.autoFields', false);
        $this->setConfig('scaffold.fields', ['dashboard']);
        $this->setConfig('scaffold.actions', []);

        $dashboard = $this->getConfig('scaffold.dashboard', new Dashboard($pageTitle));
        $subject = $this->_subject([
            'success' => true,
            'dashboard' => $dashboard,
        ]);

        $this->_trigger('beforeRender', $subject);

        $controller = $this->_controller();
        $controller->set('dashboard', $subject->dashboard);
        $controller->set('viewVar', 'dashboard');
        $controller->set('title', $subject->dashboard->get('title'));
    }
}
