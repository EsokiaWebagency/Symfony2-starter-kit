
=====================
Esokia Contact Bundle
=====================

Bundle de gestion de la page de contact 
- Le formulaire se trouve derrière l'url /contact-us
- L'admin du formulaire se trouve derriere /admin/contact
Il a pour but de créedr un simple formulaire de contact quie envoie les emails a l'adresse site.email paramettrée dans le parameter.yml


***************
Front
***************
Un simple formulaire de contact qui sert de modele pour l'envoie d'emails avec SwiftMailer.

Champs du formulaire
- nom
- email
- sujet
- message

Il inclue un champs Honeypot pour diminuer le nombre de spam sans géner l'utilisateur.
Il enregistre l'ip de l'utilisateur qui envoie un mail et utilise l'annotation Timestampable pour enregistrer la date d'envoie. 


***************
Back end
***************
L'autre partie du CRUD se trouve dans l'espace sécurisé. 
Toute la puissance de la sécurité de symfony est là, il suffit d'ajouter le prefix ``/admin/`` aux URL de ses pages pour en sécuriser l'accès.

Dans cette partie ont peut voir et supprimer tous les emails qui ont étés envoyés via le formulaire de contact.



******************************
Configuration de Swift
******************************
L'application utilise les réglages de base de Swift, il est fort probable que vous deviez les modifier dans le fichier parameters.yml pour que vos mails partent. 
L'idéale est de régler l'envoie a travers `AWS SES <http://aws.amazon.com/fr/ses/>`_  pour avoir très simplement une plateforme ultra-perfomante, des emails signés etc...

L'utilisation du service Swift Mailer est très simple en suivant la documentation de Symfony. 
Retenez tout de meme une bonne pratique, toujours envoyer les mails au format HTML et au format Txt.
Voici la method du controller qui envoie les emails: 

    protected function sendContactEmail(Contact $contact){
        
             $message = \Swift_Message::newInstance();
             $message->setSubject($this->container->getParameter('site.name').' - new contact')
                        ->setFrom($contact->getEmail())
                        ->setTo($this->container->getParameter('site.email'))
                     ->setBody(
                            $this->renderView(
                                 'EsokiaContactBundle:Email:email.html.twig',
                                 array(
                                     'contact'=> $contact,
                                     )
                             ),
                              'text/html'
                         )

                        ->addPart($this->renderView(
                                        'EsokiaContactBundle:Email:email.txt.twig',
                                        array(
                                            'contact'=> $contact,
                                            )
                                        ), 'text/plain');

           return $this->get('mailer')->send($message);
    }





