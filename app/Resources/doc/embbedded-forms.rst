=========================================
Embedded form windget
=========================================

Ajoutez vos embedded forms en One to Many en quelques secondes sans vous occuper de leur affichage 

Fonction Js
""""""""""""""""""
Ajouter des embedded form peut etre compliqué, surtout quand vous en ajoutez beaucoup dans un meme formulaire parent. 
La distribution Esokia integre donc une fonction générique pour générer les formulaires à partir de leur prototype.

La fonction est intégrée au fichier global.js pour etre disponible en front et en backend.


Ajouter un embedded form
"""""""""""""""""""""""""""
Nous ne réinventons pas la roue vous créez vos formulaires comme d'habitude en y ajoutant juste quelques attributs:
 - data-add-text
 - data-increment-text
 - data-delete-text


Dans votre formType.php ca donne: 

 public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        //.....

        ->add('field', 'collection', array(
            'type'         => new FieldType(),
            'allow_add'    => true,
            'allow_delete' => true,
            'by_reference' => false,
            'cascade_validation' => true,
            'attr'=> array(
                        'data-add-text'       => 'ajouter un field',
                        'data-increment-text' => 'field n°',
                        'data-delete-text'    => 'Supprimer ce field'
                    )              ))
          //.....
     ;
}


Vous pouvez en ajouter autant que vous le souhaitez. Enjoy!



Note, ce widget ne prend pas en compte les type array sans relation doctrine.