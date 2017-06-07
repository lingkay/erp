<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            // pdf bundle
            new Ensepar\Html2pdfBundle\EnseparHtml2pdfBundle(),

            // APC
            // new SmartCore\Bundle\AcceleratorCacheBundle\AcceleratorCacheBundle(),
            // utility bundles
            //new Gist\AdminBundle\GistAdminBundle(),
            new Gist\ChartBundle\GistChartBundle(),
            new Gist\TemplateBundle\GistTemplateBundle(),

            // core modules
            new Gist\ConfigurationBundle\GistConfigurationBundle(),
            new Gist\DashboardBundle\GistDashboardBundle(),
            new Gist\GalleryBundle\GistGalleryBundle(),
            new Gist\MenuBundle\GistMenuBundle(),
            new Gist\GridBundle\GistGridBundle(),
            new Gist\LogBundle\GistLogBundle(),
            new Gist\UserBundle\GistUserBundle(),
            new Gist\MediaBundle\GistMediaBundle(),
            new Gist\PdfBundle\GistPdfBundle(),
            new Gist\NotificationBundle\GistNotificationBundle(),

            // erp modules
            new Gist\FlotBundle\GistFlotBundle(),
            new Gist\ContactBundle\GistContactBundle(),
            new Gist\ReportBundle\GistReportBundle(),

            //GIST ERP
            new Gist\InventoryBundle\GistInventoryBundle(),
            new Gist\ManufacturingBundle\GistManufacturingBundle(),
            new Gist\LocationBundle\GistLocationBundle(),

            //Hris modules
            new Hris\AdminBundle\HrisAdminBundle(),
            new Hris\WorkforceBundle\HrisWorkforceBundle(),
            new Hris\RecruitmentBundle\HrisRecruitmentBundle(),
            new Hris\ArchivesBundle\HrisArchivesBundle(),
            new Hris\EmployeeSatisfactionBundle\HrisEmployeeSatisfactionBundle(),
            new Hris\TrainingBundle\HrisTrainingBundle(),
            new Hris\CompanyOverviewBundle\HrisCompanyOverviewBundle(),
            new Hris\ProfileBundle\HrisProfileBundle(),
            new Hris\DashboardBundle\HrisDashboardBundle(),
            new Hris\ReportBundle\HrisReportBundle(),
            new Hris\TemplateBundle\HrisTemplateBundle(),
            new Hris\NotificationBundle\HrisNotificationBundle(),
            new Hris\PayrollBundle\HrisPayrollBundle(),
            new Hris\MemoBundle\HrisMemoBundle(),
            new Hris\RemunerationBundle\HrisRemunerationBundle(),
            new Hris\BiometricsBundle\HrisBiometricsBundle(),
        );
 

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }



    public function getCacheDir()
    {
        return '/tmp/gist_erp2/cache';
    }

    public function getLogDir()
    {
        return '/tmp/gist_erp2/logs';
    }

}
