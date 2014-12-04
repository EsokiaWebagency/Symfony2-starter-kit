<?php

namespace Esokia\Bundle\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;



/**
 * InitProjectCommand
 * 
 * This command must be use only 1 time by project, 
 * It will assist you to set the first steps of your Symfony's Esokia Distribution
 * 
 * 
 * 
 */
class InitProjectCommand extends ContainerAwareCommand
{
    protected $dialog;
    protected $appPath;
    protected $configPath;
    
    
    protected function configure()
    {
        $this
            ->setName('esokia:distribution:init')
            ->setDescription('Init the distribution, remeber to create an accessible DB before executing this task')
        ;
    }

    /**
     * 1- set parameters in parameters.yml, parameters_dev.yml, etc...
     * 2- install dependencies
     * 3- clear cache in dev
     * 4- dump assets in dev mode
     * 5- load fixtures
     * 
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $this->dialog =  $this->getHelper('dialog');  
        $this->appPath =  __DIR__.'/../../../../../app/';
        $this->configPath =  $this->appPath.'config/';
        
        
        $output->writeln('<info>Configuration begin</info>');
        
        /**
         * 0- check requirements
         */

        //check all the module with the symfony command
        $process = new Process('php app/check.php');
        $process->run(function ($type, $buffer) {
                        if (Process::ERR === $type) {
                            echo 'ERR > '.$buffer;
                        } else {
                            echo 'OUT > '.$buffer;
                        }
                    });

