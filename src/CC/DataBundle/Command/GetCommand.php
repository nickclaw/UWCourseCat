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
        echo "Getting Term...\n";
        $url = 'https://ws.admin.washington.edu/student/v4/public/term/'.
            $year.','.
            $quarter.'.json';

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($handle);
        $data = json_decode($json);
        
        $term = new Term();
        $term->setYear($year)
            ->setQuarter($quarter);
        $em->persist($term);

        return $term;
    }

    private function getCampuses($year, $quarter, $em, $parent) {
        if (!$parent) {
            $repo = $em->getRepository('CCDataBundle:Term');
            $parent = $repo->findOneBy(array('year' => $year, 'quarter' => $quarter));
        }

        $url = 'https://ws.admin.washington.edu/student/v4/public/campus.json';
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($handle);
        $data = json_decode($json);

        $campuses = [];
        foreach($data->Campuses as $campus) {
            $ca = new Campus();
            $ca->setFullName($campus->CampusFullName)
                ->setName($campus->CampusName)
                ->setShortName($campus->CampusShortName)
                ->setTerm($parent);
            $em->persist($ca);
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
            $handle = curl_init($url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($handle);
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
        $em->flush();
        return $colleges;
    }

    private function getCurricula($year, $quarter, $em, $parents) {
        echo "curriculum\n";;
    }

    private function getCourses($year, $quarter, $em, $parents) {
        echo "course\n";
    }

    private function getSections($year, $quarter, $em, $parents) {
        echo "section\n";
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