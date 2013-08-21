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
            ->addArgument('from', InputArgument::OPTIONAL, 'term campus college curriculum course section ')
            ->addArgument('to', InputArgument::OPTIONAL, 'term campus college curriculum course section')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getArgument('year');
        $quarter = $input->getArgument('quarter');
        $from = $input->hasArgument('from')?$input->getArgument('from'):'term';
        $to = $input->hasArgument('to')?$input->getArgument('to'):'section';


        $em = $this->getContainer()->get('doctrine')->getManager();

        $returnedLast = null;

        switch ($from) {
            case 'term':
                $returnedLast = $this->getTerm($year, $quarter, $em, $returnedLast);
                if ($to === 'term') break;
            case 'campus':
                $returnedLast = $this->getCampuses($year, $quarter, $em, $returnedLast);
                if ($to === 'campus') break;
            case 'college':
                $returnedLast = $this->getColleges($year, $quarter, $em, $returnedLast);
                if ($to === 'college') break;
            case 'curriculum':
                $returnedLast = $this->getCurricula($year, $quarter, $em, $returnedLast);
                if ($to === 'curriculum') break;
            case 'course':
                $returnedLast = $this->getCourses($year, $quarter, $em, $returnedLast);
                if ($to === 'course') break;
            case 'section':
                $returnedLast = $this->getSections($year, $quarter, $em, $returnedLast);
                if ($to === 'section') break;
        }
        
        $em->flush();
    }

    private function getTerm($year, $quarter, $em, $parents) {
        $url = 'https://ws.admin.washington.edu/student/v4/public/term/'.
            $year.','.
            $quarter.'.json';
        $data = $this->getJsonObject($url);
        
        $term = new Term();
        $term->setYear($year)
            ->setQuarter($quarter);
        $em->merge($term);

        return $term;
    }

    private function getCampuses($year, $quarter, $em, $parent) {
        if (!$parent) {
            $repo = $em->getRepository('CCDataBundle:Term');
            $parent = $repo->findOneBy(array('year' => $year, 'quarter' => $quarter));
        }

        $url = 'https://ws.admin.washington.edu/student/v4/public/campus.json';
        $data = $this->getJsonObject($url);

        $campuses = [];
        foreach($data->Campuses as $campus) {
            $ca = new Campus();
            $ca->setFullName($campus->CampusFullName)
                ->setName($campus->CampusName)
                ->setShortName($campus->CampusShortName)
                ->setTerm($parent);
            $em->merge($ca);
            $campuses[] = $ca;
        }
        $em->flush();
        return $campuses;
    }

    private function getColleges($year, $quarter, $em, $parents) {
        if (!$parents) {
            $repo = $em->getRepository('CCDataBundle:Campus');
            $parents = $repo->findBy(array());
        }

        $colleges = [];
        foreach($parents as $campus) {
            $url = 'https://ws.admin.washington.edu/student/v4/public/college.json'.
                '?campus_short_name='. $campus->getShortName();
            $data = $this->getJsonObject($url);

            foreach($data->Colleges as $college) {
                $co = new College();
                $co->setAbbreviation($college->CollegeAbbreviation)
                    ->setFullName($college->CollegeFullName)
                    ->setName($college->CollegeName)
                    ->setShortName($college->CollegeShortName)
                    ->setCampus($campus);
                $em->merge($co);
                $colleges[] = $co;
            }
        }
        $em->flush();
        return $colleges;
    }

    private function getCurricula($year, $quarter, $em, $parents) {
        if (!$parents) {
            $repo = $em->getRepository('CCDataBundle:College');
            $parents = $repo->findBy(array());
        }

        $returner = [];
        foreach($parents as $college) {
            $mHandle = curl_multi_init();
                $url1 = 'https://ws.admin.washington.edu/student/v4/public/curriculum.json'.
                    '?year='.$year.
                    '&quarter='.$quarter.
                    '&department_abbreviation='. rawurlencode($college->getAbbreviation()) .
                    '&sort_by=on';
                $url2 = 'https://ws.admin.washington.edu/student/v4/public/curriculum.json'.
                    '?year='.$year.
                    '&quarter='.$quarter.
                    '&college_abbreviation='. rawurlencode($college->getAbbreviation()) .
                    '&sort_by=on';
            $dataArray = $this->getJsonObjects(array($url1, $url2));
            $curricula = $this->mergeCurriculum($dataArray[0]->Curricula, $dataArray[1]->Curricula);

            foreach($curricula as $curriculum) {
                $cu = new Curriculum();
                $cu->setAbbreviation($curriculum->CurriculumAbbreviation)
                    ->setFullName($curriculum->CurriculumFullName)
                    ->setName($curriculum->CurriculumName)
                    ->setCollege($college);
                $em->merge($cu);
                $returner[] = $cu;
            }
        }
        $em->flush();
        return $returner;
    }

    private function getCourses($year, $quarter, $em, $parents) {
        if (!$parents) {
            $repo = $em->getRepository('CCDataBundle:Curriculum');
            $parents = $repo->findBy(array());
        }

        $courses = [];
        foreach($parents as $curriculum) {
            $url = 'https://ws.admin.washington.edu/student/v4/public/course.json'.
                '?year='.$year.
                '&quarter='.$quarter.
                '&future_terms=0'.
                '&curriculum_abbreviation='.rawurlencode($curriculum->getAbbreviation()).
                '&page_size=400';
            $data = $this->getJsonObject($url);
            $courseChunks = array_chunk($data->Courses, 50);
            foreach($courseChunks as $courseChunk) {
                $urlArray = [];
                foreach($courseChunk as $chunk) {
                    $courseUrl = 'https://ws.admin.washington.edu/student/v4/public/course/'.
                        $year.','.
                        $quarter.','.
                        rawurlencode($curriculum->getAbbreviation()).','.
                        $chunk->CourseNumber.'.json';
                    $urlArray[] = $courseUrl;
                }
                $result = $this->getJsonObjects($urlArray);

                foreach($result as $course) {
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
                    $em->merge($crs);

                    $courses[] = $crs;
                }
            }
            $em->flush();
        }
    }

    private function getSections($year, $quarter, $em, $parents) {
        echo "section\n";
    }

    private function getJsonObject($url, $returnHandle = false) {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($handle, CURLOPT_NOPROGRESS, false);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 20);
        if ($returnHandle) {
            return $handle;
        } else {
            $json = curl_exec($handle);
            $data = json_decode($json);
            return $data;
        }
    }

    private function getJsonObjects(array $urls) {
        $mHandle = curl_multi_init();
        $handles = [];
        foreach($urls as $url) {
            curl_multi_add_handle($mHandle, $handles[] = $this->getJsonObject($url, true));
        }

        $running = null;
        do {
            curl_multi_exec($mHandle, $running);
        } while($running > 0);

        $returner = [];
        foreach($handles as $handle) {
            $returner[] = json_decode(curl_multi_getcontent($handle));
            curl_multi_remove_handle($mHandle, $handle);
            curl_close($handle);
        }
        curl_multi_close($mHandle);

        return $returner;
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