        // executes after the command finishes
        if(substr_count($process->getOutput(), 'ERROR')){
            throw new \RuntimeException('All the mandatory pre-requise must be resolve to continue. check the error');
        }
        
        
        //check node
        $process = new Process('node -v');
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException('you must install NODEjs to be able to use assetics');
        }else{
            $output->writeln(' OK       NodeJs is enabled - version '.$process->getOutput());
        }
        
        
         $output->writeln('<info> ---------------- requirements Ok ----------------</info>');

        
        /*
         * 1- set parameters in parameters.yml, parameters_dev.yml, etc...
         */
        $output->writeln('');$output->writeln('');
        $this->initParameters($input, $output);


        
        
        /*
         * 2- install dependencies
         */
        $output->writeln('');$output->writeln('');
        $output->writeln('<info>Installing dependencies</info>');

        $process = new Process('php composer.phar install');
        $process->run(function ($type, $buffer) {
                        if (Process::ERR === $type) {
                            echo 'ERR > '.$buffer;
                        } else {
                            echo 'OUT > '.$buffer;
                        }
                    });

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        
         /*
         * 3- clear cache in dev
         */ 
         $output->writeln('');$output->writeln('');
         $output->writeln('<info>Clearing the cache</info>');

         
                 //user confirmation
        if (!$this->dialog->askConfirmation(
                 $output,
                '<question>If your are on windows, you should manually remove all the folders of your app/cache/ directory. Do you want to continue? (y/n)</question>',
                false
            )) {
            exit;
        }
        
         

       
         //warmup cache
        $process = new Process('php app/console cache:clear');
        $process->run(function ($type, $buffer) {
                        if (Process::ERR === $type) {
                            echo 'ERR > '.$buffer;
                        } else {
                            echo 'OUT > '.$buffer;
                        }
                    });

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
         /*
         * 4- dump assets in dev mode
         */
        $output->writeln('');$output->writeln('');
        
        $output->writeln('<info>Dump assets</info>');
        $process = new Process('php app/console assetic:dump');
        $process->run(function ($type, $buffer) {
                        if (Process::ERR === $type) {
                            echo 'ERR > '.$buffer;
                        } else {
                            echo 'OUT > '.$buffer;
                        }
                    });

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
        
        
        
        
        
        /*
         * 5- load fixtures
         */  
        
        
        //create tables
        $output->writeln('');$output->writeln('');

        $output->writeln('<info>Creating tables</info>');

        $process = new Process('php app/console doctrine:schema:update --force');
        $process->run(function ($type, $buffer) {
                        if (Process::ERR === $type) {
                            echo 'ERR > '.$buffer;
                        } else {
                            echo 'OUT > '.$buffer;
                        }
                    });

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }    
        //load fixtures (append the fixtures to datas)
        
        
                         //user confirmation
        if (!$this->dialog->askConfirmation(
                 $output,
                '<question>Greetings! All installation is done. Do you want to install default features (An admin user and a default contact message)? (y/n)</question>',
                false
            )) {
            exit;
        }
        
        $this->loadFixtures($input, $output);
        
        
        
    }
    
    
    
    protected function initParameters(InputInterface $input, OutputInterface $output){
        $yaml = new Parser();
        $parameterPath = $this->configPath.'parameters.yml';
        $parameters = $yaml->parse(file_get_contents($parameterPath));

        
        
        //generate a unique secret key
        $tokenGenerator = $this->getContainer()->get('fos_user.util.token_generator');
        $parameters['parameters']['secret'] = substr($tokenGenerator->generateToken(), 0, 16);

        $parameters['parameters']['site.name'] = $this->dialog->askAndValidate(
            $output,
            'Please enter the site name: ',
             function ($answer) {
                if ($answer == '') {
                    throw new \RuntimeException(
                        'This parameter cannot be empty'
                    );
                }
                return $answer;
            }, false
        );
        
        $parameters['parameters']['site.email']  = $this->dialog->askAndValidate(
            $output,
            'Please enter the admin site email: ',
                function ($answer) {
                if ($answer == '') {
                    throw new \RuntimeException(
                        'This parameter cannot be empty'
                    );
                }
                if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                        throw new \RuntimeException(
                            'You have to use a valid email'
                        );               
                    }
                
                return $answer;
            }, false
        );
        
        $parameters['parameters']['company.name'] = $this->dialog->ask(
            $output,
            'Please enter the company name of the site owner [Esokia Ltd]: ',
            'Esokia Ltd'
        );       
   
        
        $parameters['parameters']['database_driver'] = $this->dialog->ask(
            $output,
            'Please choose your DB driver [pdo_mysql]: ',
            'pdo_mysql'
        );
        
        $parameters['parameters']['database_name'] = $this->dialog->askAndValidate(
            $output,
            'Please choose the name of you DataBase : ',
            function ($answer) {
                if ($answer == '') {
                    throw new \RuntimeException(
                        'This parameter cannot be empty'
                    );
                }
                return $answer;
            }, false
        );       
        $parameters['parameters']['database_host'] = $this->dialog->ask(
            $output,
            'Please define your DB host [127.0.0.1]: ',
            '127.0.0.1'
        );    
         $parameters['parameters']['database_port'] = $this->dialog->ask(
            $output,
            'Please define your DB port [null]: ',
            null
        );      

         
        $parameters['parameters']['database_user'] = $this->dialog->ask(
            $output,
            'Please define your DB user [root]: ',
             'root'
        );          
        $parameters['parameters']['database_password'] = $this->dialog->ask(
            $output,
            'Please define your DB password [null]: ',
            null
            ); 
        
         $parameters['parameters']['locale'] = $this->dialog->ask(
            $output,
            'Please define your default locale [en]: ',
            'en'
        );

         
         //write parameter.yml
        $dumper = new Dumper();
        $yaml = $dumper->dump($parameters, 2);

        
        $output->writeln('<info>your parameters will be: </info>');
        print $yaml;
        
        //user confirmation
        if ($this->dialog->askConfirmation(
                 $output,
                '<question>Those parameters will replace your parameters.yml file. Do you want to continue? (y/n)</question>',
                false
            )) {
                  file_put_contents($parameterPath, $yaml);
                  $output->writeln('<info>parameters has been saved</info>');
        }
        
        
        
        
  
    }
    
   
   
    
    
    
    
    
    protected function loadFixtures(InputInterface $input, OutputInterface $output){
        $output->writeln('<info>load fixture</info>');
        $process = new Process('php app/console doctrine:fixtures:load --append');
        $process->run(function ($type, $buffer) {
                        if (Process::ERR === $type) {
                            echo 'ERR > '.$buffer;
                        } else {
                            echo 'OUT > '.$buffer;
                        }
                    });

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

    }
    
    
    
    
}