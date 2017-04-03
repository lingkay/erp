<?php
namespace Gist\ContactBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ContactCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('gist:contact:clear-orphans')
            ->setDescription('Clear all orphaned address and phones');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        
        $addresses = $em->getRepository('GistContactBundle:Address')->findAll();
        
        foreach($addresses as $address){
            $remove = true;
            $suppliers = $em->getRepository('GistPurchasingBundle:Supplier')->findAll();
            foreach($suppliers as $supplier){
                if($supplier->getAddresses()->contains($address)){
                    $remove = false;
                }
                if($supplier->getAddresses()->contains($address)){
                    $remove = false;
                }
            }
            
            if($remove){
                $em->remove($address);
            }
        }
        $text = "Deleted unused contacts.";
        $output->writeln($text);
    }
}