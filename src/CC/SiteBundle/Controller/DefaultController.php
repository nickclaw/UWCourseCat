<?php

namespace CC\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {	
    	$doc = new \DOMDocument();
    	$doc->formatOutput = true;
    	$doc->load('https://ws.admin.washington.edu/student/v4/public/college.xml?year=&quarter=&future_terms=0&campus_short_name=seattle&page_start=1&page_size=20');
    	$colleges = $doc->getElementsByTagName('Colleges')->item(0);
    	foreach($colleges->getElementsByTagName('College') as $college) {
    		$abbreviation = $college->getElementsByTagName('CollegeAbbreviation')->item(0)->nodeValue;
    		$fullName = $college->getElementsByTagName('CollegeFullNameTitleCased')->item(0)->nodeValue;
    		$shortName = $college->getElementsByTagName('CollegeName')->item(0)->nodeValue;
    		echo $abbreviation.'-'.$fullName.'-'.$shortName.'<br />';
    	}

    	return array();
    }
}
