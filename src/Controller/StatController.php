<?php

namespace App\Controller;

use App\Entity\Reservation;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ComboChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends AbstractController
{
    /**
     * @Route("/stat", name="stat")
     */
    public function indexAction()
    {
        $reservation=$this->getDoctrine()
            ->getRepository(Reservation::class)->countPriceByDest();
        $combo = new ComboChart();
        $test=array();
        array_push($test,['Destination', 'Total Prix']);
//        array_push($test,['', 0]);
       // array_push($test,['Destination', 150]);
        foreach ($reservation as $res){

            array_push($test,[$res['dest'],$res['totalPrice']+ 0   ]);
        }

        $combo->getData()->setArrayToDataTable($test);
        $combo->getOptions()->setTitle('Monthly Coffee Production by Country');
        $combo->getOptions()->getVAxis()->setTitle('Cups');
        $combo->getOptions()->getHAxis()->setTitle('Month');
        $combo->getOptions()->setSeriesType('bars');

        $series5 = new \CMEN\GoogleChartsBundle\GoogleCharts\Options\ComboChart\Series();
        $series5->setType('line');
        $combo->getOptions()->setSeries([5 => $series5]);

        $combo->getOptions()->setWidth(900);
        $combo->getOptions()->setHeight(500);
        return $this->render('stat/index.html.twig', array('piechart' => $combo,'test'=>$reservation));
    }
}
