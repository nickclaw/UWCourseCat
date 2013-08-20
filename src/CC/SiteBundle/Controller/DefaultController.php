<?php

namespace CC\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CC\DataBundle\Entity\College;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {	
    	$em = $this->getDoctrine()->getManager();


        /****** GET CURRENT TERM ******/
        $year = 2012;
        $quarter = 'autumn';
        $term = file_get_contents("https://ws.admin.washington.edu/student/v4/public/term/". $year .",". $quarter .".json");
        $termData = json_decode($term);

        echo "Year: $year<br />";
        echo "Quarter: $quarter<br />";

        /****** GET ALL CAMPUSES ******/
    	$campuses = file_get_contents('https://ws.admin.washington.edu/student/v4/public/campus.json');
    	$campusData = json_decode($campuses, false);

        // for each campus
        foreach($campusData->Campuses as $campus) {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;".$campus->CampusName."<br />";

            /******* GET ALL COLLEGES ******/
            $collegeText = file_get_contents("https://ws.admin.washington.edu/student/v4/public/college.json?campus_short_name=". $campus->CampusShortName);
            $collegeData = json_decode($collegeText);

            foreach($collegeData->Colleges as $college) {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$college->CollegeName."<br />";

                /******* GET ALL CURRICULA ******/
                // $curText = file_get_contents("https://ws.admin.washington.edu/student/v4/public/curriculum.json".
                //     "?year=". $year .
                //     "&quarter=". $quarter .
                //     "&department_abbreviation=". $college->CollegeAbbreviation . 
                //     "&sort_by=on"
                // );
                $curText = ('https://ws.admin.washington.edu/student/v4/public/curriculum.json'.
                    '?year='.$year.
                    '&quarter='.$quarter.
                    '&department_abbreviation='. $college->CollegeAbbreviation .
                    '&sort_by=on'
                );
                
                // $curData = json_decode($curText);
                echo $curText."<br />";

                // foreach($curData->Curricula as $curriculum) {
                //     echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$curriculum->CurriculumFullName."<br />";
                // }
            }

        }

    	return array();
    }
}
