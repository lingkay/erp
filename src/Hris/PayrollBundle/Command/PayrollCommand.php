<?php
namespace Hris\PayrollBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PayrollCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        //Create cron task to run php app/console gist:payroll:generate per day
        $this->setName('gist:payroll:generate')
            ->setDescription('Generate payroll automatically');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pm = $this->getContainer()->get('hris_payroll');
        error_log('Running Payroll command');
        $pm->getDeductions();
        //Get Current Date

        //Generate entry to pay_payroll_period. reference pay_period. and settings for payroll date

        //loop employee - reference eworkforce service- get all active working employees only
            //Get earnings - referene payroll service reference attendance
            // get deductions referene payroll service  
            //insert pay_payroll row

        // end loop employee


    }
}