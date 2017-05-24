<?php 

namespace ImportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
 
use ProductBundle\Entity\Product;
 
class ImportCommand extends ContainerAwareCommand
{

 
    protected function configure()
    {
        // Name and description for app/console command
        $this
        ->setName('import:csv')
        ->setDescription('Import Products from CSV file');
    }
 
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Showing when the script is launched
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
        
        // Importing CSV on DB via Doctrine ORM
        $this->import($input, $output);
        
        // Showing when the script is over
        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }
    
    protected function import(InputInterface $input, OutputInterface $output)
    {
        // Getting php array of data from CSV
        $data = $this->get($input, $output);
        
        // Getting doctrine manager
        $em = $this->getContainer()->get('doctrine')->getManager();
        // Turning off doctrine default logs queries for saving memory
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        
        // Define the size of record, the frequency for persisting the data and the current index of records
        $size = count($data);
        $batchSize = 20;
        $i = 1;
        
        // Starting progress
        $progress = new ProgressBar($output, $size);
        $progress->start();
        
        // Processing on each row of data
        $product = new Product();
        if($data){
            foreach($data as $row) {          
                // If the Product doest not exist we create one
                $product->setName($row["name"]);
                $product->setDescription($row["Description"]);
                $product->setSizeInch($row["Taille ( inch )"]);
                $product->setSizeCm($row["Taille ( cm )"]);
                $product->setColor($row["Couleur"]);
                $product->setComposition($row["Composition"]);
                $product->setForm($row["Forme"]);
                $product->setWeight($row["Poids"]);
                $product->setUnitPrice($row["Prix unitaire"]);
                $product->setWholesalePrice($row["Prix grossiste"]);
                $product->setSpecialPrice($row["Prix special"]);
                $product->setInternetPrice($row["Prix internet"]);
                $product->setObservations($row["Observations"]);
                $product->setActive("1");
                
                // Do stuff here !
        
                // Persisting the current Product
                $em->persist($product);
                
                // Each 20 Products persisted we flush everything
                //if (($i % $batchSize) === 0) {
     
                    $em->flush();
                    // Detaches all objects from Doctrine for memory save
                    $em->clear();
                    
                    // Advancing for progress display on console
                    $progress->advance($batchSize);
                    
                    $now = new \DateTime();
                    $output->writeln(' of Products imported ... | ' . $now->format('d-m-Y G:i:s'));
     
                //}
     
                $i++;
     
            }
        }else{
            $output->writeln(' no data to foreach on ');
        }
        
        // Flushing and clear data on queue
        $em->flush();
        $em->clear();
        
        // Ending the progress bar process
        $progress->finish();
    }
 
    protected function get(InputInterface $input, OutputInterface $output) 
    {
        // Getting the CSV from filesystem
        $fileName = 'web/uploads/imports/liste_produits_DFT_v0.5.csv';
        // Using service for converting CSV to PHP Array
        $converter = $this->getContainer()->get('import.csvtoarray');
        $data = $converter->convert($fileName, ';');
        if($data){
            return $data;
        }else{
            $output->writeln(' cant reading data from file ... ');
        }

    }
    
}