<?php

namespace Gist\PdfBundle\Model;

use Doctrine\ORM\EntityManager;
use Ensepar\Html2pdfBundle\Factory\Html2pdfFactory;
use Symfony\Component\HttpFoundation\Response;

class PdfManager 
{
    protected $em;
    protected $container;
    protected $pdf;
    protected $pdf_container;
    
    public function __construct(EntityManager $em, $container)
    {
        $this->em = $em;
        $this->container = $container;
    }
    
    public function newPdf($format = 'page'){
       $this->pdf_container = $this->container->get('html2pdf_factory');
        
       switch ($format){
        case 'barcode':
            $this->pdf = $this->pdf_container->create('L',array(30,75), 'en', true, 'UTF-8', array(2, 2, 2, 2));
            $this->pdf->pdf->setDisplayMode('real');
            break;
        case 'page' :
        default: $this->pdf = $this->pdf_container->create('P', 'A4', 'en', true, 'UTF-8', array(10, 15, 10, 15));
            break;
            
        }
       
    }
    
    public function setFormat($format){
       
    }
    
    public function printPdf($html){
        $this->pdf->writeHTML($html);
        $content =  $this->pdf->Output('', true);
        
        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContent($content);
        return $response;
    }
    
}