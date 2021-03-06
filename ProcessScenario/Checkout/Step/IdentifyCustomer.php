<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\CommerceBundle\ProcessScenario\Checkout\Step;

use Vespolina\CommerceBundle\Process\AbstractProcessStep;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class IdentifyCustomer extends AbstractProcessStep
{
    protected $process;

    public function init($firstTime = false)
    {
        $this->setDisplayName('identify customer');
    }

    public function execute(&$context)
    {
        $customer = $this->getCustomer();

        // If we already have a customer, this step is considered to be complete
        if (null != $customer) {
            $this->getContext()->set('customer', $customer);
            $this->complete();
        } else {
            $controller = $this->getController('Vespolina\CommerceBundle\Controller\Process\IdentifyCustomerController');
            $controller->setProcessStep($this);
            $controller->setContainer($this->process->getContainer());

            return $controller->executeAction();
        }
    }

    public function getName()
    {
        return 'identify_customer';
    }

    protected function getCustomer()
    {
        //First attempt to load the customer from the process container
        $customer = $this->getContext()->get('customer');

        if (null == $customer) {

            //If this fails get it from the user session
            $customer = $this->process->getContainer()->get('session')->get('customer');
        }

        return $customer;
    }
}
