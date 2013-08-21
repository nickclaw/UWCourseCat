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

    private function getTerm($year, $quarter, $em, $parent) {
        echo "Getting Term...\n";
    }

    private function getCampuses($year, $quarter, $em, $parent) {
        echo "campus\n";
    }

    private function getColleges($year, $quarter, $em, $parent) {
        echo "college\n";
    }

    private function getCurricula($year, $quarter, $em, $parent) {
        echo "curriculum\n";;
    }

    private function getCourses($year, $quarter, $em, $parent) {
        echo "course\n";
    }

    private function getSections($year, $quarter, $em, $parent) {
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