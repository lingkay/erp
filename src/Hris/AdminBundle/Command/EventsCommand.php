<?php
namespace Hris\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Datetime;

class EventsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        //Create cron task to run php app/console gist:payroll:generate per day
        $this->setName('hris:admin:events')
            ->setDescription('Create daily entries for events');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $evm = $this->getContainer()->get('hris_events');
        $evm->checkForEventsTomorrow();

        $config = $this->getContainer()->get('gist_configuration');
        $settings = $this->getContainer()->get('hris_settings');
        $hr_dept = $config->get('hris_hr_department');
        if ($hr_dept != null) 
        {
            $hr = $settings->getDepartment($hr_dept);
            $hr = $hr->getDeptHead();
            if ($hr != null) 
            {
                $evm->checkForBirthdaysTomorrow($hr);
            }
        }
    }
}