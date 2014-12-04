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
 * ProdProjectCommand
 * 
 * This command must be use only to configure the project ended to install it on client production server
 * It will assist you to set the last steps of your Symfony's Esokia Distribution
 * 
 */
class ProdProjectCommand extends ContainerAwareCommand
{
    protected $dialog;
    protected $appPath;
    protected $configPath;
    
    
    protected function configure()
    {
        $this
            ->setName('esokia:distribution:prod-install')
            ->setDescription('Init the distribution, remeber to create an accessible DB before executing this task')
        ;
    }

    /**
     * main function
     * 
     * - remove cache folders
     * - dump Assets in prod mode
     * - dumps with good parameters
     * - if in apache, dump URLs in .htacess file
     * - check APC, memecache or redis settings
     * - uncomment .htaccess cache settings
     * - indicate last manual steps
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $this->dialog =  $this->getHelper('dialog');  
        $this->appPath =  __DIR__.'/../../../../../app/';
        $this->configPath =  $this->appPath.'config/';
        
        
        $output->writeln('<info>Production migration begin</info>');
        

        
       //user rights
        if (!$this->dialog->askConfirmation(
                 $output,
                '<question>Before running this command, please ensure that symfony has the good rights on folders and that you have already created a DB. Do you want to continue? (y/n)</question>',
                false
            )) {
            exit;
        }
        
        
        
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

        
        
        
        
        
        

                    
        /**
         * Clearing the cache
         */            
            $output->writeln(''); $output->writeln('');

        $output->writeln('<info>---------- cache warmup -------------</info>');     
        $process = new Process('php app/console cache:clear --env=prod --no-debug');
        $process->run(function ($type, $buffer) {
                        if (Process::ERR === $type) {
                            echo 'ERR > '.$buffer;
                        } else {
                            echo 'OUT > '.$buffer;
                        }
                    });  
                    
                
                    
          
        /*
         * 1- Dump assest in prod mode
         */
                    $output->writeln(''); $output->writeln('');

        $output->writeln('<info>---------- dumping assets -------------</info>');
        $process = new Process('php app/console assetic:dump --env=prod');
        $process->run(function ($type, $buffer) {
                        if (Process::ERR === $type) {
                            echo 'ERR > '.$buffer;
                        } else {
                            echo 'OUT > '.$buffer;
                        }
                    });
                    
                    
                    

     
     

        
         /*
          * set the DB and the Swift mailer parameters
          */
         
        $this->initParameters($input, $output);
       

        
       
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
        
        
        
        
        
        /*
         * - define a user admin
         */  
        $output->writeln(''); $output->writeln('');
        $output->writeln('<info>---------- Creating Admin User -------------</info>');        
        $this->createAdminUser($input, $output);
       
      
        
        
        
        
        
        
        
