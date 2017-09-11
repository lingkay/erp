<?php
namespace Hris\WorkforceBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Datetime;

class AttendanceCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        //Create cron task to run php app/console gist:payroll:generate per day
        $this->setName('hris:workforce:attendance')
            ->setDescription('Create daily entries for attendance');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $wm = $this->getContainer()->get('hris_attendance');
        //Get Current Date
        $wm->generateAttendanceEntry();

    }
}