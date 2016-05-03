<?php

namespace Catalyst\UserBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Catalyst\UserBundle\Entity\User;
use Catalyst\ValidationException;

class UserController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cat_user_user';
        $this->title = 'User';

        $this->list_title = 'Users';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new User();
    }
    
    protected function getObjectLabel($obj)
    {
        return $obj->getUsername();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');

        return array(
            $grid->newColumn('Username', 'getUsername', 'username'),
            $grid->newColumn('Email', 'getEmail', 'email'),
            $grid->newColumn('Name', 'getName', 'name'),
            $grid->newColumn('Roles', 'getGroupsText', 'id', 'o', null, false),
            $grid->newColumn('Last Login', 'getLastLoginText', 'lastLogin', 'o', null, false),
            $grid->newColumn('Status', 'getEnabledText', 'enabled', 'o', null, false),
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->get('catalyst_user');

        // enabled options
        $params['enabled_opts'] = array(
            1 => 'Enabled',
            0 => 'Disabled'
        );

        //branch opts
        // $inv = $this->get('catalyst_inventory');
        // $params['wh_opts'] = $inv->getWarehouseOptions();

        // groups
        $params['group_opts'] = $um->getGroupOptions();

        // user groups
        $ug_opts = array();
        if ($user != null)
        {
            $ugroups = $user->getGroups();
            foreach ($ugroups as $ug)
                $ug_opts[$ug->getID()] = $ug->getName();
        }
        $params['ug_opts'] = $ug_opts;

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $uc = $this->get('catalyst_user');

        // TODO: validation check for email
        // check if username is set then set username, else throw exception
        if (strlen($data['username']) > 0)
            $o->setUsername($data['username']);
        else
            throw new ValidationException('Cannot leave username blank');

        $o->setEmail($data['email']);
        $o->setName($data['name']);

        // status / enabled
        if ($data['enabled'] == 1)
            $o->setEnabled(1);
        else
            $o->setEnabled(0);

        // groups
        $o->clearGroups();
        if (isset($data['groups']))
        {
            foreach ($data['groups'] as $gid)
            {
                $group = $uc->findGroup($gid);
                if ($group != null)
                    $o->addGroup($group);
            }
        }

        //branch options        
        // $inv = $this->get('catalyst_inventory');
        // $wh = $inv->findWarehouse($data['warehouse_id']);
        // if($wh == null)
        //     throw new ValidationException('Could not find branch specified.');
        // $o->setWarehouse($wh);

        // check if we need to have password
        if ($is_new)
        {
            if (strlen($data['pass1']) <= 0)
                throw new ValidationException('Cannot leave password blank');

            if (strlen($data['pass2']) <= 0)
                throw new ValidationException('Cannot leave password blank');
        }

        // check if passwords match
        if (strlen($data['pass1']) > 0)
        {
            if ($data['pass1'] != $data['pass2'])
                throw new ValidationException('Passwords do not match');

            $um = $this->container->get('fos_user.user_manager');
            $o->setPlainPassword($data['pass1']);
            $um->updatePassword($o);
        }
    }

    public function toData()
    {
        $data = array(
            'warehouse_id' => $o->getWarehouse()->getID(),
            );

        return $data;
    }
}
