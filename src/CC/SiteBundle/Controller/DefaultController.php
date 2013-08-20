<?php

namespace CC\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use CC\DataBundle\Entity\Term;
use CC\DataBundle\Entity\Campus;
use CC\DataBundle\Entity\College;
use CC\DataBundle\Entity\Curriculum;
use CC\DataBundle\Entity\Course;

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
        $termUrl = "https://ws.admin.washington.edu/student/v4/public/term/". $year .",". $quarter .".json";
        $termText = file_get_contents($termUrl);
        $term = json_decode($termText);

        $te = new Term();
        $te->setYear($year)
            ->setQuarter($quarter);
        $em->persist($te);


        /****** GET ALL CAMPUSES ******/
        $campusUrl = 'https://ws.admin.washington.edu/student/v4/public/campus.json';
        $campusesText = file_get_contents($campusUrl);
        $campuses = json_decode($campusesText, false);

        // for each campus
//        echo "<a href='$campusUrl'>$campusUrl</a><br />";
        foreach($campuses->Campuses as $campus) {
//            echo $campus->CampusName.'<br />';
            // make a campus
            $ca = new Campus();
            $ca->setFullName($campus->CampusFullName)
                ->setName($campus->CampusName)
                ->setShortName($campus->CampusShortName)
                ->setTerm($te);
            $em->persist($ca);

            /******* GET ALL COLLEGES ******/
            $collegeUrl = 'https://ws.admin.washington.edu/student/v4/public/college.json'.
                '?campus_short_name='. $campus->CampusShortName;
            $collegeText = file_get_contents($collegeUrl);
            $colleges = json_decode($collegeText);

//            echo "<a href='$collegeUrl'>$collegeUrl</a><br />";
            foreach($colleges->Colleges as $college) {
//                echo "&nbsp;&nbsp;&nbsp;&nbsp;".$college->CollegeName.' - '.$college->CollegeAbbreviation.'<br />';
                $co = new College();
                $co->setAbbreviation($college->CollegeAbbreviation)
                    ->setFullName($college->CollegeFullName)
                    ->setName($college->CollegeName)
                    ->setShortName($college->CollegeShortName)
                    ->setCampus($ca);
                $em->persist($co);

                /******* GET ALL CURRICULA ******/
                $curUrl = 'https://ws.admin.washington.edu/student/v4/public/curriculum.json'.
                    '?year='.$te->getYear().
                    '&quarter='.$te->getQuarter().
                    '&department_abbreviation='. urlencode($college->CollegeAbbreviation) .
                    '&sort_by=on';
                $curText = file_get_contents($curUrl);
                $curricula = json_decode($curText);

//                echo "<a href='$curUrl'>$curUrl</a><br />";
                foreach($curricula->Curricula as $curriculum) {
//                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$curriculum->CurriculumName.' - '.$curriculum->CurriculumAbbreviation.'<br />';
                    $cu = new Curriculum();
                    $cu->setAbbreviation($curriculum->CurriculumAbbreviation)
                        ->setFullName($curriculum->CurriculumFullName)
                        ->setName($curriculum->CurriculumName)
                        ->setCollege($co);
                    $em->persist($cu);

                    $courseText = file_get_contents('https://ws.admin.washington.edu/student/v4/public/course.json'.
                        '?year='.$te->getYear().
                        '&quarter='.$te->getQuarter().
                        '&future_terms=0'.
                        '&curriculum_abbreviation='.urlencode($curriculum->CurriculumAbbreviation)
                    );
                    $courses = json_decode($courseText);

                    foreach($courses->Courses as $course) {
                        $crs = new Course();
                        $crs->setNumber($course->CourseNumber)
                            ->setTitle($course->CourseTitle)
                            ->setLongTitle($course->CourseTitleLong)
                            ->setCurriculum($cu);
                        $em->persist($crs);
                    }
                }
            }
        }
        $em->flush();

        return array();
    }
}
