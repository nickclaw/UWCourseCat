<?php

namespace CC\DataBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use CC\DataBundle\Entity\Term;
use CC\DataBundle\Entity\Campus;
use CC\DataBundle\Entity\College;
use CC\DataBundle\Entity\Curriculum;
use CC\DataBundle\Entity\Course;

class GetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cc:get')
            ->setDescription('Greet someone')
            ->addArgument('year', InputArgument::REQUIRED, 'Term year to search from.')
            ->addArgument('quarter', InputArgument::REQUIRED, 'Term quarter to search from.')
            ->addArgument('level', InputArgument::OPTIONAL, 'term campus college curriculum course section ')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getArgument('year');
        $quarter = $input->getArgument('quarter');
        $level = $input->getArgument('level');
        if (!$level){
            $level = 'term';
        }

        $em = $this->getContainer()->get('doctrine')->getManager();

        $term = null;
        $campuses = null;
        $colleges = null;
        $curricula = null;
        $courses = null;
        $sections = null;

        switch($level) {
            case 'term':
                $output->writeln('Getting term...');
                $term = $this->getTerm($year, $quarter, $em);
            case 'campus':
                $output->writeln('Getting campuses...');
                $campuses = $this->getCampuses($year, $quarter, $em, $term);
            case 'college':
                $output->writeln('Getting colleges...');
                $colleges = $this->getColleges($year, $quarter, $em, $campuses);
            case 'curriculum':
                $output->writeln('Getting curricula...');
                $curricula = $this->getCurricula($year, $quarter, $em, $colleges);
            case 'course':
                $output->writeln('Getting courses...');
                $this->getCourses($year, $quarter, $em, $curricula);
            case 'section':
                $output->writeln('Getting sections...');
        }
        $em->flush();
    }

    private function getTerm($year, $quarter, $em) {
        $url = "https://ws.admin.washington.edu/student/v4/public/term/". $year .",". $quarter .".json";
        // echo "<a href='$url'>$url</a><br />";
        $json = file_get_contents($url);
        $data = json_decode($json);

        $term = new Term();
        $term->setYear($year)
            ->setQuarter($quarter);
        $em->persist($term);

        return $term;
    }

    private function getCampuses($year, $quarter, $em, $term = null) {
        if (!$term) {
            $repo = $em->getRepository('CCDataBundle:Term');
            $term = $repo->findOneBy(array('year' => $year, 'quarter' => $quarter));
        }

        $url = 'https://ws.admin.washington.edu/student/v4/public/campus.json';
        // echo "<a href='$url'>$url</a><br />";
        $json = file_get_contents($url);
        $data = json_decode($json);

        $campuses = [];
        foreach($data->Campuses as $campus) {
            // make a campus
            $ca = new Campus();
            $ca->setFullName($campus->CampusFullName)
                ->setName($campus->CampusName)
                ->setShortName($campus->CampusShortName)
                ->setTerm($term);
            $em->persist($ca);
            $campuses[] = $ca;
        }

        return $campuses;
    }

    private function getColleges($year, $quarter, $em, array $campuses = null) {
        if (!$campuses) {
            $repo = $em->getRepository('CCDataBundle:Campus');
            $campuses = $repo->findBy(array());
        }
        $colleges = [];
        foreach($campuses as $campus) {
            $url = 'https://ws.admin.washington.edu/student/v4/public/college.json'.
                '?campus_short_name='. $campus->getShortName();
            // echo "<a href='$url'>$url</a><br />";
            $json = file_get_contents($url);
            $data = json_decode($json);

            foreach($data->Colleges as $college) {
                $co = new College();
                $co->setAbbreviation($college->CollegeAbbreviation)
                    ->setFullName($college->CollegeFullName)
                    ->setName($college->CollegeName)
                    ->setShortName($college->CollegeShortName)
                    ->setCampus($campus);
                $em->persist($co);
                $colleges[] = $co;
            }
        }

        return $colleges;
    }

    private function getCurricula($year, $quarter, $em, array $colleges = null) {
        if (!$colleges) {
            $repo = $em->getRepository('CCDataBundle:College');
            $colleges = $repo->findBy(array());
        }

        $returner = [];
        foreach ($colleges as $college) {
            $url = 'https://ws.admin.washington.edu/student/v4/public/curriculum.json'.
                '?year='.$college->getCampus()->getTerm()->getYear().
                '&quarter='.$college->getCampus()->getTerm()->getQuarter().
                '&department_abbreviation='. rawurlencode($college->getAbbreviation()) .
                '&sort_by=on';
            // echo "<a href='$url'>$url</a><br />";
            $json = file_get_contents($url);
            $part1 = json_decode($json);

            $url = 'https://ws.admin.washington.edu/student/v4/public/curriculum.json'.
                '?year='.$college->getCampus()->getTerm()->getYear().
                '&quarter='.$college->getCampus()->getTerm()->getQuarter().
                '&college_abbreviation='. rawurlencode($college->getAbbreviation()) .
                '&sort_by=on';
            $json = file_get_contents($url);
            $part2 = json_decode($json);

            $curricula = $this->mergeCurriculum($part1->Curricula, $part2->Curricula);

            foreach($curricula as $curriculum) {
                $cu = new Curriculum();
                $cu->setAbbreviation($curriculum->CurriculumAbbreviation)
                    ->setFullName($curriculum->CurriculumFullName)
                    ->setName($curriculum->CurriculumName)
                    ->setCollege($college);
                $em->persist($cu);
                $returner[] = $cu;
            }
        }
        return $returner;
    }

    private function getCourses($year, $quarter, $em, $curricula) {
        if (!$curricula) {
            $repo = $em->getRepository('CCDataBundle:Curriculum');
            $curricula = $repo->findBy(array());
        }

        $courses = [];
        foreach($curricula as $curriculum) {
            $url = 'https://ws.admin.washington.edu/student/v4/public/course.json'.
                    '?year='.$curriculum->getCollege()->getCampus()->getTerm()->getYear().
                    '&quarter='.$curriculum->getCollege()->getCampus()->getTerm()->getQuarter().
                    '&future_terms=0'.
                    '&curriculum_abbreviation='.rawurlencode($curriculum->getAbbreviation()).
                    '&page_size=50';
            do {
                echo $url."\n";
                $json = file_get_contents($url);
                $data = json_decode($json);

                foreach($data->Courses as $course) {
                    $courseUrl = 'https://ws.admin.washington.edu/student/v4/public/course/'.
                        $curriculum->getCollege()->getCampus()->getTerm()->getYear().','.
                        $curriculum->getCollege()->getCampus()->getTerm()->getQuarter().','.
                        rawurlencode($curriculum->getAbbreviation()).','.
                        $course->CourseNumber.'.json';
                    echo "    ".$courseUrl."\n";
                    $courseJson = file_get_contents($courseUrl);
                    $course = json_decode($courseJson);

                    $crs = new Course();
                    $crs->setNumber($course->CourseNumber)
                        ->setTitle($course->CourseTitle)
                        ->setLongTitle($course->CourseTitleLong)
                        ->setCurriculum($curriculum)
                        ->setDescription($course->CourseDescription)
                        ->setCreditControl($course->CreditControl)
                        ->setGradingSystem($course->GradingSystem)
                        ->setMaxCredits($course->MaximumCredit)
                        ->setMaxTermCredits($course->MaximumTermCredit)
                        ->setMinTermCredits($course->MinimumTermCredit)
                        ->setGenEd($course->GeneralEducationRequirements);
                    $em->persist($crs);

                    $courses[] = $course;
                }
                if ($data->Next !== null) {
                    $url = 'https://ws.admin.washington.edu'.$data->Next->Href;
                } else {
                    $url = null;
                }
            } while ($url !== null);
        }
        return $courses;
    }




    private function mergeCurriculum($a, $b) {
        foreach($a as $aa) {
            $unique = true;
            foreach($b as $bb) {
                if ($bb->CurriculumAbbreviation === $aa->CurriculumAbbreviation) {
                    $unique = false;
                    break;
                }
            }
            if ($unique) {
                $b[0] = $aa;
            }
        }
        return $b;
    }
}