<?php

namespace Hris\WorkforceBundle\DataFixtures\ORM\Test;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Hris\PayrollBundle\Entity\PayTaxMatrix;
use Hris\PayrollBundle\Entity\PayTaxRate;
use Hris\PayrollBundle\Entity\PayTaxStatus;



class LoadTaxMatrix extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        $user = $this->getReference('admin');

        $payroll_period = ['Daily','Weekly','Semi-Monthly','Monthly'];

        $tax_bracket = array (
            'Z' => array(
                'Daily' => array('1-1','0-32','33-98','99-230','231-461','462-824','825-1649','1650-999999999'),
                'Weekly' => array('1-1','0-191','192-576','577-1345','1346-2691','2692-4807','4808-9614','9615-999999999'),
                'Semi-Monthly' => array('1-1','0-416','417-1249','1250-2916','2917-5832','5833-10416','10417-20832','20833-999999999'),
                'Monthly' => array('1-1','0-832','833-2499','2500-5832','5833-11666','11667-20832','20833-41666','41667-999999999')
                ),
            'ME/S' => array(
                'Daily' => array('1-164','165-197','198-263','264-395','396-626','627-989','990-1814','1815-999999999'),
                'Weekly' => array('1-961','962-1153','1154-1537','1538-2307','2308-3653','3654-5768','5769-10576','10577-999999999'),
                'Semi-Monthly' => array('1-2082','2083-2499','2500-3332','3333-4999','5000-7916','7917-12499','12500-22916','22917-999999999'),
                'Monthly' => array('1-4166','4167-4999','5000-6666','6667-9999','10000-15832','15833-24999','25000-45882','45883-999999999')
                ),
            'ME1/S1' => array(
                'Daily' => array('1-247','248-280','281-346','347-478','479-709','710-1072','1073-1897','1898-999999999'),
                'Weekly' => array('1-1441','1442-1634','1635-2018','2019-2787','2788-4134','4135-6249','6250-11057','11058-999999999'),
                'Semi-Monthly' => array('1-3124','3125-3541','3542-4374','4375-6041','6042-8957','8958-13541','13542-23957','23958-999999999'), 
                'Monthly' => array('1-6249','6250-7082','7083-8749','8750-12082','12083-17916','17917-27082','27083-47916','47917-999999999')
                ),
            'ME2/S2' => array(
                'Daily' => array('1-329','330-362','363-428','429-560','561-791','792-1154','1155-1979','1980-999999999'),
                'Weekly' => array('1-1922','1923-2114','2115-2499','2500-3268','3269-4614','4615-6730','6731-11537','11538-999999999'),
                'Semi-Monthly' => array('1-4166','4167-4582','4583-5416','5417-7082','7083-9999','10000-14582','14583-24999','25000-999999999'),
                'Monthly' => array('1-8332','8333-9166','9167-10832','10833-14166','14167-19999','20000-29166','29167-49999','50000-999999999')
                ),
            'ME3/S3' => array(
                'Daily' => array('1-412','413-445','446-511','512-643','644-874','875-1237','1238-2062','2063-999999999'),
                'Weekly' => array('1-2403','2404-2595','2596-2980','2981-3749','3750-5095','5096-7211','7212-12018','12019-999999999'),
                'Semi-Monthly' => array('1-5207','5208-5624','5625-6457','6458-8124','8125-11041','11042-15624','15625-26041','26042-999999999'),
                'Monthly' => array('1-10416','10417-11249','11250-12916','12917-16249','16250-22082','22083-31249','31250-52082','52083-999999999')
                ),
            'ME4/S4' => array(
                'Daily' => array('1-494','495-527','528-593','594-725','726-956','957-1319','1320-2144','2145-999999999'),
                'Weekly' => array('1-2884','2885-3076','3077-3461','3462-4230','4231-5576','5577-7691','7692-12499','12500-999999999'),
                'Semi-Monthly' => array('1-6249','6250-6666','6667-7499','7500-9166','9167-12082','12083-16666','16667-27082','27083-999999999'), 
                'Monthly' => array('1-12499','12500-13332','13333-14999','15000-18332','18333-24166','24167-33332','33333-54166','54167-999999999')
                )
            );
        
        $tax = array(
            'Daily' => array(0.00,0.00,1.65,8.25,28.05,74.26,165.02,412.54),
            'Weekly' => array(0.00,0.00,9.62,48.08,163.46,432.69,961.54,2403.85),
            'Semi-Monthly' => array(0.00,0.00,20.83,104.17,354.17,937.50,2083.33,5208.33),
            'Monthly' => array(0.00,0.00,41.67,208.33,708.33,1875.00,4166.67,10416.67) 
            );
        $percent = [0,5,10,15,20,25,30,32];
        
        foreach ($payroll_period as $period) {

            $query = 'SELECT p FROM HrisPayrollBundle:PayPeriod p WHERE p.name LIKE :period';

            $pay_period = $em->createQuery($query)
                       ->setParameter('period',$period)
                       ->getSingleResult();

            foreach ($tax_bracket as $id => $brackets) {
                           
                foreach($brackets as $tax_period => $bracket) {
                    $count = 0;
                    if($tax_period == $period) {
                        foreach ($bracket as $range) {

                            $query = 'SELECT pts FROM HrisPayrollBundle:PayTaxStatus pts WHERE pts.code LIKE :code';

                            $tax_status = $em->createQuery($query)
                                       ->setParameter('code',$id)
                                       ->getSingleResult();

                            $matrix = new PayTaxMatrix();
                            $tax_rate = new PayTaxRate();

                            $amount = explode('-', $range);

                            $tax_rate->setBracket($range);
                            $tax_rate->setMinimum($amount[0]);
                            $tax_rate->setMaximum($amount[1]);
                            $tax_rate->setTax($tax[$period][$count]);
                            $tax_rate->setExcess($percent[$count]);
                            $tax_rate->setPeriod($pay_period);
                            $tax_rate->setTaxStatus($tax_status);

                            $matrix->setPayPeriod($pay_period);
                            $matrix->setTaxRate($tax_rate);
                            $matrix->setTaxStatus($tax_status);
                            $matrix->setBaseAmount($amount[0]);

                            $em->persist($tax_rate);
                            $em->persist($matrix);
                            if($count <7) {
                                $count++;   
                            }
                        }
                    }
                }
            }
        }
        $em->flush();
    
    }
    
    public function getOrder()
    {
        return 3;
    }
}