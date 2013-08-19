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

    	$doc = new \DOMDocument();
    	$doc->formatOutput = true;
    	$doc->load('https://ws.admin.washington.edu/student/v4/public/college.xml?year=&quarter=&future_terms=0&campus_short_name=seattle&page_start=1&page_size=20');


    	$colleges = $doc->getElementsByTagName('Colleges')->item(0);
    	foreach($colleges->getElementsByTagName('College') as $college) {
    		$abbreviation = $college->getElementsByTagName('CollegeAbbreviation')->item(0)->nodeValue;
    		$fullName = $college->getElementsByTagName('CollegeFullNameTitleCased')->item(0)->nodeValue;
    		$name = $college->getElementsByTagName('CollegeName')->item(0)->nodeValue;
    		$shortName = $college->getElementsByTagName('CollegeShortName')->item(0)->nodeValue;
    		$year = $college->getElementsByTagName('Year')->item(0)->nodeValue;
    		$quarter = $college->getElementsByTagName('Quarter')->item(0)->nodeValue; 		

    		$college = new College();
    		$college->setAbbreviation($abbreviation)
    			->setFullName($fullName)
    			->setName($name)
    			->setShortName($shortName)
    			->setYear($year)
    			->setQuarter($quarter);
    		$em->persist($college);

    		echo $fullName."<br />";

    		$curDoc = new \DomDocument();
    		$curDoc->formatOutput = true;
    		$curDoc->load("https://ws.admin.washington.edu/student/v4/public/curriculum.xml?year=$year&quarter=$quarter&department_abbreviation=$abbreviation&sort_by=on");
    		$curricula = $curDoc->getElementsByTagName('Curriculum');

    		foreach($curricula as $curriculum) {
    			echo "    ". $curriculum->getElementsByTagName('CurriculumFullName')->item(0)->nodeValue."<br />";
    		}
    	}
    	echo $curDoc->saveXml();

    	$em->flush();

    	return array();
    }
}