        /*
         * - set default apache caching
         */  

        
          if ($this->dialog->askConfirmation(
                 $output,
                '<question>Is your website run with apache ? (y/n) </question>',
                false
            )) {
            $this->apacheSettings($input, $output);
        }

        
        
        
         /*
         * - next steps
         */  
        $output->writeln(''); $output->writeln('');
        $output->writeln("<info>--------------- It's almost done ------------------</info>");        
        $output->writeln(''); $output->writeln('');
        $output->writeln("<info>There are some steps that must be mannually done</info>"); 
        $output->writeln("<info>- define a virtual host to the web folder of the application</info>"); 
        $output->writeln("<info>- enable a PHP accelerator (APC, Memcache, etc...)</info>");        
        $output->writeln(''); $output->writeln('');
        $output->writeln("<info>---------- Thank you for choosing the Esokia Symfony's distribution -------------</info>");        
        
        
        
    }
    
   
    
    
    
    /**
     * initParameters
     * 
     * Manage the configuration of production parameters
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initParameters(InputInterface $input, OutputInterface $output){
        $yaml = new Parser();
        $parameterPath = $this->configPath.'parameters.yml';
        $parameters = $yaml->parse(file_get_contents($parameterPath));

        $output->writeln('');$output->writeln('');       
        $output->writeln('<info>---------- Setting Default parameters -----------</info>');    
        
        
        //generate a unique secret key
        $tokenGenerator = $this->getContainer()->get('fos_user.util.token_generator');
        $parameters['parameters']['secret'] = substr($tokenGenerator->generateToken(), 0, 16);

        
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

 
        $parameters['parameters']['mailer_transport'] = $this->dialog->ask(
            $output,
            'Please define your mailer transport ['.$parameters['parameters']['mailer_transport'].']: ',
            $parameters['parameters']['mailer_transport']
        ); 
          $parameters['parameters']['mailer_host'] = $this->dialog->ask(
            $output,
            'Please define your mailer host ['.$parameters['parameters']['mailer_host'].']: ',
            $parameters['parameters']['mailer_host']
        );        
        $parameters['parameters']['mailer_user'] = $this->dialog->ask(
            $output,
            'Please define your mailer user ['.$parameters['parameters']['mailer_user'].']: ',
            $parameters['parameters']['mailer_user']
        );            
         $parameters['parameters']['mailer_password'] = $this->dialog->ask(
            $output,
            'Please define your mailer password ['.$parameters['parameters']['mailer_password'].']: ',
            $parameters['parameters']['mailer_password']
        );         
         
         
         //write parameter.yml
        $dumper = new Dumper();
        $yaml = $dumper->dump($parameters, 2);

        
        $output->writeln('<info>your parameters will be: </info>');
        print $yaml;
        
        //user confirmation
        if ($this->dialog->askConfirmation(
                 $output,
                '<question>Those parameters will replace your parameters.yml file. Do you want to continue? (y/n) [y]</question>',
                true
            )) {
                file_put_contents($parameterPath, $yaml);
                $output->writeln('<info>parameters has been saved</info>');
        }
        
        
       
    }
    
   
   
    
    /**
     * createAdminUser
     * 
     * Create an admin user with the user parameters
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function createAdminUser(InputInterface $input, OutputInterface $output){
        
        
        
        $output->writeln('');$output->writeln('');       
        $output->writeln('<info>---------- Creating admin user -----------</info>');      

        
        $username = $this->dialog->askAndValidate(
            $output,
            'Please choose your admin User Name (avoid admin) : ',
            function ($answer) {
                if ($answer == '') {
                    throw new \RuntimeException(
                        'This parameter cannot be empty'
                    );
                }
                return $answer;
            }, false
        );       
        
           $email = $this->dialog->askAndValidate(
            $output,
            'Please choose your admin email : ',
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
        
          $password = $this->dialog->askAndValidate(
            $output,
            'Please choose your admin password. Note a too easy one please (note it carefully, it will not be easy to change this if you loose it): ',
            function ($answer) {
                if ($answer == '') {
                    throw new \RuntimeException(
                        'This parameter cannot be empty'
                    );
                }
                if (strlen($answer) < 6 ) {
                    throw new \RuntimeException(
                        'The password must at least have 6 characters'
                    );
                }
                return $answer;
            }, false
        );       
        
        
         
            
        $output->writeln(''); $output->writeln('');
        $output->writeln('<info>Your admin infos: </info>');       
        $output->writeln('<info>Username: '.$username.'</info>');       
        $output->writeln('<info>email: '.$email.'</info>');       
        $output->writeln('<info>Password: '.$password.'</info>');       
            
        $output->writeln('');        
        
        
        //create  user
        $process = new Process('php app/console fos:user:create '.$username.' '.$email.' '.$password);
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
        
        //activate user
        $process = new Process('php app/console fos:user:activate '.$username);
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
        
        
        //promote user to admin
        $process = new Process('php app/console fos:user:promote '.$username.' ROLE_ADMIN');
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
        
        
        $output->writeln('<info>Admin User created</info>');       

        
        
    }
    
   
    
    
    /**
     * apacheSettings
     * 
     * Manage the specific pache settings
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function apacheSettings($input, $output){
        $output->writeln('');$output->writeln('');       
     
         if ($this->dialog->askConfirmation(
                 $output,
                '<question>Do you want to set apache cache headers for assets in .htaccess? (y/n) [n]',
                false
            )) {
           $this->setApacheAssetsCacheHeaders($input, $output);
        }
        
    }    

    
    /**
     * setApacheAssetsCacheHeaders
     * 
     * uncomment the cache headers in .htaccess
     * 
     * @param type $input
     * @param type $output
     */
    protected function setApacheAssetsCacheHeaders($input, $output){
        $htaccessPath = $this->appPath.'../web/.htaccess';
        $htaccess = file_get_contents($htaccessPath);
        $htaccess = str_replace('#-#','',$htaccess);
        file_put_contents($htaccessPath, $htaccess);
    }
    
    
}