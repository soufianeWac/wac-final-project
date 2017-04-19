<?php 

namespace Silex\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints as Assert;

/*---------------------------------------------------------------------*/
/*                   //CONSTRUCT PARTICIPATION FORM//                  */
/*---------------------------------------------------------------------*/
class FormConstraintInscription extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
		return $builder->add(
		  'civility',
		  ChoiceType::class,
		  array(
		    'choices'     => array('Madame' => 'Madame', 'Monsieur' => 'Monsieur'),
		    'placeholder' => ' ',
		    'expanded'    => true,
		    'multiple'    => false,
		    'constraints' => array(
		      new Constraints\NotNull(array('message' => 'Veuillez sélectionner votre civilité')),
		    )
		  )
		)
		->add(
		  'lastname',
		  TextType::class,
		  array(
		    'required' => false,
		    'constraints' => array(
		      new Constraints\NotBlank(array('message' => 'Veuillez saisir votre nom')),
		      new Constraints\Length(array('max' => 60, 'maxMessage' => 'Veuillez saisir un nom correct')),
		      new Constraints\Regex(array(
		        'pattern' => REGEX_VALID_WORDS,
		        'match'   => true,
		        'message' => 'Veuillez saisir un nom correct',
		      )),
		    )
		  )
		)
		->add(
		  'firstname',
		  TextType::class,
		  array(
		    'required' => false,
		    'constraints' => array(
		      new Constraints\NotBlank(array('message' => 'Veuillez saisir votre prénom')),
		      new Constraints\Length(array('max' => 60, 'maxMessage' => 'Veuillez saisir un prénom correcte')),
		      new Constraints\Regex(array(
		        'pattern' => REGEX_VALID_WORDS,
		        'match'   => true,
		        'message' => 'Veuillez saisir un prénom correct',
		      )),
		    )
		  )
		)
		->add(
		  'mail',
		  EmailType::class,
		  array(
		    'required' => false,
		    'constraints' => array(
		      new Constraints\NotBlank(array('message' => 'Veuillez saisir votre adressse e-mail')),
		      new Constraints\Email(array('message' => 'Veuillez saisir une adressse e-mail correcte')),
		      new Constraints\Length(array('max' => 150, 'maxMessage' => 'Veuillez saisir une adressse e-mail correcte'))
		    )
		  )
		)
		->add(
		  'birthdate',
		  BirthdayType::class,
		  array(
		  	'widget' => 'text',
		  	'format' => 'dd-MM-yyyy',
		  	'input' => 'string',
		  	'invalid_message' => 'Veuillez saisir une date de naissance valide',
		    'required' => false,
		    'constraints' => array(
		      new Constraints\NotBlank(array('message' => 'Veuillez saisir votre date de naissance')),
      	  new Constraints\Date(array('message' => 'Veuillez saisir une date de naissance correcte')),
      	  // new Constraints\Callback(array('Vendor\Package\Validator', 'validateAge'))
		    )
		  )
		)
		->add(
		'username',
		TextType::class,
		array(
			'required' => false,
			'constraints' => array(
				new Constraints\NotBlank(array('message' => 'Veuillez saisir votre nom d\'utilisateur'))),
			)
		)
		->add(
		  'password',
		  PasswordType::class,
		  array(
		    'required' => false,
		    'constraints' => array(
		      new Constraints\NotBlank(array('message' => 'Veuillez renseigner votre mot de passe'))),
		  	)
		)
		->add(
			'confirm_password',
			PasswordType::class,
			array(
				'required' => false,
			)
		)
		->add(
	      'checkTerms',
	      CheckboxType::class,
	      array(
	        'required' => false,
	        'constraints' => array(
	          new Constraints\NotNull(array("message" => 'Veuillez reconnaitre avoir pris connaissance des termes et conditions du site')),
	          new Constraints\EqualTo(array("value" => true, "message" => 'Veuillez reconnaitre avoir pris connaissance des termes et conditions du site'))
	        )
	      )
	    );
    }
}
/*---------------------------------------------------------------------*/
/*                   		//CONSTRUCT CONNEXION FORM//                	 */
/*---------------------------------------------------------------------*/
class ConnexionFormConstraint extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    return $builder->add(
		  'login',
		  EmailType::class,
		  array(
		    'required' => false,
		    'constraints' => array(
		      new Constraints\NotBlank(array('message' => 'Veuillez saisir votre adressse e-mail')),
		      new Constraints\Email(array('message' => 'Veuillez saisir une adressse e-mail correcte')),
		      new Constraints\Length(array('max' => 150, 'maxMessage' => 'Veuillez saisir une adressse e-mail correcte'))
		    )
		  )
		)
    ->add(
      'password',
      PasswordType::class,
      array(
        'required' => false,
        'constraints' => array(
          new Constraints\NotBlank(array('message' => 'Veuillez renseigner votre mot de passe'))),
      )
    );
  }
}
/*---------------------------------------------------------------------*/
/*                   		//CONSTRUCT CONNEXION FORM//                	 */
/*---------------------------------------------------------------------*/
class VideoFormConstraint extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    return $builder->add(
			'name',
			TextType::class,
			[
				'required' =>false,
				'constraints' => [
					new Constraints\NotBlank(['message' => 'Veuillez ajouter un titre à votre vidéo'])
				]
			]
		)
    ->add(
      'description',
      TextareaType::class,
      [
      	'required' => false
      ]
    )
    ->add(
    	'category',
    	ChoiceType::class,
    	[
    		'choices' => [
          'Chanson' => 'Chanson',
          'Tutoriel' => 'Tutoriel',
          'Politique' => 'Politique',
          'Culture' => 'Culture',
          'Cinema' => 'Cinema',
          'Jeux et technologie' => 'Jeux et technologie',
          'Sport' => 'Sport',
          'Divers' => 'Divers'
        ],
    		'required' => false,
    		'expanded' => false,
        'multiple' => false,
    		'constraints' => [
    			new Constraints\NotBlank(['message' => 'Veuillez séléction une catégorie']),
    		]
    	]
    )
    ->add(
    	'videoFile',
    	FileType::class,
    	[
    		'required' => false,
    		'constraints' => [
    			new Constraints\NotBlank(['message' => 'Veuillez ajouter un fichier à votre vidéo']),
    			new Constraints\File(
          [
            'maxSize' => '2000M',
            'mimeTypes' => ['video/avi','video/mpeg','video/x-mpeg','video/quicktime','video/mp4'],
            'mimeTypesMessage' => "Les formats de video suporté sont mp4, AVI, quicktime et mpeg",
            'maxSizeMessage' => "Le fichier téléchargé ne doit pas dépasser les 2000M",
          ]),
    		]
    	]
    )
    ->add(
    	'imageVideo',
			FileType::class,
    	[
    		'required' => false,
    		'constraints' => [
    			new Constraints\File(
          [
            'maxSize' => '10M',
            'mimeTypes' => ['image/jpg','image/png','image/jpeg'],
            'mimeTypesMessage' => "Les formats d'image suportés sont PNG, JPG et JPEG",
            'maxSizeMessage' => "Le fichier téléchargé ne doit pas dépasser les 10M",
          ]),
    		]
    	]
    )
    ->add(
    	'display',
    	CheckboxType::class,
    	[
    		'required' => false
    	]
    );
  }
}
/*---------------------------------------------------------------------*/
/*                   	//CONSTRUCT CONNENTAIRE FORM//                	 */
/*---------------------------------------------------------------------*/
class CommentaireFormConstraint extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    return $builder->add(
      'comment',
      TextareaType::class,
      [
      	'required' => false,
      	'constraints' => [
    			new Constraints\NotBlank(['message' => 'Ce champs ne doit pas être vide']),
    		]
      ]
    );
  }
}
/*---------------------------------------------------------------------*/
/*                   	//CONSTRUCT EDIT PROFIL FORM//                	 */
/*---------------------------------------------------------------------*/
class FormConstraintEditProfil extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
		return $builder->add(
		'username',
		TextType::class,
		array(
			'required' => false,
			'constraints' => array(
				new Constraints\NotBlank(array('message' => 'Veuillez saisir votre nom d\'utilisateur'))),
			)
		)
    ->add(
    	'imageVideo',
			FileType::class,
    	[
    		'required' => false,
    		'constraints' => [
    			new Constraints\File(
          [
            'maxSize' => '10M',
            'mimeTypes' => ['image/jpg','image/png','image/jpeg'],
            'mimeTypesMessage' => "Les formats d'image suportés sont PNG, JPG et JPEG",
            'maxSizeMessage' => "Le fichier téléchargé ne doit pas dépasser les 10M",
          ]),
    		]
    	]
    );
  }
}